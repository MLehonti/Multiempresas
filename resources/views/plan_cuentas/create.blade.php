<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight text-center">
            {{ __('Crear Plan de Cuentas') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <form action="{{ route('plan-cuentas.store') }}" method="POST" id="planCuentasForm">
                    @csrf

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="form-group">
                        <label for="empresa_id">Empresa:</label>
                        <select name="empresa_id" id="empresa_id" class="form-control">
                            <!-- Aquí se insertarán dinámicamente las empresas -->
                        </select>
                    </div>

                    <div class="form-group mt-4">
                        <label for="cuentas">Selecciona las cuentas:</label>

                        <!-- Distribución en dos columnas -->
                        <div class="grid grid-cols-2 gap-4">
                            <!-- Columna 1 -->
                            <div>
                                <h3 class="text-lg font-bold mt-6">1 Activos Corrientes</h3>
                                <ul id="activosCorrientes">
                                    <!-- Cuentas de activos corrientes se cargarán aquí -->
                                </ul>

                                <h3 class="text-lg font-bold mt-6">3 Pasivos Corrientes</h3>
                                <ul id="pasivosCorrientes">
                                    <!-- Cuentas de pasivos corrientes se cargarán aquí -->
                                </ul>

                                <h3 class="text-lg font-bold mt-6">5 Patrimonio</h3>
                                <ul id="patrimonio">
                                    <!-- Cuentas de patrimonio se cargarán aquí -->
                                </ul>
                            </div>

                            <!-- Columna 2 -->
                            <div>
                                <h3 class="text-lg font-bold mt-6">2 Activos No Corrientes</h3>
                                <ul id="activosNoCorrientes">
                                    <!-- Cuentas de activos no corrientes se cargarán aquí -->
                                </ul>

                                <h3 class="text-lg font-bold mt-6">4 Pasivos No Corrientes</h3>
                                <ul id="pasivosNoCorrientes">
                                    <!-- Cuentas de pasivos no corrientes se cargarán aquí -->
                                </ul>
                            </div>
                        </div>

                    </div>

                    <!-- Alineando el botón a la derecha -->
                    <div class="flex justify-end mt-4">
                        <button style="background-color: #38a169; color: white; padding: 0.75rem 1.5rem; border-radius: 0.5rem; margin-top: 1rem;">
                            Crear Plan de Cuentas
                        </button>
                    </div>
                </form>

                <!-- Tabla de vista previa del plan de cuentas -->
                <div class="mt-8">
                    <h3 class="text-xl font-bold mb-4">Vista Previa del Plan de Cuentas</h3>
                    <table class="min-w-full bg-white border w-full" id="tablaVistaPrevia">
                        <thead>
                            <tr>
                                <th class="py-2 px-4 border">Código</th>
                                <th class="py-2 px-4 border">Cuenta</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Aquí se insertarán las cuentas seleccionadas dinámicamente -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Script para cargar datos de cuentas y actualizar la vista previa -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Realizar el fetch para obtener las cuentas y la empresa en formato JSON
            fetch("{{ route('plan-cuentas.create') }}", {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                const empresaSelect = document.getElementById('empresa_id');
                empresaSelect.innerHTML = `<option value="${data.empresa.id}">${data.empresa.nombre}</option>`;

                const activosCorrientes = document.getElementById('activosCorrientes');
                const activosNoCorrientes = document.getElementById('activosNoCorrientes');
                const pasivosCorrientes = document.getElementById('pasivosCorrientes');
                const pasivosNoCorrientes = document.getElementById('pasivosNoCorrientes');
                const patrimonio = document.getElementById('patrimonio');

                data.cuentas.forEach(cuenta => {
                    const listItem = `<li>
                        <input type="checkbox" name="cuentas_seleccionadas[]" value="${cuenta.id}" data-nombre="${cuenta.nombre}" data-tipo="${cuenta.tipo}" onchange="actualizarVistaPrevia()">
                        ${cuenta.nombre}
                    </li>`;

                    switch (cuenta.tipo) {
                        case 'activo_corriente':
                            activosCorrientes.innerHTML += listItem;
                            break;
                        case 'activo_no_corriente':
                            activosNoCorrientes.innerHTML += listItem;
                            break;
                        case 'pasivo_corriente':
                            pasivosCorrientes.innerHTML += listItem;
                            break;
                        case 'pasivo_no_corriente':
                            pasivosNoCorrientes.innerHTML += listItem;
                            break;
                        case 'patrimonio':
                            patrimonio.innerHTML += listItem;
                            break;
                    }
                });
            })
            .catch(error => console.error('Error al cargar las cuentas:', error));
        });

        function actualizarVistaPrevia() {
            const checkboxes = document.querySelectorAll('input[name="cuentas_seleccionadas[]"]:checked');
            const tablaVistaPrevia = document.getElementById('tablaVistaPrevia').getElementsByTagName('tbody')[0];
            tablaVistaPrevia.innerHTML = ''; // Limpiar tabla antes de actualizar

            const tipos = {
                '1 Activos Corrientes': [],
                '2 Activos No Corrientes': [],
                '3 Pasivos Corrientes': [],
                '4 Pasivos No Corrientes': [],
                '5 Patrimonio': []
            }; // Definir el orden de los tipos de cuentas

            // Agregar las cuentas a sus tipos correspondientes
            checkboxes.forEach((checkbox) => {
                const nombreCuenta = checkbox.getAttribute('data-nombre');
                const tipoCuenta = checkbox.getAttribute('data-tipo');

                if (tipoCuenta.includes('activo_corriente')) {
                    tipos['1 Activos Corrientes'].push(nombreCuenta);
                } else if (tipoCuenta.includes('activo_no_corriente')) {
                    tipos['2 Activos No Corrientes'].push(nombreCuenta);
                } else if (tipoCuenta.includes('pasivo_corriente')) {
                    tipos['3 Pasivos Corrientes'].push(nombreCuenta);
                } else if (tipoCuenta.includes('pasivo_no_corriente')) {
                    tipos['4 Pasivos No Corrientes'].push(nombreCuenta);
                } else if (tipoCuenta.includes('patrimonio')) {
                    tipos['5 Patrimonio'].push(nombreCuenta);
                }
            });

            const codigos = {
                '1 Activos Corrientes': 1,
                '2 Activos No Corrientes': 1,
                '3 Pasivos Corrientes': 1,
                '4 Pasivos No Corrientes': 1,
                '5 Patrimonio': 1,
            }; // Para manejar el autoincremento de cada tipo de cuenta

            // Iterar sobre los tipos de cuentas en el orden correcto
            for (const tipo in tipos) {
                if (tipos[tipo].length > 0) {
                    // Agregar fila con el título del tipo de cuenta
                    const filaTitulo = document.createElement('tr');
                    filaTitulo.innerHTML = `
                        <td colspan="2" class="border px-4 py-2 font-bold">${tipo}</td>
                    `;
                    tablaVistaPrevia.appendChild(filaTitulo);

                    // Agregar las cuentas de este tipo
                    tipos[tipo].forEach((nombreCuenta) => {
                        const filaCuenta = document.createElement('tr');
                        filaCuenta.innerHTML = `
                            <td class="border px-4 py-2">${tipo.split(' ')[0]}.${codigos[tipo]}</td>
                            <td class="border px-4 py-2">${nombreCuenta}</td>
                        `;
                        tablaVistaPrevia.appendChild(filaCuenta);

                        // Incrementar el código de la cuenta dentro de este tipo
                        codigos[tipo]++;
                    });
                }
            }
        }
    </script>

</x-app-layout>
