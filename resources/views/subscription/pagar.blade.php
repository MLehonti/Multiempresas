<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pago con Tarjeta - NimbusBooks</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://js.stripe.com/v3/"></script>
</head>

<body class="bg-gray-100">

    <!-- Formulario de Pago con Tarjeta -->
    <div class="min-h-screen flex items-center justify-center">
        <div class="bg-white p-8 rounded-lg shadow-md max-w-md w-full">
            <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">Pagar con Tarjeta</h2>
            <form id="payment-form">
                <div class="mb-4">
                    <label for="amount" class="block text-gray-700">Monto (USD)</label>
                    <input type="number" id="amount" name="amount" step="0.01" required
                        class="mt-1 w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600">
                </div>
                <div class="mb-4">
                    <label for="description" class="block text-gray-700">Descripción</label>
                    <input type="text" id="description" name="description" required
                        class="mt-1 w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600">
                </div>
                <div id="card-element" class="p-4 border rounded-lg mt-4 mb-6 bg-gray-50">
                    <!-- Stripe Card Element se mostrará aquí -->
                </div>
                <button id="submit-button" class="w-full bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700">
                    Proceder al Pago
                </button>
                <p id="payment-message" class="hidden text-center text-red-600 mt-4"></p>
            </form>
        </div>
    </div>

    <script>
        const stripe = Stripe("{{ env('STRIPE_KEY') }}");
        const elements = stripe.elements();
        const cardElement = elements.create('card');
        cardElement.mount('#card-element');

        const paymentForm = document.getElementById('payment-form');
        const paymentMessage = document.getElementById('payment-message');

        paymentForm.addEventListener('submit', async (event) => {
            event.preventDefault();

            const amount = document.getElementById('amount').value;
            const description = document.getElementById('description').value;

            const { paymentMethod, error } = await stripe.createPaymentMethod({
                type: 'card',
                card: cardElement,
                billing_details: {
                    name: 'Customer',
                },
            });

            if (error) {
                paymentMessage.textContent = error.message;
                paymentMessage.classList.remove('hidden');
            } else {
                // Enviar detalles al backend para crear el PaymentIntent
                fetch('/create-payment', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': "{{ csrf_token() }}",
                    },
                    body: JSON.stringify({
                        payment_method: paymentMethod.id,
                        amount: amount,
                        description: description
                    })
                }).then(res => res.json()).then(response => {
                    if (response.error) {
                        paymentMessage.textContent = response.error;
                        paymentMessage.classList.remove('hidden');
                    } else {
                        window.location.href = response.url;
                    }
                });
            }
        });
    </script>

</body>

</html>
