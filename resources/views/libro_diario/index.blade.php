<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Libro Diario y Mayor para ') . $empresa->nombre }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <h3 class="text-2xl font-bold mb-4 text-gray-700">Libro Diario y Mayor</h3>

                <!-- Formulario de Filtro de Fecha -->
                <form action="" method="GET" class="mb-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="form-group">
                            <label for="fecha_inicio" class="block text-gray-700 font-bold mb-2">Fecha Inicio</label>
                            <input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control w-full border rounded px-3 py-2" value="{{ request('fecha_inicio') }}">
                        </div>
                        <div class="form-group">
                            <label for="fecha_fin" class="block text-gray-700 font-bold mb-2">Fecha Fin</label>
                            <input type="date" name="fecha_fin" id="fecha_fin" class="form-control w-full border rounded px-3 py-2" value="{{ request('fecha_fin') }}">
                        </div>
                        <div class="form-group flex items-end">
                            <button  class="bg-blue-500 text-white font-bold py-3 px-6 rounded-full shadow-lg transition duration-300 ease-in-out transform hover:scale-105">
                                Filtrar
                            </button>
                        </div>
                    </div>
                </form>

                @php
                    // Filtrar los asientos por fecha en la vista
                    $asientosFiltrados = $asientos->filter(function ($asientosPorFecha, $fecha) {
                        $fechaInicio = request('fecha_inicio');
                        $fechaFin = request('fecha_fin');

                        if ($fechaInicio && $fecha < $fechaInicio) return false;
                        if ($fechaFin && $fecha > $fechaFin) return false;

                        return true;
                    });
                @endphp

                @forelse($asientosFiltrados as $fecha => $asientosPorFecha)
                    <div class="mb-8">
                        <h4 class="text-lg font-semibold text-gray-600 mb-2">{{ \Carbon\Carbon::parse($fecha)->format('d/m/Y') }}</h4>
                        <table class="min-w-full bg-white border border-gray-300 rounded-lg mb-4">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="py-2 px-4 border text-left text-gray-600 font-semibold">Cuenta Origen</th>
                                    <th class="py-2 px-4 border text-left text-gray-600 font-semibold">Cuenta Destino</th>
                                    <th class="py-2 px-4 border text-left text-gray-600 font-semibold">Monto</th>
                                    <th class="py-2 px-4 border text-left text-gray-600 font-semibold">Debe</th>
                                    <th class="py-2 px-4 border text-left text-gray-600 font-semibold">Haber</th>
                                    <th class="py-2 px-4 border text-left text-gray-600 font-semibold">Descripción</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($asientosPorFecha as $asiento)
                                    <tr class="{{ $loop->odd ? 'bg-gray-50' : 'bg-white' }}">
                                        <td class="py-2 px-4 border text-gray-700">{{ $asiento->cuentaOrigen->nombre }}</td>
                                        <td class="py-2 px-4 border text-gray-700">{{ $asiento->cuentaDestino->nombre }}</td>
                                        <td class="py-2 px-4 border text-gray-800 font-semibold">{{ number_format($asiento->monto, 2) }}</td>
                                        <td class="py-2 px-4 border text-green-600 font-semibold">{{ number_format($asiento->debe, 2) }}</td>
                                        <td class="py-2 px-4 border text-red-600 font-semibold">{{ number_format($asiento->haber, 2) }}</td>
                                        <td class="py-2 px-4 border text-gray-600">{{ $asiento->descripcion }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @empty
                    <p class="text-gray-600">No hay asientos contables registrados en el Libro Diario para esta empresa en el rango de fechas seleccionado.</p>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
