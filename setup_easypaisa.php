<?php
/**
 * Easypaisa Integration Setup Script
 * 
 * This script helps you set up the Easypaisa payment integration
 * Run this from your project root: php setup_easypaisa.php
 */

echo "=== Easypaisa Payment Integration Setup ===\n\n";

// Check if we're in the right directory
if (!file_exists('artisan')) {
    echo "❌ Error: Please run this script from your Laravel project root directory.\n";
    exit(1);
}

echo "✅ Laravel project detected\n";

// Check configuration
$configFile = 'config/easypaisa.php';
if (file_exists($configFile)) {
    echo "✅ Configuration file exists: {$configFile}\n";
} else {
    echo "❌ Configuration file missing: {$configFile}\n";
    exit(1);
}

// Check controller
$controllerFile = 'app/Http/Controllers/PaymentController.php';
if (file_exists($controllerFile)) {
    echo "✅ PaymentController exists: {$controllerFile}\n";
} else {
    echo "❌ PaymentController missing: {$controllerFile}\n";
    exit(1);
}

// Check model
$modelFile = 'app/Transaction.php';
if (file_exists($modelFile)) {
    echo "✅ Transaction model exists: {$modelFile}\n";
} else {
    echo "❌ Transaction model missing: {$modelFile}\n";
    exit(1);
}

// Check migration
$migrationFile = 'database/migrations/2024_01_15_000000_create_transactions_table.php';
if (file_exists($migrationFile)) {
    echo "✅ Migration file exists: {$migrationFile}\n";
} else {
    echo "❌ Migration file missing: {$migrationFile}\n";
    exit(1);
}

// Check views
$viewDir = 'resources/views/easypaisa';
if (is_dir($viewDir)) {
    echo "✅ Views directory exists: {$viewDir}\n";
} else {
    echo "❌ Views directory missing: {$viewDir}\n";
    exit(1);
}

echo "\n=== Environment Configuration Check ===\n";

// Load environment variables
$envFile = '.env';
if (file_exists($envFile)) {
    $envContent = file_get_contents($envFile);
    
    $requiredVars = [
        'EASYPAISA_STORE_ID',
        'EASYPAISA_ACCOUNT_ID', 
        'EASYPAISA_MERCHANT_NAME',
        'EASYPAISA_SECRET_KEY',
        'EASYPAISA_MODE',
        'EASYPAISA_CALLBACK_URL',
        'EASYPAISA_RETURN_URL',
        'EASYPAISA_CANCEL_URL'
    ];
    
    foreach ($requiredVars as $var) {
        if (strpos($envContent, $var) !== false) {
            echo "✅ {$var} is configured\n";
        } else {
            echo "❌ {$var} is missing from .env file\n";
        }
    }
} else {
    echo "❌ .env file not found\n";
}

echo "\n=== Next Steps ===\n";
echo "1. Run the SQL migration manually:\n";
echo "   - Open your database management tool (phpMyAdmin, MySQL Workbench, etc.)\n";
echo "   - Run the SQL from: database/sql/create_transactions_table.sql\n\n";

echo "2. Test the integration:\n";
echo "   - Visit: http://KCS.test/easypaisa-test\n";
echo "   - Or use the API directly: POST /api/easypaisa/pay\n\n";

echo "3. API Usage Example:\n";
echo "   curl -X POST http://KCS.test/api/easypaisa/pay \\\n";
echo "     -H \"Content-Type: application/json\" \\\n";
echo "     -H \"X-CSRF-TOKEN: your_token\" \\\n";
echo "     -d '{\"mobile_number\": \"03001234567\", \"amount\": 100}'\n\n";

echo "4. Check logs for debugging:\n";
echo "   tail -f storage/logs/laravel.log\n\n";

echo "=== Setup Complete ===\n";
echo "Your Easypaisa payment integration is ready to use!\n";

