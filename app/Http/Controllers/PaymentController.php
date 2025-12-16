<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;
use App\Transaction;

class PaymentController extends Controller
{
    /**
     * Initiate Easypaisa payment
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function initiatePayment(Request $request)
    {
        try {
            // Validate request
            $request->validate([
                'mobile_number' => 'required|string|min:11|max:15',
                'amount' => 'required|numeric|min:1'
            ]);

            $mobileNumber = $request->input('mobile_number');
//            $amount = $request->input('amount');
            $amount = 1;

            // Generate unique transaction ID
            $transactionId = 'TXN_' . time() . '_' . Str::random(8);

            // Get config values
            $storeId = '1144878';
            $accountId = '1144878';
            $merchantName = 'kingcambridgesolutions';
            $hashKey = 'FTP0EKH68SWIJC5K';
            $mode = 'production';
//            $sandboxUrl = config('easypaisa.sandbox_url');
            $liveUrl = 'https://easypay.easypaisa.com.pk/easypay/Index.jsf';
            $paymentMethod = 'MA';
            $currency = 'PKR';

            // Validate configuration
            if (empty($storeId) || empty($hashKey) || empty($accountId) || empty($merchantName)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Easypaisa configuration incomplete. Please check your environment variables.'
                ], 500);
            }

            // Format amount to 2 decimal places
            $formattedAmount = number_format($amount, 2, '.', '');

            // Calculate expiry date (24 hours from now)
            $expiryDate = date('Ymd', strtotime('+24 hours'));

            // Generate order reference number
            $orderRefNum = 'KCS_' . $transactionId;

            // Prepare callback URL
            $callbackUrl = route('easypaisa.callback');
            $returnUrl = config('easypaisa.return_url', url('/'));
            $cancelUrl = config('easypaisa.cancel_url', url('/'));

            // Prepare payment data
            $paymentData = [
                'amount' => $formattedAmount,
                'autoRedirect' => '1',
                'emailAddr' => 'customer@example.com', // You can modify this based on your needs
                'mobileNum' => $mobileNumber,
                'orderRefNum' => $orderRefNum,
                'paymentMethod' => $paymentMethod,
                'postBackURL' => $callbackUrl,
                'storeId' => $storeId,
                'returnUrl' => $returnUrl,
                'cancelUrl' => $cancelUrl,
                'expiryDate' => $expiryDate,
                'merchantName' => $merchantName,
                'accountId' => $accountId,
            ];

            // Generate hash string for HMAC SHA256
            $hashString = $formattedAmount . '&' . '1' . '&' . $paymentData['emailAddr'] . '&' . $mobileNumber . '&' . $orderRefNum . '&' . $paymentMethod . '&' . $callbackUrl . '&' . $storeId;

            // Generate HMAC SHA256 hash
            $merchantHashedReq = hash_hmac('sha256', $hashString, $hashKey);
            $paymentData['merchantHashedReq'] = $merchantHashedReq;

            // Determine the correct Easypaisa endpoint
            $formAction = $mode === 'production' ? $liveUrl : $sandboxUrl;

            // Create transaction record
            Transaction::create([
                'transaction_id' => $transactionId,
                'order_ref_num' => $orderRefNum,
                'mobile_number' => $mobileNumber,
                'amount' => $formattedAmount,
                'status' => 'pending',
                'callback_data' => $paymentData
            ]);

            // Store order reference in session for later booking creation
            session(['easypaisa_order_ref' => $orderRefNum]);

            // Log payment initiation
            Log::info('Easypaisa Payment Initiated', [
                'transaction_id' => $transactionId,
                'order_ref' => $orderRefNum,
                'amount' => $formattedAmount,
                'mobile' => $mobileNumber,
                'mode' => $mode,
                'hash_string' => $hashString
            ]);

            // Generate HTML form for auto-submit
            $formHtml = $this->generateAutoSubmitForm($paymentData, $formAction);

            return response()->json([
                'success' => true,
                'transaction_id' => $transactionId,
                'form' => $formHtml
            ]);

        } catch (\Exception $e) {
            Log::error('Easypaisa Payment Initiation Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Payment initiation failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Handle Easypaisa payment callback
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function paymentCallback(Request $request)
    {
        try {
            // Capture raw body and query string for exact payload comparison
            $rawBody = file_get_contents('php://input');
            $queryString = $request->getQueryString();

            // Log all incoming callback data to dedicated file
            Log::channel('easypaisa')->debug('EP Callback Received', [
                'method' => $request->method(),
                'all_data' => $request->all(),
                'headers' => $request->headers->all()
            ]);

            Log::channel('easypaisa')->debug('EP Callback Raw', [
                'raw_body' => $rawBody,
                'query_string' => $queryString,
                'url' => $request->fullUrl(),
            ]);

            $orderRefNum = $request->input('orderRefNum');
            $status = $request->input('status');
            $transactionId = $request->input('transactionId');
            $amount = $request->input('amount');
            $storeId = $request->input('storeId');
            $responseCode = $request->input('responseCode');

            // Find transaction by order reference
            $transaction = Transaction::where('order_ref_num', $orderRefNum)->first();

            if (!$transaction) {
                Log::error('Easypaisa Callback: Transaction not found', [
                    'order_ref' => $orderRefNum
                ]);
                return response('OK', 200);
            }

            // Find booking by order reference (check if it exists in booking meta)
            $booking = null;
            if (class_exists('\Modules\Booking\Models\Booking')) {
                $booking = \Modules\Booking\Models\Booking::whereHas('meta', function($query) use ($orderRefNum) {
                    $query->where('name', 'easypaisa_order_ref')
                          ->where('val', $orderRefNum);
                })->first();
            }

            // Verify callback authenticity
            if ($this->verifyCallback($request)) {
                // Determine transaction status
                $transactionStatus = 'failed';
                if ($responseCode == '0000' || $status == 'SUCCESS' || $status == 'COMPLETED') {
                    $transactionStatus = 'success';
                    Log::info('Easypaisa Payment Successful', [
                        'order_ref' => $orderRefNum,
                        'transaction_id' => $transactionId,
                        'amount' => $amount,
                        'status' => $status
                    ]);
                } else {
                    Log::warning('Easypaisa Payment Failed', [
                        'order_ref' => $orderRefNum,
                        'response_code' => $responseCode,
                        'status' => $status,
                        'amount' => $amount
                    ]);
                }

                // Update transaction record
                $transaction->update([
                    'status' => $transactionStatus,
                    'easypaisa_transaction_id' => $transactionId,
                    'response_code' => $responseCode,
                    'callback_data' => $request->all()
                ]);

                // Update booking status if booking exists
                if ($booking && $transactionStatus === 'success') {
                    $booking->status = 'confirmed';
                    $booking->save();

                    // Create payment record if BookingPayment class exists
                    if (class_exists('\Modules\Booking\Models\Payment')) {
                        \Modules\Booking\Models\Payment::create([
                            'booking_id' => $booking->id,
                            'payment_gateway' => 'easypaisa',
                            'amount' => $amount,
                            'currency' => 'PKR',
                            'converted_amount' => $amount,
                            'converted_currency' => 'PKR',
                            'exchange_rate' => 1,
                            'status' => 'completed',
                            'logs' => json_encode($request->all()),
                            'create_user' => $booking->customer_id,
                            'update_user' => $booking->customer_id,
                        ]);
                    }

                    Log::info('Booking confirmed for Easypaisa payment', [
                        'booking_id' => $booking->id,
                        'transaction_id' => $transactionId,
                        'amount' => $amount
                    ]);
                }

            } else {
                Log::error('Easypaisa Callback Verification Failed', [
                    'order_ref' => $orderRefNum,
                    'all_data' => $request->all()
                ]);

                // Update transaction as failed due to verification failure
                $transaction->update([
                    'status' => 'failed',
                    'callback_data' => $request->all()
                ]);
            }

            // Always return 200 OK to Easypaisa
            return response('OK', 200);

        } catch (\Exception $e) {
            Log::error('Easypaisa Callback Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);

            return response('OK', 200);
        }
    }

    /**
     * Generate auto-submit HTML form
     *
     * @param array $paymentData
     * @param string $formAction
     * @return string
     */
    private function generateAutoSubmitForm(array $paymentData, string $formAction): string
    {
        $formHtml = '<!DOCTYPE html>
<html>
<head>
    <title>Redirecting to Easypaisa...</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            padding: 50px;
            background-color: #f8f9fa;
        }
        .loading {
            color: #2ecc71;
            font-size: 18px;
            margin-bottom: 20px;
        }
        .spinner {
            border: 4px solid #f3f3f3;
            border-top: 4px solid #2ecc71;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 2s linear infinite;
            margin: 0 auto 20px;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <div class="spinner"></div>
    <div class="loading">Redirecting to Easypaisa Payment...</div>
    <form id="easypaisaForm" action="' . htmlspecialchars($formAction) . '" method="POST">';

        foreach ($paymentData as $key => $value) {
            $formHtml .= '<input type="hidden" name="' . htmlspecialchars($key) . '" value="' . htmlspecialchars($value) . '">';
        }

        $formHtml .= '</form>
    <script>
        setTimeout(function() {
            document.getElementById("easypaisaForm").submit();
        }, 1000);
    </script>
</body>
</html>';

        return $formHtml;
    }

    /**
     * Verify Easypaisa callback authenticity
     *
     * @param Request $request
     * @return bool
     */
    private function verifyCallback(Request $request): bool
    {
        $orderRefNum = $request->input('orderRefNum');
        $amount = $request->input('amount');
        $status = $request->input('status');
        $storeId = $request->input('storeId');
        $responseCode = $request->input('responseCode');

        // Verify store ID matches configuration
        if ($storeId !== config('easypaisa.store_id')) {
            Log::error('Easypaisa callback: Store ID mismatch', [
                'expected' => config('easypaisa.store_id'),
                'received' => $storeId
            ]);
            return false;
        }

        // Additional verification can be added here based on your business logic
        // For example, verify amount, check if order exists in database, etc.

        return true;
    }
}
