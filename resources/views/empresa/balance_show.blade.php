<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight text-center">
            {{ __('Balance de Apertura de ') . $empresa->nombre }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <h3 class="text-xl font-bold mb-4 text-center">Balance de Apertura</h3>

                <!-- Botones para seleccionar la moneda -->
                <div class="text-center mb-4">
                    <button id="convertirDolares" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Ver en Dólares</button>
                    <button id="convertirEuros" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Ver en Euros</button>
                    <button id="convertirReales" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Ver en Reales</button>
                </div>

                @if($balance)
                    <!-- Contenedor para centrar la tabla -->
                    <div class="flex justify-center">
                        <!-- Tabla de balance de apertura con ancho completo -->
                        <table id="balanceTable" class="min-w-full bg-white border w-full">
                            <thead>
                                <tr>
                                    <th class="py-2 px-4 border text-left">Código</th>
                                    <th class="py-2 px-4 border text-left">Cuenta</th>
                                    <th class="py-2 px-4 border text-left">Debe (Bs)</th>
                                    <th class="py-2 px-4 border text-left">Haber (Bs)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $codigoTipoCuenta = [
                                        'activo_corriente' => 1,
                                        'activo_no_corriente' => 2,
                                        'pasivo_corriente' => 3,
                                        'pasivo_no_corriente' => 4,
                                        'patrimonio' => 5
                                    ];

                                    $nombresTipoCuenta = [
                                        'activo_corriente' => 'Activos Corrientes',
                                        'activo_no_corriente' => 'Activos No Corrientes',
                                        'pasivo_corriente' => 'Pasivos Corrientes',
                                        'pasivo_no_corriente' => 'Pasivos No Corrientes',
                                        'patrimonio' => 'Patrimonio'
                                    ];

                                    $contadores = [
                                        'activo_corriente' => 1,
                                        'activo_no_corriente' => 1,
                                        'pasivo_corriente' => 1,
                                        'pasivo_no_corriente' => 1,
                                        'patrimonio' => 1
                                    ];

                                    $tipoCuentaActual = null;
                                @endphp

                                @foreach($balance->detalles as $detalle)
                                    @php
                                        $tipoCuenta = $detalle->cuenta->tipo;
                                        $codigo = $codigoTipoCuenta[$tipoCuenta] . '.' . $contadores[$tipoCuenta];

                                        // Verificar si estamos cambiando de tipo de cuenta (ej. de Activos Corrientes a Pasivos Corrientes)
                                        if ($tipoCuenta !== $tipoCuentaActual) {
                                            $tipoCuentaActual = $tipoCuenta;
                                            echo "<tr><td colspan='4' class='font-bold py-2 px-4 border'>{$codigoTipoCuenta[$tipoCuenta]}. {$nombresTipoCuenta[$tipoCuenta]}</td></tr>";
                                        }

                                        $contadores[$tipoCuenta]++;
                                    @endphp
                                    <tr>
                                        <td class="py-2 px-4 border text-left">{{ $codigo }}</td>
                                        <td class="py-2 px-4 border text-left">{{ $detalle->cuenta->nombre }}</td>
                                        <td class="py-2 px-4 border text-left debe-bs" data-original="{{ $detalle->debe }}">{{ number_format($detalle->debe, 2) }}</td>
                                        <td class="py-2 px-4 border text-left haber-bs" data-original="{{ $detalle->haber }}">{{ number_format($detalle->haber, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Botón para eliminar el balance -->
                    <div class="text-center mt-4">
                        <button id="eliminarBalance" style="background-color: #dc2626; color: white; padding: 0.5rem 1rem; border-radius: 0.5rem; margin-right: 0.5rem;">
                            Eliminar Balance de Apertura
                        </button>
                    </div>
                @else
                    <p class="text-center">No hay balance de apertura registrado para esta empresa.</p>
                @endif


                <div class="text-center mt-4">
                <a href="{{ route('asientos.create', ['empresa_id' => $empresa->id]) }}" 
                    style="background-color: #3490dc; color: white; padding: 0.5rem 1rem; border-radius: 0.5rem; text-decoration: none;">
                        Crear Asiento Contable
                </a>
            </div>

            
        </div>
</div>

    </div>

    <script>
        // Tasa de conversión para las monedas
        const tasaDolares = 6.96;
        const tasaEuros = 7.50;
        const tasaReales = 1.40;

        // Función para convertir los valores desde el valor original en Bs
        function convertirMoneda(tasa, moneda) {
            const debeBsCells = document.querySelectorAll('.debe-bs');
            const haberBsCells = document.querySelectorAll('.haber-bs');

            // Convertir los valores de "Debe"
            debeBsCells.forEach(function(cell) {
                const valorOriginal = parseFloat(cell.getAttribute('data-original'));
                const valorConvertido = (valorOriginal / tasa).toFixed(2);
                cell.innerText = `${valorConvertido} ${moneda}`;
            });

            // Convertir los valores de "Haber"
            haberBsCells.forEach(function(cell) {
                const valorOriginal = parseFloat(cell.getAttribute('data-original'));
                const valorConvertido = (valorOriginal / tasa).toFixed(2);
                cell.innerText = `${valorConvertido} ${moneda}`;
            });
        }

        // Eventos para cada botón de conversión
        document.getElementById('convertirDolares').addEventListener('click', function() {
            convertirMoneda(tasaDolares, 'USD');
        });

        document.getElementById('convertirEuros').addEventListener('click', function() {
            convertirMoneda(tasaEuros, 'EUR');
        });

        document.getElementById('convertirReales').addEventListener('click', function() {
            convertirMoneda(tasaReales, 'BRL');
        });

        // Funcionalidad para eliminar el balance de apertura
        document.getElementById('eliminarBalance').addEventListener('click', function() {
            if (confirm('¿Estás seguro de eliminar este balance de apertura?')) {
                fetch(`/balance/{{ $balance->id }}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Balance eliminado correctamente');
                        // Redirigir correctamente con el ID de la empresa
                        window.location.href = "{{ route('empresa.show', ['empresa' => $empresa->id]) }}";
                    } else {
                        alert('Error al eliminar el balance');
                    }
                })
                .catch(error => console.error('Error:', error));
            }
        });
    </script>
</x-app-layout>
