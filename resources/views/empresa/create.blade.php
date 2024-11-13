<!-- resources/views/empresa/create.blade.php -->

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Crear Empresa') }}
        </h2>
    </x-slot>

    <!-- Incluir la librería de SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <!-- Mensaje de éxito o error -->
                <div id="message" class="hidden mb-4 text-center text-white font-bold py-2 px-4 rounded"></div>

                <!-- Formulario de Crear Empresa -->
                <form id="empresaForm">
                    @csrf
                    <!-- Campo Nombre de la Empresa -->
                    <div class="mb-4">
                        <label for="nombre" class="block text-gray-700 text-sm font-bold mb-2">Nombre de la Empresa</label>
                        <input type="text" name="nombre" id="nombre" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    </div>

                    <!-- Campo Rubro como Cuadros -->
                    <div class="mb-4">
                        <label for="rubro_id" class="block text-gray-700 text-sm font-bold mb-2">Seleccione un Rubro</label>
                        <div id="rubros-container" class="grid grid-cols-2 md:grid-cols-3 gap-4">
                            @foreach($rubros as $rubro)
                                <div class="cursor-pointer p-4 border rounded-lg shadow hover:bg-gray-100 transition duration-200 ease-in-out rubro-card" data-id="{{ $rubro->id }}">
                                    <input type="radio" name="rubro_id" value="{{ $rubro->id }}" id="rubro_{{ $rubro->id }}" class="hidden">
                                    <label for="rubro_{{ $rubro->id }}" class="block text-center">
                                        <span class="block font-bold">{{ $rubro->nombre }}</span>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Botón de Enviar (Componente de Botón) -->
                    <div class="flex items-center justify-center mt-4"> <!-- Ajustado para centrar el botón -->
                        <x-button type="button" id="submitBtn" style="background-color: #3490dc; color: white;">
                            Crear Empresa
                        </x-button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        .rubro-card.selected {
            border-color: #4299e1;
            background-color: #ebf8ff;
        }

        #message.success {
            background-color: #38a169;
        }

        #message.error {
            background-color: #e53e3e;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const rubroCards = document.querySelectorAll('.rubro-card');
            let selectedRubro = null;

            rubroCards.forEach(card => {
                card.addEventListener('click', function () {
                    rubroCards.forEach(c => c.classList.remove('selected'));
                    this.classList.add('selected');
                    selectedRubro = this.getAttribute('data-id');
                });
            });

            document.getElementById('submitBtn').addEventListener('click', function (event) {
                event.preventDefault();

                const nombre = document.getElementById('nombre').value;
                const rubro_id = selectedRubro;

                if (!rubro_id) {
                    showSweetAlert('Error', 'Por favor, seleccione un rubro.', 'error');
                    return;
                }

                // Realizar la solicitud AJAX para enviar los datos en JSON
                fetch('{{ route("empresa.store") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        nombre: nombre,
                        rubro_id: rubro_id
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showSweetAlert('Éxito', data.message, 'success');
                        setTimeout(() => {
                            window.location.href = data.redirect;
                        }, 2000);
                    } else {
                        showSweetAlert('Error', data.message, 'error');
                    }
                })
                .catch(error => {
                    showSweetAlert('Error', 'Ocurrió un error al crear la empresa. Por favor, inténtalo de nuevo.', 'error');
                });
            });

            function showSweetAlert(title, text, icon) {
                Swal.fire({
                    title: title,
                    text: text,
                    icon: icon,
                    confirmButtonText: 'Aceptar'
                });
            }
        });
    </script>
</x-app-layout>
