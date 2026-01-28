<div id="jazzcash-payment-container" style="display:none; margin-top: 15px;">
    <h5>{{ __('Jazzcash Payment Details') }}</h5>

    {{-- EasyPaisa usually doesn’t require card input like Stripe, but you can add fields if needed --}}

    <div class="form-group">
        <label for="jazzcash_phone">{{ __('Phone Number (registered with Jazzcash)') }} <span class="required">*</span></label>
        <input type="text" id="easypaisa_phone" name="jazzcash_phone" class="form-control" placeholder="{{ __('Enter your Jazzcash registered phone') }}" value="{{ old('jazzcash_phone') }}">
        <label for="jazzcash_cnic">{{ __('CNIC (registered with Jazzcash)') }} <span class="required">*</span></label>
        <input type="text" id="jazzcash_cnic" name="jazzcash_cnic" class="form-control" placeholder="{{ __('Enter your Jazzcash registered CNIC') }}" value="{{ old('jazzcash_cnic') }}">
    </div>

    {{-- You can add other EasyPaisa-specific fields here if needed --}}
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        function toggleEasyPaisaFields() {
            const selected = document.querySelector('input[name="payment_gateway"]:checked').value;
            const container = document.getElementById('jazzcash-payment-container');

            const phone = document.getElementById('jazzcash_phone');
            const cnic = document.getElementById('jazzcash_cnic');

            if (selected === 'jazzcash') {
                container.style.display = 'block';
                phone.required = true;
                cnic.required = true;
            } else {
                container.style.display = 'none';
                phone.required = false;
                cnic.required = false;
            }
        }

        toggleEasyPaisaFields();

        document.querySelectorAll('input[name="payment_gateway"]').forEach(radio => {
            radio.addEventListener('change', toggleEasyPaisaFields);
        });
    });
</script>

