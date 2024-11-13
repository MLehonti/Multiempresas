{{-- resources/views/empresa/show.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tu Empresa') }}
        </h2>
    </x-slot>

    <!-- Incluir la librería de SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                @if(session('success'))
                    <div class="alert alert-success mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="p-6">
                    <h3 class="text-xl font-bold">Tu empresa creada es:</h3>
                    <p class="text-lg">{{ $empresa->nombre }}</p>
                    <p class="text-sm text-gray-600">Rubro: {{ $empresa->rubro->nombre }}</p>

                    <!-- Botón para redirigir a la creación del balance de apertura -->
                    <div class="flex items-center justify-center mt-4">
                        <a href="{{ route('plan-cuentas.create', ['empresa_id' => $empresa->id]) }}">
                            <x-button type="button" style="background-color: #38a169; color: white;">
                                Crear Plan de Cuentas
                            </x-button>
                        </a>
                    </div>

                    <!-- Botón para eliminar la empresa -->
                    <div class="flex items-center justify-center mt-4">
                        <x-button type="button" id="deleteBtn" style="background-color: #e3342f; color: white;">
                            Eliminar Empresa
                        </x-button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('deleteBtn').addEventListener('click', function () {
            Swal.fire({
                title: '¿Estás seguro?',
                text: "¡Esta acción no se puede deshacer y se eliminarán todos los datos relacionados con la empresa!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e3342f',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Realizar la solicitud de eliminación con fetch
                    fetch('{{ route("empresa.destroy", ["empresa" => $empresa->id]) }}', {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire(
                                '¡Eliminada!',
                                data.message,
                                'success'
                            );
                            setTimeout(() => {
                                window.location.href = '{{ route("dashboard") }}'; // Redirigir al Dashboard después de eliminar
                            }, 2000); // Redirigir al Dashboard después de eliminar
                        } else {
                            Swal.fire('Error', data.message, 'error');
                        }
                    })
                    .catch(error => {
                        Swal.fire('Error', 'Ocurrió un error al eliminar la empresa. Por favor, inténtalo de nuevo.', 'error');
                    });
                }
            });
        });
    </script>
</x-app-layout>

