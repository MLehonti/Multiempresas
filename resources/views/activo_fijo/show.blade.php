<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight text-center">
            {{ __('Listado de Depreciaciones') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">

                <!-- Botón para aplicar depreciación -->
                <div class="flex justify-center mb-6">
                    <a href="{{ route('aplicar.depreciacion') }}"
                       class="px-6 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-700">
                        Aplicar Depreciación
                    </a>
                </div>

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
                                <th class="px-6 py-3 text-sm font-medium">Acciones</th> <!-- Nueva columna para eliminar -->
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($depreciaciones as $depreciacion)
                                <tr>
                                    <td class="px-6 py-4 text-sm text-gray-800">{{ $depreciacion->id }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-800">{{ $depreciacion->nombre }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-800">{{ $depreciacion->porcentaje_depreciacion }}%</td>
                                    <td class="px-6 py-4 text-sm text-gray-800">{{ $depreciacion->descripcion }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-800">
                                        @foreach($depreciacion->cuentas as $cuenta)
                                            <span>{{ $cuenta->nombre }}</span>
                                        @endforeach
                                    </td>
                                    <td class="px-6 py-4 text-sm text-center">
                                        <form action="{{ route('eliminar.depreciacion', $depreciacion->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas eliminar esta depreciación?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bg-blue-500 text-white py-1 px-4 rounded-lg hover:bg-blue-700">
                                                Eliminar Depreciación
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>     </div>
        </div>
    </div>
</x-app-layout>
