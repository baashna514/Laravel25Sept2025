<?php
/**
 * Test Checkout Integration with Easypaisa
 * 
 * This script tests the checkout form integration
 */

echo "=== Checkout Integration Test ===\n\n";

// Test the checkout form submission
$checkoutData = [
    'first_name' => 'Test',
    'last_name' => 'User',
    'email' => 'test@example.com',
    'phone' => '03001234567',
    'country' => 'Pakistan',
    'payment_gateway' => 'easypaisa',
    'term_conditions' => '1'
];

echo "Testing checkout form submission with Easypaisa...\n";
echo "Data: " . json_encode($checkoutData) . "\n\n";

$url = 'http://KCS.test/booking/doCheckout';
$data = http_build_query($checkoutData);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/x-www-form-urlencoded',
    'Accept: application/json'
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);

echo "Sending request to: {$url}\n";

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);

curl_close($ch);

if ($error) {
    echo "❌ cURL Error: {$error}\n";
    exit(1);
}

echo "HTTP Status Code: {$httpCode}\n";
echo "Response:\n";
echo $response . "\n\n";

if ($httpCode === 200) {
    $responseData = json_decode($response, true);
    if ($responseData && isset($responseData['status']) && $responseData['status']) {
        echo "✅ Checkout Integration Successful!\n";
        echo "Booking ID: " . ($responseData['data']['booking_id'] ?? 'N/A') . "\n";
        echo "Status: " . ($responseData['data']['status'] ?? 'N/A') . "\n";
    } else {
        echo "❌ Checkout failed: " . ($responseData['message'] ?? 'Unknown error') . "\n";
    }
} else {
    echo "❌ Checkout request failed with HTTP {$httpCode}\n";
}

echo "\n=== Integration Test Complete ===\n";
echo "Now test the full flow:\n";
echo "1. Visit: http://KCS.test/checkout\n";
echo "2. Fill in the form with your details\n";
echo "3. Select 'easypaisa' as payment method\n";
echo "4. Click Submit - it should redirect to Easypaisa payment page\n";
echo "5. Complete the payment on Easypaisa\n";
echo "6. Check the callback at: http://KCS.test/easypaisa/callback\n";

