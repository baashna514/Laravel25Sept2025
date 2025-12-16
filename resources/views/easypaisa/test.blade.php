<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Easypaisa Payment Test - {{ config('app.name') }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #333;
        }
        input[type="text"], input[type="number"] {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
            box-sizing: border-box;
        }
        input[type="text"]:focus, input[type="number"]:focus {
            outline: none;
            border-color: #2ecc71;
            box-shadow: 0 0 5px rgba(46, 204, 113, 0.3);
        }
        .btn {
            background-color: #2ecc71;
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            width: 100%;
        }
        .btn:hover {
            background-color: #27ae60;
        }
        .btn:disabled {
            background-color: #bdc3c7;
            cursor: not-allowed;
        }
        .info {
            background-color: #e8f4fd;
            border: 1px solid #bee5eb;
            color: #0c5460;
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
        }
        .config-info {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
        }
        .config-info h4 {
            margin-top: 0;
            color: #495057;
        }
        .config-info code {
            background-color: #e9ecef;
            padding: 2px 4px;
            border-radius: 3px;
            font-family: monospace;
        }
        .status {
            padding: 10px;
            border-radius: 4px;
            margin: 10px 0;
        }
        .status.success {
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
        }
        .status.error {
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Easypaisa Payment Integration Test</h2>
        
        <div class="info">
            <strong>Configuration Status:</strong> 
            @if(config('easypaisa.store_id') && config('easypaisa.hash_key'))
                <span class="status success">✅ Configuration loaded successfully</span>
            @else
                <span class="status error">❌ Configuration incomplete - check your .env file</span>
            @endif
        </div>

        <div class="config-info">
            <h4>Current Configuration:</h4>
            <p><strong>Store ID:</strong> <code>{{ config('easypaisa.store_id') ?: 'Not set' }}</code></p>
            <p><strong>Account ID:</strong> <code>{{ config('easypaisa.account_id') ?: 'Not set' }}</code></p>
            <p><strong>Merchant Name:</strong> <code>{{ config('easypaisa.merchant_name') ?: 'Not set' }}</code></p>
            <p><strong>Mode:</strong> <code>{{ config('easypaisa.mode') ?: 'sandbox' }}</code></p>
            <p><strong>Callback URL:</strong> <code>{{ config('easypaisa.callback_url') ?: 'Not set' }}</code></p>
        </div>

        <form id="easypaisa-payment-form">
            <div class="form-group">
                <label for="mobile_number">Mobile Number:</label>
                <input type="text" id="mobile_number" name="mobile_number" 
                       placeholder="e.g., 03001234567 or +923001234567" 
                       value="03001234567" required>
                <small>Enter Pakistani mobile number (with or without +92)</small>
            </div>

            <div class="form-group">
                <label for="amount">Amount (PKR):</label>
                <input type="number" id="amount" name="amount" 
                       placeholder="Enter amount in PKR" 
                       value="100" min="1" max="1000000" step="0.01" required>
                <small>Enter amount between 1 and 1,000,000 PKR</small>
            </div>

            <button type="submit" id="submit-btn" class="btn">
                Pay with Easypaisa
            </button>
        </form>

        <div id="response" style="margin-top: 20px;"></div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('easypaisa-payment-form');
            const submitBtn = document.getElementById('submit-btn');
            const responseDiv = document.getElementById('response');

            form.addEventListener('submit', async function(e) {
                e.preventDefault();
                
                const mobileNumber = document.getElementById('mobile_number').value;
                const amount = parseFloat(document.getElementById('amount').value);
                
                // Validate inputs
                if (!mobileNumber || !amount) {
                    showResponse('Please fill in all fields', 'error');
                    return;
                }
                
                // Show loading state
                const originalText = submitBtn.textContent;
                submitBtn.textContent = 'Processing...';
                submitBtn.disabled = true;
                
                try {
                    const response = await fetch('/api/easypaisa/pay', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            mobile_number: mobileNumber,
                            amount: amount
                        })
                    });

                    const data = await response.json();

                    if (data.success) {
                        showResponse('Payment initiated successfully! Opening payment window...', 'success');
                        
                        // Open payment window
                        const paymentWindow = window.open('', 'easypaisa_payment', 'width=800,height=600,scrollbars=yes,resizable=yes');
                        if (paymentWindow) {
                            paymentWindow.document.write(data.form);
                            paymentWindow.document.close();
                            paymentWindow.focus();
                        } else {
                            showResponse('Popup blocked. Please allow popups and try again.', 'error');
                        }
                    } else {
                        showResponse('Payment failed: ' + (data.message || 'Unknown error'), 'error');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    showResponse('Network error: ' + error.message, 'error');
                } finally {
                    // Reset button state
                    submitBtn.textContent = originalText;
                    submitBtn.disabled = false;
                }
            });

            function showResponse(message, type) {
                responseDiv.innerHTML = `<div class="status ${type}">${message}</div>`;
            }
        });
    </script>
</body>
</html>

