# Easypaisa Payment Integration

This document describes the complete Easypaisa payment integration for Laravel 7 with PHP 7.2.

## Features

- ✅ Clean, production-ready code
- ✅ PSR-12 compliant
- ✅ Complete API endpoints
- ✅ Auto-submit HTML form generation
- ✅ Transaction logging
- ✅ CSRF protection
- ✅ Comprehensive error handling
- ✅ HMAC SHA256 hash generation
- ✅ Callback verification

## Files Created/Modified

### 1. Configuration
- `config/easypaisa.php` - Easypaisa configuration file

### 2. Controller
- `app/Http/Controllers/PaymentController.php` - Main payment controller

### 3. Model & Migration
- `app/Transaction.php` - Transaction model
- `database/migrations/2024_01_15_000000_create_transactions_table.php` - Transaction table migration

### 4. Routes
- `routes/api.php` - API routes (POST /api/easypaisa/pay)
- `routes/web.php` - Web routes (POST /easypaisa/callback)

### 5. Middleware
- `app/Http/Middleware/VerifyCsrfToken.php` - CSRF exception for callback

### 6. Views
- `resources/views/easypaisa/autosubmit.blade.php` - Auto-submit form template

### 7. Frontend Examples
- `public/js/easypaisa-example.js` - JavaScript integration example
- `public/easypaisa-test.html` - Test form

## Environment Variables

Your existing `.env` configuration is already set up correctly:

```env
# Easypaisa Configuration (Already configured)
EASYPAISA_STORE_ID=70126
EASYPAISA_ACCOUNT_ID=118028798
EASYPAISA_MERCHANT_NAME=StudentCare
EASYPAISA_SECRET_KEY=FTP0EKH68SWIJC5K
EASYPAISA_MODE=sandbox
EASYPAISA_CALLBACK_URL=http://KCS.test/payment/easypaisa/callback
EASYPAISA_RETURN_URL=http://KCS.test/booking/checkout
EASYPAISA_CANCEL_URL=http://KCS.test/payment/easypaisa/cancel
```

**Note**: The integration uses `EASYPAISA_SECRET_KEY` as the hash key for HMAC SHA256 generation.

## API Endpoints

### 1. Initiate Payment
**POST** `/api/easypaisa/pay`

**Request Body:**
```json
{
    "mobile_number": "03001234567",
    "amount": 100
}
```

**Response:**
```json
{
    "success": true,
    "transaction_id": "TXN_1234567890_abc123",
    "form": "<HTML form for auto-submit>"
}
```

### 2. Payment Callback
**POST** `/easypaisa/callback`

This endpoint receives callbacks from Easypaisa after payment completion.

## Database Migration

Run the migration to create the transactions table:

```bash
php artisan migrate
```

## Usage Examples

### Frontend JavaScript

```javascript
// Initialize the payment class
const easypaisa = new EasypaisaPayment();

// Initiate payment
easypaisa.initiatePayment(
    '03001234567',  // mobile number
    100,            // amount
    function(response) {
        // Success callback
        console.log('Payment initiated:', response);
    },
    function(error) {
        // Error callback
        console.error('Payment failed:', error);
    }
);
```

### Axios Example

```javascript
// Using Axios
const response = await axios.post('/api/easypaisa/pay', {
    mobile_number: '03001234567',
    amount: 100
});

if (response.data.success) {
    // Open payment window
    const paymentWindow = window.open('', 'easypaisa_payment');
    paymentWindow.document.write(response.data.form);
    paymentWindow.document.close();
}
```

### cURL Example

```bash
curl -X POST http://yoursite.com/api/easypaisa/pay \
  -H "Content-Type: application/json" \
  -H "X-CSRF-TOKEN: your_csrf_token" \
  -d '{
    "mobile_number": "03001234567",
    "amount": 100
  }'
```

## Testing

1. **Test Form**: Visit `/easypaisa-test.html` to test the integration
2. **API Testing**: Use Postman or similar tools to test the API endpoints
3. **Callback Testing**: Use ngrok or similar tools to test callbacks locally

## Security Features

1. **CSRF Protection**: API routes are protected with CSRF tokens
2. **Input Validation**: All inputs are validated and sanitized
3. **Hash Verification**: Callbacks are verified using HMAC SHA256
4. **Transaction Logging**: All transactions are logged for audit purposes

## Error Handling

The integration includes comprehensive error handling:

- Input validation errors
- Configuration validation
- Network errors
- Payment failures
- Callback verification failures

All errors are logged for debugging purposes.

## Transaction Status

Transactions can have the following statuses:

- `pending` - Payment initiated, waiting for completion
- `success` - Payment completed successfully
- `failed` - Payment failed
- `cancelled` - Payment was cancelled

## Monitoring

Check the Laravel logs for detailed information:

```bash
tail -f storage/logs/laravel.log
```

Look for log entries with:
- "Easypaisa Payment Initiated"
- "Easypaisa Callback Received"
- "Easypaisa Payment Successful"
- "Easypaisa Payment Failed"

## Production Deployment

1. Set `EASYPAISA_MODE=production` in your `.env` file
2. Update URLs to use HTTPS
3. Ensure all environment variables are properly set
4. Test thoroughly in sandbox mode first
5. Monitor logs after deployment

## Support

For issues or questions:

1. Check the Laravel logs
2. Verify environment variables
3. Test with the provided test form
4. Check Easypaisa documentation for API changes

## License

This integration is provided as-is for educational and commercial use.
