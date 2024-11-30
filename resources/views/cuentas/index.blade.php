<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight text-center">
            {{ __('Gestión de Cuentas Activas') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <!-- Tabla de Cuentas -->
                <h3 class="text-lg font-bold mb-4 flex justify-center">Lista de Cuentas Activas</h3>
   <div class="flex justify-center">
                    <table class="min-w-full bg-white border mt-4">
                        <thead>
                            <tr>
                                <th class="px-4 py-2 text-sm font-medium text-gray-900">Nombre</th>
                                <th class="px-4 py-2 text-sm font-medium text-gray-900">Tipo</th>
                                <th class="px-4 py-2 text-sm font-medium text-gray-900">Acciones</th> <!-- Nueva columna para el botón -->
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cuentas as $cuenta)
                                <tr>
                                    <td class="px-4 py-2 text-sm text-gray-800">{{ $cuenta->nombre }}</td>
                                    <td class="px-4 py-2 text-sm text-gray-800">{{ $cuenta->tipo }}</td>
                                    <td class="px-4 py-2 text-sm text-gray-800">
                                        <!-- Botón que lleva al formulario -->
                                        <button onclick="scrollToForm({{ $cuenta->id }})" class="text-blue-500 hover:text-blue-700">
                                            Agregar Depreciación
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Formulario para agregar depreciación -->
                <div id="form-depreciacion" class="mt-8">
                    <h3 class="text-lg font-bold mb-4 flex justify-center">Agregar Depreciación</h3>
                    <form action="{{ route('depreciacion.store', ['cuentaId' => $cuenta->id]) }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="nombre" class="block text-sm font-medium text-gray-700">Nombre de la Depreciación</label>
                            <input type="text" name="nombre" id="nombre" class="form-input mt-1 block w-full" required>
                        </div>

                        <div class="form-group mt-4">
                            <label for="porcentaje_depreciacion" class="block text-sm font-medium text-gray-700">Porcentaje de Depreciación</label>
                            <input type="number" name="porcentaje_depreciacion" id="porcentaje_depreciacion" class="form-input mt-1 block w-full" required>
                        </div>

                        <div class="form-group mt-4">
                            <label for="descripcion" class="block text-sm font-medium text-gray-700">Descripción</label>
                            <textarea name="descripcion" id="descripcion" class="form-input mt-1 block w-full"></textarea>
                        </div>

                        <div class="mt-4 flex justify-center">
                            <button type="submit" class="bg-green-500 text-white px-6 py-2 rounded">Guardar Depreciación</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div> <script>
        function scrollToForm(cuentaId) {
            window.location.href = "#form-depreciacion";
        }</script>
</x-app-layout>
