<!DOCTYPE html>
<html>
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Processing Payment...</title>
</head>
<body>
<p>Processing your payment, please wait...</p>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Get form data from localStorage or query string if needed
        // Assuming you saved the booking form data in localStorage before redirect
        const formData = new FormData();

        // Example: you can store booking data before PayPal redirect in localStorage
        const bookingData = JSON.parse(localStorage.getItem('bookingFormData') || '{}');
        for (const key in bookingData) {
            formData.append(key, bookingData[key]);
        }

        // Set PayPal as the gateway
        formData.append('payment_gateway', 'paypal');

        fetch('{{ route("booking.doCheckout") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = data.redirect_url;
                } else {
                    alert(data.message || 'Booking failed');
                }
            })
            .catch(err => {
                console.error(err);
                alert('Something went wrong. Please try again.');
            });
    });
</script>
</body>
</html>
