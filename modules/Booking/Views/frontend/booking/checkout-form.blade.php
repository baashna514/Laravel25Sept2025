<form id="form-checkout" method="POST" action="{{ route('booking.doCheckout') }}">
    @csrf
    <div class="form-section">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>{{ __('First Name') }} <span class="required">*</span></label>
                    <input type="text" placeholder="{{ __('First Name') }}" class="form-control"
                        value="{{ $user->billing_first_name ?? '' }}" name="first_name">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>{{ __('Last Name') }} <span class="required">*</span></label>
                    <input type="text" placeholder="{{ __('Last Name') }}" class="form-control"
                        value="{{ $user->billing_last_name ?? '' }}" name="last_name">
                </div>
            </div>
            <div class="col-md-6 field-email">
                <div class="form-group">
                    <label>{{ __('Email') }} <span class="required">*</span></label>
                    <input type="email" placeholder="{{ __('email@domain.com') }}" class="form-control"
                        value="{{ $user->email ?? '' }}" name="email">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>{{ __('Phone') }} <span class="required">*</span></label>
                    <input type="text" placeholder="{{ __('Your Phone') }}" class="form-control"
                        value="{{ $user->billing_phone ?? '' }}" name="phone">
                </div>
            </div>
            <div class="col-md-6 field-address-line-1">
                <div class="form-group">
                    <label>{{ __('Address line 1') }} </label>
                    <input type="text" placeholder="{{ __('Address line 1') }}" class="form-control"
                        value="{{ $user->billing_address ?? '' }}" name="address_line_1">
                </div>
            </div>
            <div class="col-md-6 field-address-line-2">
                <div class="form-group">
                    <label>{{ __('Address line 2') }} </label>
                    <input type="text" placeholder="{{ __('Address line 2') }}" class="form-control"
                        value="{{ $user->billing_address2 ?? '' }}" name="address_line_2">
                </div>
            </div>
            <div class="col-md-6 field-city">
                <div class="form-group">
                    <label>{{ __('City') }} </label>
                    <input type="text" class="form-control" value="{{ $user->billing_city ?? '' }}" name="city"
                        placeholder="{{ __('Your City') }}">
                </div>
            </div>
            <div class="col-md-6 field-state">
                <div class="form-group">
                    <label>{{ __('State/Province/Region') }} </label>
                    <input type="text" class="form-control" value="{{ $user->billing_state ?? '' }}" name="state"
                        placeholder="{{ __('State/Province/Region') }}">
                </div>
            </div>
            <div class="col-md-6 field-zip-code">
                <div class="form-group">
                    <label>{{ __('ZIP code/Postal code') }} </label>
                    <input type="text" class="form-control" value="{{ $user->billing_zip_code ?? '' }}"
                        name="zip_code" placeholder="{{ __('ZIP code/Postal code') }}">
                </div>
            </div>
            <div class="col-md-6 field-country">
                <div class="form-group">
                    <label>{{ __('Country') }} <span class="required">*</span> </label>
                    <select name="country" class="form-control">
                        <option value="">{{ __('-- Select --') }}</option>
                        @foreach (get_country_lists() as $id => $name)
                            <option @if (($user->billing_country ?? '') == $id) selected @endif value="{{ $id }}">
                                {{ $name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>
    <div class="form-group">
        <label>{{ __('Select Payment Method') }}</label><br>
        <div class="payment-methods">
            <label class="payment-method-option">
                <input type="radio" name="payment_gateway" value="stripe" checked>
                <div class="payment-method-content">
                    <svg class="payment-icon" viewBox="0 0 120 24" width="140" height="28">
                        <text x="0" y="16" font-family="Arial, sans-serif" font-size="16" font-weight="bold" fill="#635BFF">Stripe</text>
                    </svg>
                </div>
            </label>
            <label class="payment-method-option">
                <input type="radio" name="payment_gateway" value="easypaisa">
                <div class="payment-method-content">
                    <svg class="payment-icon" viewBox="0 0 120 24" width="140" height="28">
                        <!-- EasyPaisa stylized 'e' icon -->
                        <g>
                            <!-- Upper black part of 'e' (incomplete oval) -->
                            <path fill="#000000" d="M2 4c0-1.1.9-2 2-2h8c1.1 0 2 .9 2 2v3c0 .6-.4 1-1 1H5c-.6 0-1-.4-1-1V4z"/>
                            <!-- Lower green curved part of 'e' -->
                            <path fill="#00A651" d="M2 7c0 .6.4 1 1 1h6c.6 0 1-.4 1-1s-.4-1-1-1H3c-.6 0-1 .4-1 1z"/>
                            <!-- Bottom curved part -->
                            <path fill="#00A651" d="M2 9c0 .6.4 1 1 1h4c.6 0 1-.4 1-1s-.4-1-1-1H3c-.6 0-1 .4-1 1z"/>
                        </g>
                        <!-- EasyPaisa text -->
                        <text x="18" y="16" font-family="Arial, sans-serif" font-size="14" font-weight="normal" fill="#000000">Easypaisa</text>
                    </svg>
                </div>
            </label>
            <label class="payment-method-option">
                <input type="radio" name="payment_gateway" value="paypal">
                <div class="payment-method-content">
                    <svg class="payment-icon" viewBox="0 0 120 24" width="140" height="28">
                        <text x="0" y="16" font-family="Arial, sans-serif" font-size="16" font-weight="bold" fill="#5fceff">Paypal</text>
                    </svg>
                </div>
            </label>
        </div>
    </div>
    <div id="payment-stripe" style="display: block;">
        @include ($service->checkout_form_payment_file ?? 'Booking::frontend/booking/checkout-payment')
    </div>

    <div id="payment-easypaisa" style="display: none;">
        @include ($service->checkout_form_payment_file_easypaisa ?? 'Booking::frontend/booking/checkouteasypaisa-payment')
    </div>
    @php
        $term_conditions = setting_item('booking_term_conditions');
    @endphp

    <div class="form-group">
        <label class="term-conditions-checkbox">
            <input type="checkbox" name="term_conditions"> {{ __('I have read and accept the') }} <a target="_blank"
                href="{{ get_page_url($term_conditions) }}">{{ __('terms and conditions') }}</a>
        </label>
    </div>
    @if (setting_item('booking_enable_recaptcha'))
        <div class="form-group">
            {{ recaptcha_field('booking') }}
        </div>
    @endif
    <div class="html_before_actions"></div>

    <p class="alert-text mt10" v-show=" message.content" v-html="message.content"
        :class="{ 'danger': !message.type, 'success': message.type }"></p>

    <div class="form-actions">
        <button type="submit" class="btn btn-danger">
            {{ __('Submit') }}
            <i class="fa fa-spin fa-spinner" v-show="onSubmit"></i>
        </button>
    </div>
</form>

<!-- Processing Overlay -->
<div id="processingOverlay" style="display:none;">
    <div class="loader-box">
        <div class="spinner"></div>

        <h3>Payment Request Sent</h3>

        <p class="primary-text">
            Please check your mobile phone and confirm the payment.
        </p>

        <p class="secondary-text">
            Do not close or refresh this page until the payment is completed.
        </p>
    </div>
</div>

<div id="processingPayPalOverlay" style="display:none;">
    <div class="loader-box">
        <div class="spinner"></div>

        <h3>Payment Request Submitted</h3>

        <p class="primary-text">
            Please wait! We are redirecting you to PayPal Checkout Page.
        </p>

        <p class="secondary-text">
            Do not close or refresh this page until the page is redirected to PayPal Checkout Page.
        </p>
    </div>
</div>


<style>
.payment-methods {
    display: flex;
    gap: 15px;
    margin-top: 10px;
}

.payment-method-option {
    display: flex;
    align-items: center;
    cursor: pointer;
    border: 2px solid #e0e0e0;
    border-radius: 8px;
    padding: 12px 16px;
    transition: all 0.3s ease;
    background: #fff;
    flex: 1;
    max-width: 200px;
}

.payment-method-option:hover {
    border-color: #007bff;
    box-shadow: 0 2px 8px rgba(0, 123, 255, 0.15);
}

.payment-method-option input[type="radio"] {
    display: none;
}

.payment-method-option input[type="radio"]:checked + .payment-method-content {
    color: #007bff;
}

.payment-method-option input[type="radio"]:checked {
    border-color: #007bff;
    background: #f8f9ff;
}

.payment-method-content {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
}

.payment-icon {
    flex-shrink: 0;
}

@media (max-width: 768px) {
    .payment-methods {
        flex-direction: column;
        gap: 10px;
    }

    .payment-method-option {
        max-width: none;
    }
}



#processingOverlay {
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.65);
    z-index: 9999;
    display: flex;
    align-items: center;
    justify-content: center;
}

.loader-box {
    background: #ffffff;
    padding: 30px 35px;
    border-radius: 10px;
    text-align: center;
    width: 360px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.2);
}

.loader-box h3 {
    margin-top: 15px;
    font-size: 20px;
    color: #1a7f37;
    font-weight: 600;
}

.primary-text {
    margin-top: 10px;
    font-size: 15px;
    font-weight: 500;
    color: #333;
}

.secondary-text {
    margin-top: 8px;
    font-size: 13px;
    color: #666;
}

.spinner {
    width: 46px;
    height: 46px;
    border: 4px solid #e5e5e5;
    border-top: 4px solid #1a7f37;
    border-radius: 50%;
    animation: spin 1s linear infinite;
    margin: 0 auto;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

</style>

<script>
   document.addEventListener('DOMContentLoaded', function() {
    // Get the current total amount from the page
    const originalTotal = {{ Cart::total() }};

    // Calculate EasyPaisa total from cart items
    let easypaisaTotal = 0;
    @foreach(Cart::content() as $cartItem)
        @if($cartItem->model && isset($cartItem->model->easypaisa_price))
            easypaisaTotal += {{ $cartItem->model->easypaisa_price }} * {{ $cartItem->qty }};
        @endif
    @endforeach

    // Show/hide payment method forms and update total
    function togglePaymentForms() {
        const selectedGateway = document.querySelector('input[name="payment_gateway"]:checked').value;
        const stripeDiv = document.getElementById('payment-stripe');
        const easypaisaDiv = document.getElementById('payment-easypaisa');
        const paypalDiv = document.getElementById('payment-ayal');

        if (selectedGateway === 'stripe') {
            stripeDiv.style.display = 'block';
            easypaisaDiv.style.display = 'none';
            updateTotalDisplay(originalTotal, 'USD');
        } else if (selectedGateway === 'easypaisa') {
            stripeDiv.style.display = 'none';
            easypaisaDiv.style.display = 'block';
            updateTotalDisplay(easypaisaTotal, 'PKR');
        }else if (selectedGateway === 'paypal') {
            stripeDiv.style.display = 'none';
            easypaisaDiv.style.display = 'none';
            updateTotalDisplay(originalTotal, 'USD');
        }
    }

    // Function to update the total display in the sidebar
    function updateTotalDisplay(amount, currency) {
        const totalElement = document.getElementById('checkout-total');
        const currencyNote = document.getElementById('currency-note');

        // Update cart item prices
        const cartItemPrices = document.querySelectorAll('.cart-item-price');
        cartItemPrices.forEach(priceElement => {
            if (currency === 'PKR') {
                priceElement.textContent = priceElement.getAttribute('data-pkr-price');
            } else {
                priceElement.textContent = priceElement.getAttribute('data-usd-price');
            }
        });

        if (totalElement) {
            if (currency === 'PKR') {
                // Show only EasyPaisa amount
                totalElement.textContent = 'PKR ' + Math.round(amount).toLocaleString();
                if (currencyNote) currencyNote.style.display = 'block';
            } else {
                // Show only USD amount
                totalElement.textContent = '$' + amount.toFixed(2);
                if (currencyNote) currencyNote.style.display = 'none';
            }
        }
    }

    // Check if user was redirected back from EasyPaisa
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('payment_cancelled') === '1') {
        // Show error message if payment was cancelled
        const alertDiv = document.createElement('div');
        alertDiv.className = 'alert alert-warning';
        alertDiv.innerHTML = 'Payment was cancelled. You can try again or choose a different payment method.';
        document.querySelector('.form-section').insertBefore(alertDiv, document.querySelector('.form-section').firstChild);
    }

    // Initial call on page load
    togglePaymentForms();

    // Add event listeners to radio buttons
    document.querySelectorAll('input[name="payment_gateway"]').forEach(radio => {
        radio.addEventListener('change', togglePaymentForms);
    });

    // Handle form submission
    const form = document.getElementById('form-checkout');
    form.addEventListener('submit', function(e) {
        const selectedGateway = document.querySelector('input[name="payment_gateway"]:checked').value;

        // Remove any existing hidden payment_gateway inputs to avoid duplicates
        document.querySelectorAll('input[name="payment_gateway"][type="hidden"]').forEach(input => {
            input.remove();
        });

        // Add the selected payment gateway as a hidden input
        const gatewayInput = document.createElement('input');
        gatewayInput.type = 'hidden';
        gatewayInput.name = 'payment_gateway';
        gatewayInput.value = selectedGateway;
        form.appendChild(gatewayInput);

        if (selectedGateway === 'easypaisa') {
            e.preventDefault();
            handleEasyPaisaPayment();
        }
        else if (selectedGateway === 'paypal') {
            handlePaypalPayment();
        }
    });

       function handleEasyPaisaPayment() {
           const phoneInput = document.querySelector('input[name="phone"]');
           const easypaisa_phone_input = document.querySelector('input[name="easypaisa_phone"]');
           const easypaisa_email_input = document.querySelector('input[name="easypaisa_email"]');

           const phoneNumber = phoneInput ? phoneInput.value : '';
           const easypaisa_phone = easypaisa_phone_input ? easypaisa_phone_input.value : '';
           const easypaisa_email = easypaisa_email_input ? easypaisa_email_input.value : '';

           if (!phoneNumber) {
               alert('Please enter your phone number');
               return;
           }

           const totalText = document.getElementById('checkout-total').innerText;
            // Remove currency and spaces, then convert to number
           const totalAmount = parseFloat(
               totalText.replace('PKR', '').trim()
           );

           {{--const totalAmount = {{ Cart::total() }};--}}
           if (!totalAmount || totalAmount <= 0) {
               alert('Invalid amount for payment');
               return;
           }

           const submitBtn = document.querySelector('button[type="submit"]');
           submitBtn.disabled = true;

           // Show overlay immediately
           document.getElementById('processingOverlay').style.display = 'flex';


           fetch('/api/easypaisa/pay', {
               method: 'POST',
               headers: {
                   'Content-Type': 'application/json',
                   'X-CSRF-TOKEN': document
                       .querySelector('meta[name="csrf-token"]')
                       .getAttribute('content'),
                   'Accept': 'application/json'
               },
               body: JSON.stringify({
                   mobile_number: phoneNumber,
                   amount: totalAmount,
                   easypaisa_phone: easypaisa_phone,
                   easypaisa_email: easypaisa_email
               })
           })
               .then(response => response.json())
               .then(data => {
                   if (data.success) {
                       saveBookingData();
                   } else {
                       document.getElementById('processingOverlay').style.display = 'none';
                       submitBtn.disabled = false;

                       alert('Payment failed. Please try again or choose a different payment method.');
                       window.location.href = "{{ url('/') }}";
                   }
               })
               .catch(error => {
                   document.getElementById('processingOverlay').style.display = 'none';
                   submitBtn.disabled = false;
                   alert('Payment failed. Please try again.');
               });
       }

       function handlePaypalPayment() {

           // Show overlay immediately
           document.getElementById('processingPayPalOverlay').style.display = 'flex';

           const form = document.getElementById('form-checkout');
           const formData = new FormData(form);
           const dataObj = {};
           formData.forEach((value, key) => {
               dataObj[key] = value;
           });

           // Save booking form data in localStorage
           localStorage.setItem('bookingFormData', JSON.stringify(dataObj));

           fetch('/api/paypal/pay', {
               method: 'POST',
               headers: {
                   'Content-Type': 'application/json',
                   'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                   'Accept': 'application/json'
               },
               body: JSON.stringify({
                   amount: {{ Cart::total() }}
               })
           })
               .then(res => res.json())
               .then(data => {
                   if (data.success && data.approve_url) {
                       window.location.href = data.approve_url;
                   } else {
                       alert('Unable to start PayPal payment');
                   }
               })
               .catch(() => alert('Payment failed'));
       }

       function saveBookingData() {
       const formData = new FormData(form);
       formData.append('payment_gateway', 'easypaisa');

       fetch('{{ route("booking.doCheckout") }}', {
           method: 'POST',
           body: formData,
           headers: {
               'X-CSRF-TOKEN': document
                   .querySelector('meta[name="csrf-token"]')
                   .getAttribute('content')
           }
       })
           .then(response => response.json())
           .then(data => {
               console.log("DATA: " + JSON.stringify(data));
               if (data.success) {
                   window.location.href = data.redirect_url;
               } else {
                   console.error('Failed to save booking:', data.message);
                   alert(data.message || 'Booking failed');
               }
           })
           .catch(error => {
               console.error('Error saving booking:', error);
               alert('Something went wrong. Please try again.');
           });
   }

       function submitToEasyPaisa(authToken) {
        // Create a form to submit to EasyPaisa
        const easypaisaForm = document.createElement('form');
        easypaisaForm.method = 'POST';
        easypaisaForm.action = 'https://easypay.easypaisa.com.pk/easypay/Confirm.jsf';
        easypaisaForm.target = '_blank';

        // Add auth token and postback URL
        const authInput = document.createElement('input');
        authInput.type = 'hidden';
        authInput.name = 'auth_token';
        authInput.value = authToken;
        easypaisaForm.appendChild(authInput);

        const postbackInput = document.createElement('input');
        postbackInput.type = 'hidden';
        postbackInput.name = 'postBackURL';
        postbackInput.value = '{{ route("booking.confirmPayment", ["gateway" => "easypaisa"]) }}';
        easypaisaForm.appendChild(postbackInput);

        document.body.appendChild(easypaisaForm);
        easypaisaForm.submit();
        document.body.removeChild(easypaisaForm);
    }
});
</script>
