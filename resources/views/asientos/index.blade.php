<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detalles de Balance Copia para la Empresa ID: ') . $empresa_id }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <h3 class="text-2xl font-bold mb-4 text-gray-700">Detalles del Balance Copia</h3>

                @if($detalles->isEmpty())
                    <p class="text-gray-600">No hay detalles de balance copia registrados para esta empresa.</p>
                @else
                    <table class="min-w-full bg-white border border-gray-300 rounded-lg">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="py-3 px-4 border-b border-gray-300 text-left text-gray-600 font-semibold">ID</th>
                                <th class="py-3 px-4 border-b border-gray-300 text-left text-gray-600 font-semibold">Cuenta ID</th>
                                <th class="py-3 px-4 border-b border-gray-300 text-left text-gray-600 font-semibold">Debe</th>
                                <th class="py-3 px-4 border-b border-gray-300 text-left text-gray-600 font-semibold">Haber</th>
                                <th class="py-3 px-4 border-b border-gray-300 text-left text-gray-600 font-semibold">Fecha Creación</th>
                                <th class="py-3 px-4 border-b border-gray-300 text-left text-gray-600 font-semibold">Fecha Actualización</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($detalles as $detalle)
                                <tr class="{{ $loop->odd ? 'bg-gray-50' : 'bg-white' }}">
                                    <td class="py-3 px-4 border-b border-gray-200 text-gray-700">{{ $detalle->id }}</td>
                                    <td class="py-3 px-4 border-b border-gray-200 text-gray-700">{{ $detalle->cuenta_id }}</td>
                                    <td class="py-3 px-4 border-b border-gray-200 text-green-600 font-semibold">{{ number_format($detalle->debe, 2) }}</td>
                                    <td class="py-3 px-4 border-b border-gray-200 text-red-600 font-semibold">{{ number_format($detalle->haber, 2) }}</td>
                                    <td class="py-3 px-4 border-b border-gray-200 text-gray-600">{{ $detalle->created_at->format('d/m/Y H:i') }}</td>
                                    <td class="py-3 px-4 border-b border-gray-200 text-gray-600">{{ $detalle->updated_at->format('d/m/Y H:i') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
