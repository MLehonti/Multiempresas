<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight text-center">
            {{ __('Gestión de Cuentas') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <div class="mb-6">
                    <h3 class="text-lg font-bold">Agregar Nueva Cuenta</h3>
                    <form id="createForm">
                        <div class="form-group mb-4">
                            <label for="nombre" class="block text-sm font-medium text-gray-700 mb-2">Nombre:</label>
                            <input type="text" id="nombre" name="nombre" class="form-control border-gray-300 rounded-md shadow-sm w-full" required>
                        </div>
                        <div class="form-group mb-4">
                            <label for="tipo" class="block text-sm font-medium text-gray-700 mb-2">Tipo:</label>
                            <input type="text" id="tipo" name="tipo" class="form-control border-gray-300 rounded-md shadow-sm w-full" required>
                        </div>
                        
                        <button style="background-color: #38a169; color: white; padding: 0.75rem 1.5rem; border-radius: 0.5rem; margin-top: 1rem;">
                            Agregar
                        </button>
                    </form>
                </div>

                <!-- Formulario de Edición -->
                <div id="editFormContainer" style="display: none;">
                    <h3 class="text-lg font-bold">Editar Cuenta</h3>
                    <form id="editForm">
                        <div class="form-group mb-4">
                            <label for="editNombre" class="block text-sm font-medium text-gray-700 mb-2">Nombre:</label>
                            <input type="text" id="editNombre" name="nombre" class="form-control border-gray-300 rounded-md shadow-sm w-full" required>
                        </div>
                        <div class="form-group mb-4">
                            <label for="editTipo" class="block text-sm font-medium text-gray-700 mb-2">Tipo:</label>
                            <input type="text" id="editTipo" name="tipo" class="form-control border-gray-300 rounded-md shadow-sm w-full" required>
                        </div>
                        
                        <button type="submit" id="updateButton" style="background-color: #38a169; color: white; padding: 0.75rem 1.5rem; border-radius: 0.5rem; margin-top: 1rem;">
                            Actualizar
                        </button>
                    </form>
                </div>

                <h3 class="text-lg font-bold mb-4 flex justify-center">Lista de Cuentas</h3>

                <!-- Aseguramos que el contenedor de la tabla se centre -->
                <div class="flex justify-center">
                    <table class="min-w-full bg-white border mt-4 w-3/4">
                        <thead>
                            <tr>
                                <th class="py-2 px-4 border">ID</th>
                                <th class="py-2 px-4 border">Nombre</th>
                                <th class="py-2 px-4 border">Tipo</th>
                                <th class="py-2 px-4 border">Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="cuentasTableBody">
                            <!-- Aquí se llenarán los datos de las cuentas -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Incluir la librería de SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
      document.addEventListener('DOMContentLoaded', function () {
    const cuentasTableBody = document.getElementById('cuentasTableBody');
    const editFormContainer = document.getElementById('editFormContainer');
    const editForm = document.getElementById('editForm');
    const nombreInput = document.getElementById('editNombre');
    const tipoInput = document.getElementById('editTipo');
    let selectedCuentaId = null;

    // Función para cargar las cuentas
    function loadCuentas() {
        fetch('/cuentas', {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            cuentasTableBody.innerHTML = ''; // Limpiar la tabla

            data.forEach(cuenta => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td class="border px-4 py-2">${cuenta.id}</td>
                    <td class="border px-4 py-2">${cuenta.nombre}</td>
                    <td class="border px-4 py-2">${cuenta.tipo}</td>
                    <td class="border px-4 py-2">
                        <button style="background-color: #FFD700; color: white; padding: 0.5rem 1rem; border-radius: 0.5rem; margin-right: 0.5rem;" onclick="editCuenta(${cuenta.id})">
                            Editar
                        </button>
                        <button style="background-color: #EF4444; color: white; padding: 0.5rem 1rem; border-radius: 0.5rem; margin-left: 0.5rem;" onclick="deleteCuenta(${cuenta.id})">
                            Eliminar
                        </button>
                    </td>
                `;
                cuentasTableBody.appendChild(row);
            });
        });
    }

    // Cargar cuentas al inicio
    loadCuentas();

    // Crear cuenta
    document.getElementById('createForm').addEventListener('submit', function (e) {
        e.preventDefault();

        const nombre = document.getElementById('nombre').value;
        const tipo = document.getElementById('tipo').value;

        fetch('/cuentas', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            },
            body: JSON.stringify({ nombre, tipo })
        })
        .then(response => response.json())
        .then(data => {
            alert('Cuenta creada exitosamente.');
            loadCuentas(); // Recargar cuentas
            document.getElementById('createForm').reset();
        })
        .catch(error => console.error('Error:', error));
    });

    // Editar cuenta
    window.editCuenta = function(id) {
        // Hacer una solicitud para obtener los datos de la cuenta por ID
        fetch(`/cuentas/${id}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            // Verifica si la respuesta fue exitosa
            if (data.success) {
                // Llenar los campos del formulario de edición con los datos obtenidos
                nombreInput.value = data.cuenta.nombre; // Llenar el nombre
                tipoInput.value = data.cuenta.tipo; // Llenar el tipo
                selectedCuentaId = data.cuenta.id; // Guardar el ID de la cuenta seleccionada

                // Mostrar el formulario de edición
                editFormContainer.style.display = 'block';
            } else {
                console.error("Error en la respuesta del servidor:", data.message);
                Swal.fire("Error", data.message, "error");
            }
        })
        .catch(error => {
            console.error('Error al obtener los datos de la cuenta:', error);
            Swal.fire("Error", "No se pudieron cargar los datos de la cuenta.", "error");
        });
    }

    // Actualizar cuenta con confirmación de SweetAlert
    editForm.addEventListener('submit', function (e) {
        e.preventDefault();

        Swal.fire({
            title: '¿Estás seguro?',
            text: "¿Quieres actualizar esta cuenta?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, actualizar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                const nombre = nombreInput.value;
                const tipo = tipoInput.value;

                fetch(`/cuentas/${selectedCuentaId}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    body: JSON.stringify({ nombre, tipo })
                })
                .then(response => response.json())
                .then(data => {
                    Swal.fire('Actualizado!', 'La cuenta ha sido actualizada correctamente.', 'success');
                    loadCuentas(); // Recargar cuentas
                    editFormContainer.style.display = 'none'; // Ocultar el formulario de edición
                })
                .catch(error => {
                    console.error('Error al actualizar la cuenta:', error);
                    Swal.fire('Error', 'No se pudo actualizar la cuenta.', 'error');
                });
            }
        });
    });

    // Eliminar cuenta
    window.deleteCuenta = function(id) {
        if (confirm('¿Estás seguro de eliminar esta cuenta?')) {
            fetch(`/cuentas/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest',
                }
            })
            .then(response => response.json())
            .then(data => {
                alert('Cuenta eliminada correctamente.');
                loadCuentas(); // Recargar cuentas
            })
            .catch(error => console.error('Error:', error));
        }
    }
});

    </script>
</x-app-layout>
