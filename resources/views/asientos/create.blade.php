<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Registrar Asiento Contable para ') . $empresa->nombre }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <form action="{{ route('asientos.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="empresa_id" value="{{ $empresa->id }}">

                    <!-- Formulario de registro de asiento contable con estructura en una sola columna y espacios -->
                    <div class="grid grid-cols-1 gap-6">
                        <div class="form-group">
                            <label for="cuenta_origen_id" class="block text-gray-700 font-bold mb-2">Cuenta Origen</label>
                            <select name="cuenta_origen_id" class="form-control w-full border rounded px-3 py-2">
                                @foreach ($cuentas as $cuenta)
                                    <option value="{{ $cuenta->id }}">{{ $cuenta->nombre }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="cuenta_destino_id" class="block text-gray-700 font-bold mb-2">Cuenta Destino</label>
                            <select name="cuenta_destino_id" class="form-control w-full border rounded px-3 py-2">
                                @foreach ($cuentas as $cuenta)
                                    <option value="{{ $cuenta->id }}">{{ $cuenta->nombre }}</option>
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
                            <label for="fecha" class="block text-gray-700 font-bold mb-2">Fecha</label>
                            <input type="date" name="fecha" class="form-control w-full border rounded px-3 py-2" required>
                        </div>
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
                                <th class="py-2 px-4 border text-left">Cuenta Origen</th>
                                <th class="py-2 px-4 border text-left">Cuenta Destino</th>
                                <th class="py-2 px-4 border text-left">Monto</th>
                                <th class="py-2 px-4 border text-left">Debe</th>
                                <th class="py-2 px-4 border text-left">Haber</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($asientos as $asiento)
                                <tr>
                                    <td class="py-2 px-4 border">{{ $asiento->fecha }}</td>
                                    <td class="py-2 px-4 border">{{ $asiento->cuentaOrigen->nombre }}</td>
                                    <td class="py-2 px-4 border">{{ $asiento->cuentaDestino->nombre }}</td>
                                    <td class="py-2 px-4 border">{{ number_format($asiento->monto, 2) }}</td>
                                    <td class="py-2 px-4 border">{{ number_format($asiento->debe, 2) }}</td>
                                    <td class="py-2 px-4 border">{{ number_format($asiento->haber, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </div>

    <!-- Script para mostrar la alerta de éxito -->
    @if(session('success'))
        <script>
            alert("{{ session('success') }}");
        </script>
    @endif
</x-app-layout>
