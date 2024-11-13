<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Registrar Asiento Contable para ') . $empresa->nombre }}
        </h2>
    </x-slot>

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

        $codigoCuentas = [];
        foreach ($cuentas as $cuenta) {
            $codigoBase = $codigoTipoCuenta[$cuenta->tipo];
            $codigoCuenta = "{$codigoBase}." . $contadores[$cuenta->tipo];
            $codigoCuentas[$cuenta->id] = $codigoCuenta;
            $contadores[$cuenta->tipo]++;
        }
    @endphp

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <form action="{{ route('asientos.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="empresa_id" value="{{ $empresa->id }}">

                    <div class="form-group">
                        <label for="codigo_origen" class="block text-gray-700 font-bold mb-2">Código de Cuenta Origen</label>
                        <input type="text" id="codigo_origen" name="codigo_origen" class="form-control w-full border rounded px-3 py-2" readonly>
                    </div>

                    <div class="form-group">
                        <label for="cuenta_origen_id" class="block text-gray-700 font-bold mb-2">Cuenta Origen</label>
                        <select name="cuenta_origen_id" id="cuenta_origen_id" class="form-control w-full border rounded px-3 py-2">
                            <option value="">Selecciona una cuenta...</option>
                            @foreach ($cuentas as $cuenta)
                                <option value="{{ $cuenta->id }}" data-codigo="{{ $codigoCuentas[$cuenta->id] }}">
                                    {{ $cuenta->nombre }} ({{ $codigoCuentas[$cuenta->id] }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="codigo_destino" class="block text-gray-700 font-bold mb-2">Código de Cuenta Destino</label>
                        <input type="text" id="codigo_destino" name="codigo_destino" class="form-control w-full border rounded px-3 py-2" readonly>
                    </div>

                    <div class="form-group">
                        <label for="cuenta_destino_id" class="block text-gray-700 font-bold mb-2">Cuenta Destino</label>
                        <select name="cuenta_destino_id" id="cuenta_destino_id" class="form-control w-full border rounded px-3 py-2">
                            <option value="">Selecciona una cuenta...</option>
                            @foreach ($cuentas as $cuenta)
                                <option value="{{ $cuenta->id }}" data-codigo="{{ $codigoCuentas[$cuenta->id] }}">
                                    {{ $cuenta->nombre }} ({{ $codigoCuentas[$cuenta->id] }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="monto" class="block text-gray-700 font-bold mb-2">Monto</label>
                        <input type="number" name="monto" class="form-control w-full border rounded px-3 py-2" step="0.01" required>
                    </div>

                    <div class="form-group">
                        <label for="debe" class="block text-gray-700 font-bold mb-2">Debe</label>
                        <input type="number" name="debe" class="form-control w-full border rounded px-3 py-2" step="0.01">
                    </div>

                    <div class="form-group">
                        <label for="haber" class="block text-gray-700 font-bold mb-2">Haber</label>
                        <input type="number" name="haber" class="form-control w-full border rounded px-3 py-2" step="0.01">
                    </div>

                    <div class="form-group">
                        <label for="descripcion" class="block text-gray-700 font-bold mb-2">Descripción</label>
                        <textarea name="descripcion" class="form-control w-full border rounded px-3 py-2" rows="3"></textarea>
                    </div>

                    <div class="form-group">
                        <label for="fecha" class="block text-gray-700 font-bold mb-2">Fecha</label>
                        <input type="date" name="fecha" class="form-control w-full border rounded px-3 py-2" required>
                    </div>

                    <button type="submit" class="btn btn-primary mt-6 w-full md:w-auto bg-blue-500 text-white font-bold py-2 px-4 rounded">
                        Registrar Asiento
                    </button>
                </form>
            </div>

            <!-- Tabla de Asientos Contables -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 mt-8">
                <h3 class="text-xl font-bold mb-4">Asientos Contables Realizados</h3>
                @if($asientos->isEmpty())
                    <p>No hay asientos contables registrados para esta empresa.</p>
                @else
                    <table class="min-w-full bg-white border">
                        <thead>
                            <tr>
                                <th class="py-2 px-4 border text-left">Fecha</th>
                                <th class="py-2 px-4 border text-left">Código Cuenta Origen</th>
                                <th class="py-2 px-4 border text-left">Cuenta Origen</th>
                                <th class="py-2 px-4 border text-left">Código Cuenta Destino</th>
                                <th class="py-2 px-4 border text-left">Cuenta Destino</th>
                                <th class="py-2 px-4 border text-left">Monto</th>
                                <th class="py-2 px-4 border text-left">Debe</th>
                                <th class="py-2 px-4 border text-left">Haber</th>
                                <th class="py-3 px-4 border text-left">Descripción</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                // Inicializar los contadores de los códigos por cada cuenta
                                $ultimoCodigoPorCuentaOrigen = [];
                                $ultimoCodigoPorCuentaDestino = [];
                            @endphp
                            @foreach($asientos as $asiento)
                                @php
                                    $codigoOrigen = $codigoCuentas[$asiento->cuenta_origen_id] ?? '';
                                    $codigoDestino = $codigoCuentas[$asiento->cuenta_destino_id] ?? '';

                                    // Incrementar el código de cuenta origen si ya existe en la tabla
                                    if (isset($ultimoCodigoPorCuentaOrigen[$asiento->cuenta_origen_id])) {
                                        $ultimoCodigoPorCuentaOrigen[$asiento->cuenta_origen_id]++;
                                    } else {
                                        $ultimoCodigoPorCuentaOrigen[$asiento->cuenta_origen_id] = 1;
                                    }
                                    $codigoOrigen .= '.' . $ultimoCodigoPorCuentaOrigen[$asiento->cuenta_origen_id];

                                    // Incrementar el código de cuenta destino si ya existe en la tabla
                                    if (isset($ultimoCodigoPorCuentaDestino[$asiento->cuenta_destino_id])) {
                                        $ultimoCodigoPorCuentaDestino[$asiento->cuenta_destino_id]++;
                                    } else {
                                        $ultimoCodigoPorCuentaDestino[$asiento->cuenta_destino_id] = 1;
                                    }
                                    $codigoDestino .= '.' . $ultimoCodigoPorCuentaDestino[$asiento->cuenta_destino_id];
                                @endphp
                                <tr>
                                    <td class="py-2 px-4 border">{{ $asiento->fecha }}</td>
                                    <td class="py-2 px-4 border">{{ $codigoOrigen }}</td>
                                    <td class="py-2 px-4 border">{{ $asiento->cuentaOrigen->nombre }}</td>
                                    <td class="py-2 px-4 border">{{ $codigoDestino }}</td>
                                    <td class="py-2 px-4 border">{{ $asiento->cuentaDestino->nombre }}</td>
                                    <td class="py-2 px-4 border">{{ number_format($asiento->monto, 2) }}</td>
                                    <td class="py-2 px-4 border">{{ number_format($asiento->debe, 2) }}</td>
                                    <td class="py-2 px-4 border">{{ number_format($asiento->haber, 2) }}</td>
                                    <td class="py-3 px-4 border">{{ $asiento->descripcion }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>

            <div class="flex justify-center mt-8 space-x-4">
                <a href="{{ route('detalles_balance_copia.index', ['empresa_id' => $empresa->id]) }}"
                   class="bg-blue-500 text-white font-bold py-3 px-6 rounded-full shadow-lg transition duration-300 ease-in-out transform hover:scale-105">
                    Ver Detalles de Balance Copia
                </a>
                <a href="{{ route('libro_diario.index', ['empresa_id' => $empresa->id]) }}"
                   class="bg-blue-500 text-white font-bold py-3 px-6 rounded-full shadow-lg transition duration-300 ease-in-out transform hover:scale-105">
                    Ver Libro Diario
                </a>
            </div>
        </div>
    </div>

    <!-- Script para actualizar los códigos al seleccionar cuentas y manejar el incremento -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const cuentaOrigenSelect = document.getElementById('cuenta_origen_id');
            const codigoOrigenInput = document.getElementById('codigo_origen');
            const cuentaDestinoSelect = document.getElementById('cuenta_destino_id');
            const codigoDestinoInput = document.getElementById('codigo_destino');

            const codigosUsados = {}; // Almacena el último código usado para cada cuenta

            function actualizarCodigo(selectElement, codigoInput) {
                const selectedOption = selectElement.options[selectElement.selectedIndex];
                const codigoBase = selectedOption.getAttribute('data-codigo');
                
                if (!codigosUsados[codigoBase]) {
                    codigosUsados[codigoBase] = 1; // Si es la primera vez, empieza en 1
                } else {
                    codigosUsados[codigoBase] += 1; // Incrementa si ya existe
                }
                
                codigoInput.value = `${codigoBase}.${codigosUsados[codigoBase]}`;
            }

            cuentaOrigenSelect.addEventListener('change', () => actualizarCodigo(cuentaOrigenSelect, codigoOrigenInput));
            cuentaDestinoSelect.addEventListener('change', () => actualizarCodigo(cuentaDestinoSelect, codigoDestinoInput));
        });
    </script>

    @if(session('success'))
        <script>
            alert("{{ session('success') }}");
        </script>
    @endif
</x-app-layout>
