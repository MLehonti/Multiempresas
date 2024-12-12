<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Flujo de Caja para la Empresa ID: ') . $empresa_id }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <h3 class="text-2xl font-bold mb-4 text-gray-700">Flujo de Caja</h3>

                <!-- Mostrar resultados por actividad -->
                @foreach ($resultados as $tipo => $resultado)
                    <div class="mb-6">
                        <h4 class="text-xl font-semibold text-gray-700 mb-2">Actividades de {{ ucfirst($tipo) }}</h4>

                        <table class="min-w-full bg-white border border-gray-300 rounded-lg">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="py-3 px-4 border-b border-gray-300 text-left text-gray-600 font-semibold">Entradas</th>
                                    <th class="py-3 px-4 border-b border-gray-300 text-left text-gray-600 font-semibold">Salidas</th>
                                    <th class="py-3 px-4 border-b border-gray-300 text-left text-gray-600 font-semibold">Flujo Neto</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="bg-white">
                                    <td class="py-3 px-4 border-b border-gray-200 text-green-600 font-semibold">
                                        {{ number_format($resultado['salidas'], 2) }}
                                    </td>
                                    <td class="py-3 px-4 border-b border-gray-200 text-red-600 font-semibold">
                                        {{ number_format($resultado['entradas'], 2) }}
                                    </td>
                                    <td class="py-3 px-4 border-b border-gray-200 text-gray-700 font-semibold">
                                        {{ number_format($resultado['neto'], 2) }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                @endforeach

                <!-- Total General -->
                <h4 class="text-xl font-bold text-gray-700 mt-8 mb-2">Total General</h4>
                <table class="min-w-full bg-white border border-gray-300 rounded-lg">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="py-3 px-4 border-b border-gray-300 text-left text-gray-600 font-semibold">Entradas</th>
                            <th class="py-3 px-4 border-b border-gray-300 text-left text-gray-600 font-semibold">Salidas</th>
                            <th class="py-3 px-4 border-b border-gray-300 text-left text-gray-600 font-semibold">Flujo Neto</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="bg-white">
                            <td class="py-3 px-4 border-b border-gray-200 text-green-600 font-semibold">
                                {{ number_format($totalGeneral['salidas'], 2) }}
                            </td>
                            <td class="py-3 px-4 border-b border-gray-200 text-red-600 font-semibold">
                                {{ number_format($totalGeneral['entradas'], 2) }}
                            </td>
                            <td class="py-3 px-4 border-b border-gray-200 text-gray-700 font-semibold">
                                {{ number_format($totalGeneral['neto'], 2) }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
