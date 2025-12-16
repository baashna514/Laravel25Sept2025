/**
 * Easypaisa Payment Integration Example
 * 
 * This file demonstrates how to use the Easypaisa payment API
 * from your frontend application.
 */

class EasypaisaPayment {
    constructor() {
        this.apiUrl = '/api/easypaisa/pay';
        this.csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    }

    /**
     * Initiate payment with Easypaisa
     * 
     * @param {string} mobileNumber - Customer mobile number
     * @param {number} amount - Payment amount
     * @param {function} onSuccess - Success callback
     * @param {function} onError - Error callback
     */
    async initiatePayment(mobileNumber, amount, onSuccess, onError) {
        try {
            const response = await fetch(this.apiUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    mobile_number: mobileNumber,
                    amount: amount
                })
            });

            const data = await response.json();

            if (data.success) {
                // Create a new window/tab with the auto-submit form
                this.openPaymentWindow(data.form);
                
                if (onSuccess) {
                    onSuccess(data);
                }
            } else {
                if (onError) {
                    onError(data.message || 'Payment initiation failed');
                }
            }
        } catch (error) {
            console.error('Easypaisa Payment Error:', error);
            if (onError) {
                onError('Network error occurred');
            }
        }
    }

    /**
     * Open payment window with auto-submit form
     * 
     * @param {string} formHtml - HTML form string
     */
    openPaymentWindow(formHtml) {
        // Create a new window
        const paymentWindow = window.open('', 'easypaisa_payment', 'width=800,height=600,scrollbars=yes,resizable=yes');
        
        if (paymentWindow) {
            paymentWindow.document.write(formHtml);
            paymentWindow.document.close();
            
            // Focus the window
            paymentWindow.focus();
        } else {
            // Fallback: redirect current window
            document.body.innerHTML = formHtml;
        }
    }

    /**
     * Validate mobile number format
     * 
     * @param {string} mobileNumber - Mobile number to validate
     * @returns {boolean}
     */
    validateMobileNumber(mobileNumber) {
        // Pakistani mobile number validation
        const mobileRegex = /^(\+92|0)?[0-9]{10}$/;
        return mobileRegex.test(mobileNumber.replace(/\s/g, ''));
    }

    /**
     * Validate amount
     * 
     * @param {number} amount - Amount to validate
     * @returns {boolean}
     */
    validateAmount(amount) {
        return typeof amount === 'number' && amount > 0 && amount <= 1000000; // Max 1M PKR
    }
}

// Example usage:
document.addEventListener('DOMContentLoaded', function() {
    const easypaisa = new EasypaisaPayment();
    
    // Example form submission
    const paymentForm = document.getElementById('easypaisa-payment-form');
    if (paymentForm) {
        paymentForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const mobileNumber = document.getElementById('mobile_number').value;
            const amount = parseFloat(document.getElementById('amount').value);
            
            // Validate inputs
            if (!easypaisa.validateMobileNumber(mobileNumber)) {
                alert('Please enter a valid Pakistani mobile number');
                return;
            }
            
            if (!easypaisa.validateAmount(amount)) {
                alert('Please enter a valid amount (1 - 1,000,000 PKR)');
                return;
            }
            
            // Show loading state
            const submitBtn = document.getElementById('submit-btn');
            const originalText = submitBtn.textContent;
            submitBtn.textContent = 'Processing...';
            submitBtn.disabled = true;
            
            // Initiate payment
            easypaisa.initiatePayment(
                mobileNumber,
                amount,
                function(response) {
                    // Success callback
                    console.log('Payment initiated successfully:', response);
                    // You can show a success message or redirect
                },
                function(error) {
                    // Error callback
                    console.error('Payment failed:', error);
                    alert('Payment failed: ' + error);
                    
                    // Reset button state
                    submitBtn.textContent = originalText;
                    submitBtn.disabled = false;
                }
            );
        });
    }
});

// Export for use in modules
if (typeof module !== 'undefined' && module.exports) {
    module.exports = EasypaisaPayment;
}

