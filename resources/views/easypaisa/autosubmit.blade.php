<!DOCTYPE html>
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
            margin: 0;
        }
        .container {
            max-width: 400px;
            margin: 0 auto;
            background: white;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .loading { 
            color: #2ecc71; 
            font-size: 18px; 
            margin-bottom: 20px;
            font-weight: 500;
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
        .info {
            color: #666;
            font-size: 14px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="spinner"></div>
        <div class="loading">Redirecting to Easypaisa Payment...</div>
        <div class="info">Please wait while we redirect you to complete your payment.</div>
        
        <form id="easypaisaForm" action="{{ $formAction }}" method="POST">
            @foreach($paymentData as $key => $value)
                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
            @endforeach
        </form>
    </div>

    <script>
        // Auto-submit form after 1 second
        setTimeout(function() {
            document.getElementById('easypaisaForm').submit();
        }, 1000);
        
        // Fallback: submit immediately if user clicks anywhere
        document.addEventListener('click', function() {
            document.getElementById('easypaisaForm').submit();
        });
    </script>
</body>
</html>

