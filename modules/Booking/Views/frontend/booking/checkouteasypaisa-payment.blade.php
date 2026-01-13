<div id="easypaisa-payment-container" style="display:none; margin-top: 15px;">
    <h5>{{ __('EasyPaisa Payment Details') }}</h5>

    {{-- EasyPaisa usually doesn’t require card input like Stripe, but you can add fields if needed --}}

    <div class="form-group">
        <label for="easypaisa_phone">{{ __('Phone Number (registered with EasyPaisa)') }} <span class="required">*</span></label>
        <input type="text" id="easypaisa_phone" name="easypaisa_phone" class="form-control" placeholder="{{ __('Enter your EasyPaisa registered phone') }}" value="{{ old('easypaisa_phone') }}">
        <label for="easypaisa_email">{{ __('Email (registered with EasyPaisa)') }} <span class="required">*</span></label>
        <input type="text" id="easypaisa_email" name="easypaisa_email" class="form-control" placeholder="{{ __('Enter your EasyPaisa registered email') }}" value="{{ old('easypaisa_email') }}">
    </div>

    {{-- You can add other EasyPaisa-specific fields here if needed --}}
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        function toggleEasyPaisaFields() {
            const selected = document.querySelector('input[name="payment_gateway"]:checked').value;
            const container = document.getElementById('easypaisa-payment-container');

            const phone = document.getElementById('easypaisa_phone');
            const email = document.getElementById('easypaisa_email');

            if (selected === 'easypaisa') {
                container.style.display = 'block';
                phone.required = true;
                email.required = true;
            } else {
                container.style.display = 'none';
                phone.required = false;
                email.required = false;
            }
        }

        toggleEasyPaisaFields();

        document.querySelectorAll('input[name="payment_gateway"]').forEach(radio => {
            radio.addEventListener('change', toggleEasyPaisaFields);
        });
    });
</script>

