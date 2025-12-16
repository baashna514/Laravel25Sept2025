<?php
/**
 * Easypaisa API Test Script
 * 
 * This script tests the Easypaisa payment API
 * Run this from your project root: php test_easypaisa_api.php
 */

echo "=== Easypaisa API Test ===\n\n";

// Test data
$testData = [
    'mobile_number' => '03001234567',
    'amount' => 100
];

echo "Testing with data:\n";
echo "Mobile: {$testData['mobile_number']}\n";
echo "Amount: {$testData['amount']} PKR\n\n";

// Prepare the request
$url = 'http://KCS.test/api/easypaisa/pay';
$data = json_encode($testData);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Accept: application/json',
    'X-CSRF-TOKEN: test-token' // You may need to get a real CSRF token
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);

echo "Sending request to: {$url}\n";
echo "Request data: {$data}\n\n";

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
    if ($responseData && isset($responseData['success']) && $responseData['success']) {
        echo "✅ API Test Successful!\n";
        echo "Transaction ID: " . ($responseData['transaction_id'] ?? 'N/A') . "\n";
        echo "Form HTML length: " . strlen($responseData['form'] ?? '') . " characters\n";
    } else {
        echo "❌ API returned error: " . ($responseData['message'] ?? 'Unknown error') . "\n";
    }
} else {
    echo "❌ API request failed with HTTP {$httpCode}\n";
}

echo "\n=== Test Complete ===\n";

