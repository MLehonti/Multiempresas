<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight text-center">
            {{ __('Listado de Depreciaciones') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">

                <!-- Lista de Depreciaciones -->
                <h3 class="text-2xl font-semibold text-gray-800 mb-6 text-center">Todas las Depreciaciones</h3>

                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white border border-gray-300 rounded-lg shadow-md">
                        <thead class="bg-gradient-to-r from-teal-500 to-blue-500 text-black">
                            <tr>
                                <th class="px-6 py-3 text-sm font-medium">ID</th>
                                <th class="px-6 py-3 text-sm font-medium">Nombre</th>
                                <th class="px-6 py-3 text-sm font-medium">Porcentaje de Depreciación</th>
                                <th class="px-6 py-3 text-sm font-medium">Descripción</th>
                                <th class="px-6 py-3 text-sm font-medium">Cuentas Asociadas</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($depreciaciones as $depreciacion)
                                <tr class="bg-gray-50 hover:bg-gray-100">
                                    <td class="px-6 py-4 text-sm text-gray-800">{{ $depreciacion->id }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-800">{{ $depreciacion->nombre }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-800">{{ $depreciacion->porcentaje_depreciacion }}%</td>
                                    <td class="px-6 py-4 text-sm text-gray-800">{{ $depreciacion->descripcion }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-800">
                                        @foreach($depreciacion->cuentas as $cuenta)
                                            <span class="block text-gray-600">{{ $cuenta->nombre }} ({{ $cuenta->tipo }})</span>
                                        @endforeach
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Mensaje si no hay depreciaciones -->
                @if($depreciaciones->isEmpty())
                    <p class="text-center text-gray-500 mt-4">No hay depreciaciones registradas.</p>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
