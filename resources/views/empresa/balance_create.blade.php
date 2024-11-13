<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Crear Balance de Apertura para ') . $empresa->nombre }}
        </h2>
    </x-slot>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <!-- Formulario de Balance de Apertura -->
                <form id="balanceForm">
                    @csrf
                    <input type="hidden" name="empresa_id" value="{{ $empresa->id }}">

                    <h3 class="text-xl font-bold mb-4">Balance de Apertura</h3>

                    @php
                        $codigoTipoCuenta = [
                            'activo_corriente' => 1,
                            'activo_no_corriente' => 2,
                            'pasivo_corriente' => 3,
                            'pasivo_no_corriente' => 4,
                            'patrimonio' => 5
                        ];

                        $contadores = [
                            'activo_corriente' => 1,
                            'activo_no_corriente' => 1,
                            'pasivo_corriente' => 1,
                            'pasivo_no_corriente' => 1,
                            'patrimonio' => 1
                        ];
                    @endphp

                    <!-- Activos Corrientes -->
                    <h4 class="text-lg font-semibold mt-6 mb-2">1 Activos Corrientes</h4>
                    @foreach($cuentas as $cuenta)
                        @if($cuenta->tipo === 'activo_corriente')
                            @php
                                $codigo = $codigoTipoCuenta['activo_corriente'] . '.' . $contadores['activo_corriente'];
                                $contadores['activo_corriente']++;
                            @endphp
                            <div class="mb-4">
                                <label for="cuenta_{{ $cuenta->id }}" class="block text-gray-700">{{ $codigo }} {{ $cuenta->nombre }}</label>
                                <div class="flex">
                                    <input type="hidden" name="detalles[{{ $cuenta->id }}][cuenta_id]" value="{{ $cuenta->id }}">
                                    <input type="number" step="0.01" name="detalles[{{ $cuenta->id }}][debe]" placeholder="Debe" class="shadow appearance-none border rounded w-1/2 py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mr-2">
                                    <input type="number" step="0.01" name="detalles[{{ $cuenta->id }}][haber]" placeholder="Haber" class="shadow appearance-none border rounded w-1/2 py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                </div>
                            </div>
                        @endif
                    @endforeach

                    <!-- Activos No Corrientes -->
                    <h4 class="text-lg font-semibold mt-6 mb-2">2 Activos No Corrientes</h4>
                    @foreach($cuentas as $cuenta)
                        @if($cuenta->tipo === 'activo_no_corriente')
                            @php
                                $codigo = $codigoTipoCuenta['activo_no_corriente'] . '.' . $contadores['activo_no_corriente'];
                                $contadores['activo_no_corriente']++;
                            @endphp
                            <div class="mb-4">
                                <label for="cuenta_{{ $cuenta->id }}" class="block text-gray-700">{{ $codigo }} {{ $cuenta->nombre }}</label>
                                <div class="flex">
                                    <input type="hidden" name="detalles[{{ $cuenta->id }}][cuenta_id]" value="{{ $cuenta->id }}">
                                    <input type="number" step="0.01" name="detalles[{{ $cuenta->id }}][debe]" placeholder="Debe" class="shadow appearance-none border rounded w-1/2 py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mr-2">
                                    <input type="number" step="0.01" name="detalles[{{ $cuenta->id }}][haber]" placeholder="Haber" class="shadow appearance-none border rounded w-1/2 py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                </div>
                            </div>
                        @endif
                    @endforeach

                    <!-- Pasivos Corrientes -->
                    <h4 class="text-lg font-semibold mt-6 mb-2">3 Pasivos Corrientes</h4>
                    @foreach($cuentas as $cuenta)
                        @if($cuenta->tipo === 'pasivo_corriente')
                            @php
                                $codigo = $codigoTipoCuenta['pasivo_corriente'] . '.' . $contadores['pasivo_corriente'];
                                $contadores['pasivo_corriente']++;
                            @endphp
                            <div class="mb-4">
                                <label for="cuenta_{{ $cuenta->id }}" class="block text-gray-700">{{ $codigo }} {{ $cuenta->nombre }}</label>
                                <div class="flex">
                                    <input type="hidden" name="detalles[{{ $cuenta->id }}][cuenta_id]" value="{{ $cuenta->id }}">
                                    <input type="number" step="0.01" name="detalles[{{ $cuenta->id }}][debe]" placeholder="Debe" class="shadow appearance-none border rounded w-1/2 py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mr-2">
                                    <input type="number" step="0.01" name="detalles[{{ $cuenta->id }}][haber]" placeholder="Haber" class="shadow appearance-none border rounded w-1/2 py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                </div>
                            </div>
                        @endif
                    @endforeach

                    <!-- Pasivos No Corrientes -->
                    <h4 class="text-lg font-semibold mt-6 mb-2">4 Pasivos No Corrientes</h4>
                    @foreach($cuentas as $cuenta)
                        @if($cuenta->tipo === 'pasivo_no_corriente')
                            @php
                                $codigo = $codigoTipoCuenta['pasivo_no_corriente'] . '.' . $contadores['pasivo_no_corriente'];
                                $contadores['pasivo_no_corriente']++;
                            @endphp
                            <div class="mb-4">
                                <label for="cuenta_{{ $cuenta->id }}" class="block text-gray-700">{{ $codigo }} {{ $cuenta->nombre }}</label>
                                <div class="flex">
                                    <input type="hidden" name="detalles[{{ $cuenta->id }}][cuenta_id]" value="{{ $cuenta->id }}">
                                    <input type="number" step="0.01" name="detalles[{{ $cuenta->id }}][debe]" placeholder="Debe" class="shadow appearance-none border rounded w-1/2 py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mr-2">
                                    <input type="number" step="0.01" name="detalles[{{ $cuenta->id }}][haber]" placeholder="Haber" class="shadow appearance-none border rounded w-1/2 py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                </div>
                            </div>
                        @endif
                    @endforeach

                    <!-- Patrimonio -->
                    <h4 class="text-lg font-semibold mt-6 mb-2">5 Patrimonio</h4>
                    @foreach($cuentas as $cuenta)
                        @if($cuenta->tipo === 'patrimonio')
                            @php
                                $codigo = $codigoTipoCuenta['patrimonio'] . '.' . $contadores['patrimonio'];
                                $contadores['patrimonio']++;
                            @endphp
                            <div class="mb-4">
                                <label for="cuenta_{{ $cuenta->id }}" class="block text-gray-700">{{ $codigo }} {{ $cuenta->nombre }}</label>
                                <div class="flex">
                                    <input type="hidden" name="detalles[{{ $cuenta->id }}][cuenta_id]" value="{{ $cuenta->id }}">
                                    <input type="number" step="0.01" name="detalles[{{ $cuenta->id }}][debe]" placeholder="Debe" class="shadow appearance-none border rounded w-1/2 py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mr-2">
                                    <input type="number" step="0.01" name="detalles[{{ $cuenta->id }}][haber]" placeholder="Haber" class="shadow appearance-none border rounded w-1/2 py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                </div>
                            </div>
                        @endif
                    @endforeach

                    <!-- Botón de enviar -->
                    <div class="flex items-center justify-center mt-4">
                        <x-button type="button" id="submitBtn" style="background-color: #3490dc; color: white;">
                            Guardar Balance de Apertura
                        </x-button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Script para manejar el envío de datos -->
    <script>
        document.getElementById('submitBtn').addEventListener('click', function (event) {
            event.preventDefault();

            const formData = new FormData(document.getElementById('balanceForm'));

            // Crear un objeto JSON para enviar los datos
            const data = {};
            formData.forEach((value, key) => {
                if (key.includes('detalles')) {
                    const match = key.match(/detalles\[(\d+)\]\[(\w+)\]/); // Extraer el ID de la cuenta y el campo (debe/haber)
                    if (match) {
                        const cuentaId = match[1];
                        const campo = match[2];
                        if (!data['detalles']) data['detalles'] = {};
                        if (!data['detalles'][cuentaId]) data['detalles'][cuentaId] = {};
                        data['detalles'][cuentaId][campo] = value;
                    }
                } else {
                    data[key] = value;
                }
            });

            fetch('{{ route("balance.store") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        title: '¡Éxito!',
                        text: 'El balance de apertura se ha guardado correctamente.',
                        icon: 'success',
                        confirmButtonText: 'Aceptar'
                    }).then(() => {
                        window.location.href = '{{ route("balance.show", ["empresa_id" => $empresa->id]) }}';
                    });
                } else {
                    Swal.fire('Error', 'No se pudo guardar el balance de apertura. ' + data.message, 'error');
                }
            })
            .catch(error => {
                Swal.fire('Error', 'Ocurrió un error al guardar el balance de apertura. Por favor, inténtalo de nuevo.', 'error');
            });
        });
    </script>
</x-app-layout>
