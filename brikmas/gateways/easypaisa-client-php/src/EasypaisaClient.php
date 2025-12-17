<?php


namespace Brikmas\Easypaisa;

use Brikmas\Easypaisa\Entities\InquireTxnReq;
use Brikmas\Easypaisa\Entities\PaymentReq;

/**
 * Class EasypaisaClient
 *
 * REST service handler
 *
 * @package Brikmas\Gateway\Easypaisa
 */
class EasypaisaClient
{
    const VERSION = 1.0;

    private $apiBaseUrl;
    private $merchantId;
    private $username;
    private $password;

    /**
     * Client constructor.
     *
     * @param array $config
     * @throws \Exception
     */
    public function __construct($config = [])
    {
        if (!empty($config) && !is_array($config)) {
            throw new \Exception('$config must be an an array');
        }

        $this->apiBaseUrl = $config['apiBaseUrl'] ?? getenv('EASYPAISA_API_BASE_URL');
        $this->merchantId = $config['merchantId'] ?? getenv('EASYPAISA_MERCHANT_ID');
        $this->username   = $config['username']   ?? getenv('EASYPAISA_USERNAME');
        $this->password   = $config['password']   ?? getenv('EASYPAISA_PASSWORD');

        if (empty($this->apiBaseUrl)) {
            throw new \Exception('Base url is required');
        }

        if (empty($this->merchantId)) {
            throw new \Exception('Merchant/store id is required');
        }

        if (empty($this->password)) {
            throw new \Exception('Password is required');
        }

        if (empty($this->username)) {
            throw new \Exception('Username is required');
        }
    }

    /**
     * Mobile wallet method
     *
     * @param PaymentReq $payload
     * @return object
     * @throws \Exception
     */
    public function callMobileAccountService(PaymentReq $payload)
    {
        $this->setServiceUrl('initiate-ma-transaction');
        $payload->setTxnType('MA');

        return $this->callService($payload);
    }

    /**
     * Voucher payment method
     *
     * @param PaymentReq $payload
     * @return object
     * @throws \Exception
     */
    public function callVoucherService(PaymentReq $payload)
    {
        $this->setServiceUrl('initiate-otc-transaction');
        $payload->setTxnType('OTC');

        return $this->callService($payload);
    }

    /**
     * Transaction inquiry
     *
     * The Inquire Transaction Status API is used to inquire
     * transaction information of the referenced transaction.
     *
     * @param InquireTxnReq $InquireTxnReq
     * @return object
     * @throws \Exception
     */
    public function callTxnInquiryService(InquireTxnReq $InquireTxnReq)
    {
        $this->setServiceUrl('inquire-transaction');

        return $this->callService($InquireTxnReq);
    }

    /**
     * @param $uri
     */
    private function setServiceUrl($uri)
    {
        $this->apiBaseUrl = rtrim($this->apiBaseUrl, '/');
        $this->apiBaseUrl .= '/easypay-service/rest/v4/' . $uri;
    }

    /**
     * @param PaymentReq $payload
     * @return object
     * @throws \Exception
     */
    private function callService(PaymentReq $payload)
    {
        $payload->setMerchantId($this->merchantId);

        $cURLConnection = curl_init($this->apiBaseUrl);
        curl_setopt($cURLConnection, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Credentials: ' . base64_encode(sprintf('%s:%s', $this->username, $this->password))
        ]);
        curl_setopt($cURLConnection, CURLOPT_POST, true);
        curl_setopt($cURLConnection, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($cURLConnection, CURLOPT_FAILONERROR, true);
        curl_setopt($cURLConnection, CURLOPT_SSL_VERIFYPEER, false);

        $apiResponse = curl_exec($cURLConnection);

        if (curl_errno($cURLConnection)) {
            throw new \Exception('Fatal Error: ' . curl_error($cURLConnection));
        }

        curl_close($cURLConnection);

        return json_decode($apiResponse);
    }
}