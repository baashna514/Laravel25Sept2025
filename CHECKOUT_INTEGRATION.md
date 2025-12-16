# Easypaisa Checkout Integration

This document explains how the Easypaisa payment integration works with your existing checkout form.

## How It Works

### 1. **User Flow:**
1. User fills out the checkout form at `http://KCS.test/checkout`
2. User selects "easypaisa" as payment method
3. User enters their phone number (registered with Easypaisa)
4. User clicks "Submit" button
5. System calls our new Easypaisa API (`/api/easypaisa/pay`)
6. API returns HTML form for auto-submit to Easypaisa
7. New window opens with Easypaisa payment page
8. User completes payment on Easypaisa
9. Easypaisa sends callback to `/easypaisa/callback`
10. System updates booking status to "confirmed"

### 2. **Technical Implementation:**

#### Frontend (checkout-form.blade.php):
- Modified `handleEasyPaisaPayment()` function to use our new API
- Gets phone number from form input
- Gets total amount from cart
- Calls `/api/easypaisa/pay` with mobile number and amount
- Opens new window with Easypaisa payment form
- Saves booking data in background

#### Backend (PaymentController.php):
- `initiatePayment()` - Creates transaction and returns HTML form
- `paymentCallback()` - Handles Easypaisa callback and updates booking status

#### BookingController.php:
- Modified `doCheckout()` to handle Easypaisa payments
- Creates booking in "draft" status
- Links booking with transaction via order reference

### 3. **Database Flow:**

1. **Transaction Created:** When payment is initiated
   - `transactions` table: New record with status "pending"
   - `booking` table: New booking with status "draft"
   - `booking_meta` table: Links booking to transaction

2. **Payment Completed:** When Easypaisa sends callback
   - `transactions` table: Status updated to "success"
   - `booking` table: Status updated to "confirmed"
   - `booking_payments` table: Payment record created

### 4. **Files Modified:**

- `modules/Booking/Views/frontend/booking/checkout-form.blade.php` - Frontend integration
- `modules/Booking/Controllers/BookingController.php` - Booking handling
- `app/Http/Controllers/PaymentController.php` - Payment processing
- `routes/api.php` - API routes
- `routes/web.php` - Web routes
- `app/Http/Middleware/VerifyCsrfToken.php` - CSRF exceptions

### 5. **Testing:**

#### Test the Integration:
```bash
# Test API directly
php test_easypaisa_api.php

# Test checkout integration
php test_checkout_integration.php

# Test web interface
# Visit: http://KCS.test/checkout
```

#### Manual Testing:
1. Add items to cart
2. Go to checkout page
3. Fill in all required fields
4. Select "easypaisa" payment method
5. Enter phone number
6. Click Submit
7. Should open Easypaisa payment page in new window

### 6. **Configuration:**

Your existing `.env` configuration is used:
```env
EASYPAISA_STORE_ID=70126
EASYPAISA_ACCOUNT_ID=118028798
EASYPAISA_MERCHANT_NAME=StudentCare
EASYPAISA_SECRET_KEY=FTP0EKH68SWIJC5K
EASYPAISA_MODE=sandbox
EASYPAISA_CALLBACK_URL=http://KCS.test/payment/easypaisa/callback
EASYPAISA_RETURN_URL=http://KCS.test/booking/checkout
EASYPAISA_CANCEL_URL=http://KCS.test/payment/easypaisa/cancel
```

### 7. **Error Handling:**

- **Phone number validation:** Frontend checks if phone number is entered
- **Amount validation:** Frontend checks if amount is valid
- **API errors:** Displayed to user with clear messages
- **Callback verification:** Backend verifies Easypaisa callback authenticity
- **Transaction logging:** All transactions are logged for debugging

### 8. **Security Features:**

- **CSRF Protection:** All requests include CSRF tokens
- **Input Validation:** All inputs are validated and sanitized
- **Hash Verification:** Easypaisa callbacks are verified using HMAC SHA256
- **Session Management:** Order references are stored securely in session

### 9. **Monitoring:**

Check logs for debugging:
```bash
tail -f storage/logs/laravel.log
```

Look for entries:
- "Easypaisa Payment Initiated"
- "Easypaisa Callback Received"
- "Booking confirmed for Easypaisa payment"

### 10. **Production Deployment:**

1. Set `EASYPAISA_MODE=production` in `.env`
2. Update URLs to use HTTPS
3. Test thoroughly in sandbox mode first
4. Monitor logs after deployment
5. Verify callback URLs are accessible

## Summary

The integration is now complete and ready for production use. Users can select Easypaisa as a payment method on your checkout form, and the system will automatically redirect them to Easypaisa for payment completion, then update the booking status upon successful payment.

