<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Pagos Realizados - NimbusBooks</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">

    <div class="min-h-screen flex items-center justify-center py-12">
        <div class="bg-white p-8 rounded-lg shadow-md max-w-3xl w-full">
            <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">Mis Pagos Realizados</h2>

            <table class="w-full table-auto mb-6">
                <thead>
                    <tr class="bg-purple-600 text-white">
                        <th class="py-2 px-4">ID de Pago</th>
                        <th class="py-2 px-4">Descripción</th>
                        <th class="py-2 px-4">Monto (USD)</th>
                        <th class="py-2 px-4">Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($payments as $payment)
                        <tr class="bg-gray-50 text-center">
                            <td class="py-2 px-4">{{ $payment->id }}</td>
                            <td class="py-2 px-4">{{ $payment->description ?? 'N/A' }}</td>
                            <td class="py-2 px-4">{{ number_format($payment->amount / 100, 2) }}</td>
                            <td class="py-2 px-4">
                                <span class="inline-block px-2 py-1 rounded-lg text-sm
                                    {{ $payment->status == 'succeeded' ? 'bg-green-200 text-green-800' : 'bg-red-200 text-red-800' }}">
                                    {{ ucfirst($payment->status) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-4 text-gray-600">No se encontraron pagos.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <!-- Botón para volver a la vista de bienvenida -->
            <div class="text-center">
                <a href="/" class="bg-purple-600 text-white px-6 py-2 rounded-lg hover:bg-purple-700">
                    Volver al Inicio
                </a>
            </div>
        </div>
    </div>

</body>

</html>
