@role('admin')
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight text-center">
            {{ __('Lista de Usuarios') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <!-- Botón para descargar PDF y Excel -->
                <div class="text-center mb-4">
                    <a href="{{ route('users.export.pdf') }}" style="background-color: #22c55e; color: white; padding: 0.5rem 1rem; border-radius: 0.5rem; text-decoration: none; margin-right: 10px;">
                        Descargar PDF
                    </a>
                    <a href="{{ route('users.export.excel') }}" style="background-color: #3490dc; color: white; padding: 0.5rem 1rem; border-radius: 0.5rem; text-decoration: none;">
                        Descargar Excel
                    </a>
                </div>

                <!-- Contenedor centralizado para la tabla -->
                <div class="flex justify-center">
                    <!-- Tabla de usuarios con mayor ancho -->
                    <table class="min-w-full bg-white border w-3/4">
                        <thead>
                            <tr>
                                <th class="py-2 px-4 border text-left">ID</th>
                                <th class="py-2 px-4 border text-left">Nombre</th>
                                <th class="py-2 px-4 border text-left">Email</th>
                                <th class="py-2 px-4 border text-left">Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="userTableBody">
                            <!-- Aquí se insertarán los usuarios dinámicamente -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            fetch("{{ route('users.index') }}", {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(users => {
                const userTableBody = document.getElementById('userTableBody');
                userTableBody.innerHTML = '';

                users.forEach(user => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td class="border px-4 py-2">${user.id}</td>
                        <td class="border px-4 py-2">${user.name}</td>
                        <td class="border px-4 py-2">${user.email}</td>
                        <td class="border px-4 py-2 text-center">
                            <button style="background-color: #dc2626; color: white; padding: 0.5rem 1rem; border-radius: 0.5rem; margin-right: 0.5rem;" onclick="deleteUser(${user.id})">
                                Eliminar
                            </button>
                            <button style="background-color: #22c55e; color: white; padding: 0.5rem 1rem; border-radius: 0.5rem; margin-left: 0.5rem;" onclick="location.href='/users/${user.id}/assign-role'">
                                Asignar Rol
                            </button>
                        </td>
                    `;
                    userTableBody.appendChild(row);
                });
            })
            .catch(error => console.error('Error al cargar los usuarios:', error));
        });

        function deleteUser(userId) {
            if (confirm('¿Estás seguro de que quieres eliminar este usuario?')) {
                fetch(`/users/${userId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Usuario eliminado correctamente.');
                        location.reload();
                    } else {
                        alert('Error al eliminar el usuario.');
                    }
                })
                .catch(error => console.error('Error al eliminar el usuario:', error));
            }
        }
    </script>
</x-app-layout>
@endrole
