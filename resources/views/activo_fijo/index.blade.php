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
                                <th class="px-4 py-2 text-sm font-medium text-gray-900">ID</th>
                                <th class="px-4 py-2 text-sm font-medium text-gray-900">Nombre</th>
                                <th class="px-4 py-2 text-sm font-medium text-gray-900">Tipo</th>
                                <th class="px-4 py-2 text-sm font-medium text-gray-900">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cuentas as $cuenta)
                                <tr>
                                    <td class="px-4 py-2 text-sm text-gray-800">{{ $cuenta->id }}</td>
                                    <td class="px-4 py-2 text-sm text-gray-800">{{ $cuenta->nombre }}</td>
                                    <td class="px-4 py-2 text-sm text-gray-800">{{ $cuenta->tipo }}</td>
                                    <td class="px-4 py-2 text-sm text-gray-800">
                                        <!-- Botón para abrir el formulario de depreciación y desplazar -->
                                        <button
                                            class="text-blue-500 hover:text-blue-700"
                                            onclick="scrollToFormAndSetCuentaId({{ $cuenta->id }})">
                                            Agregar Depreciación
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Formulario de Depreciación -->
                <div id="form-depreciacion" class="mt-8">
                    <h3 class="text-lg font-bold mb-4 flex justify-center">Agregar Depreciación</h3>
                    <!-- Formulario que capturará los datos de la depreciación -->
                    <form action="{{ route('depreciacion.store', 'cuentaId_placeholder') }}" method="POST" id="form-depreciacion-actual">
                        @csrf
                        <div class="mb-4">
                            <label for="nombre" class="block text-sm font-medium text-gray-700">Nombre de la Depreciación:</label>
                            <input type="text" name="nombre" id="nombre" class="form-control w-full" required>
                        </div>
                        <div class="mb-4">
                            <label for="porcentaje_depreciacion" class="block text-sm font-medium text-gray-700">Porcentaje de Depreciación:</label>
                            <input type="number" name="porcentaje_depreciacion" id="porcentaje_depreciacion" min="0" max="100" class="form-control w-full" required>
                        </div>
                        <div class="mb-4">
                            <label for="descripcion" class="block text-sm font-medium text-gray-700">Descripción:</label>
                            <textarea name="descripcion" id="descripcion" class="form-control w-full"></textarea>
                        </div>
                        <div class="flex justify-center">
                            <button type="submit" class="bg-blue-500 text-white p-2 rounded hover:bg-blue-700">Guardar Depreciación</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript para desplazarse suavemente al formulario -->
    <script>
        function scrollToFormAndSetCuentaId(cuentaId) {
            // Desplazarse suavemente hacia el formulario
            const form = document.getElementById('form-depreciacion');
            window.scrollTo({
                top: form.offsetTop - 100, // Ajuste para darle un pequeño margen superior
                behavior: 'smooth'
            });

            // Cambiar la acción del formulario para incluir el id de la cuenta seleccionada
            const formAction = "{{ route('depreciacion.store', ':id') }}".replace(':id', cuentaId);
            document.getElementById('form-depreciacion-actual').action = formAction;
        }
    </script>
</x-app-layout>
