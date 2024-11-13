<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenido a Nuestra Plataforma</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">

    <!-- Navegación -->
    <nav class="bg-white border-b-2 border-purple-500">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo y Menú de Navegación Izquierda -->
                <div class="flex items-center">
                    <a href="/" class="text-2xl font-bold text-purple-600 hover:text-purple-700">NimbusBooks</a>
                    <div class="hidden md:flex space-x-6 ml-10">
                        <a href="/"
                            class="text-gray-800 hover:text-purple-600 font-medium px-3 py-2 rounded-md">
                            Inicio
                        </a>
                        <a href="{{route('subscription.index')}}"
                            class="text-gray-800 hover:text-purple-600 font-medium px-3 py-2 rounded-md">
                            Precio
                        </a>
                        <a href="#"
                            class="text-gray-800 hover:text-purple-600 font-medium px-3 py-2 rounded-md">
                            Funcionalidades
                        </a>
                    </div>
                </div>

                <!-- Botón de Menú para Móvil -->
                <div class="md:hidden flex items-center">
                    <button id="mobile-menu-button" class="text-gray-800 hover:text-purple-600 focus:outline-none">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16m-7 6h7"></path>
                        </svg>
                    </button>
                </div>

                <!-- Botones de Registro y Login -->
                <div class="hidden md:flex space-x-4">
                    <a href="#"
                        class="text-gray-800 hover:text-purple-600 font-medium px-3 py-2 rounded-md">
                        Log in
                    </a>
                </div>
            </div>
        </div>

        <!-- Menú desplegable para móvil -->
        <div id="mobile-menu" class="hidden md:hidden bg-white border-t border-purple-500">
            <div class="px-2 pt-2 pb-3 space-y-1">
                <a href="#"
                    class="block text-gray-800 hover:text-purple-600 font-medium px-3 py-2 rounded-md">
                    Inicio
                </a>
                <a href="#subscriptions"
                    class="block text-gray-800 hover:text-purple-600 font-medium px-3 py-2 rounded-md">
                    Precio
                </a>
                <a href="#"
                    class="block text-gray-800 hover:text-purple-600 font-medium px-3 py-2 rounded-md">
                    Funcionalidades
                </a>
                <a href="#"
                    class="block text-gray-800 hover:text-purple-600 font-medium px-3 py-2 rounded-md">
                    Log in
                </a>
            </div>
        </div>
    </nav>

    <!-- Sección de Inicio -->
    <div class="bg-gray-100 py-16">
        <div class="max-w-7xl mx-auto text-center px-6 lg:px-8">
                <a href="/" class="text-5xl font-bold text-purple-600 hover:text-purple-700">NimbusBooks</a>
        </div>
    </div>

    <!-- Sección de Suscripciones -->
    <div id="subscriptions" class="py-5 bg-white">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="text-center mb-8">
                <h2 class="text-3xl font-bold text-gray-900">Nuestras Suscripciones</h2>
                <p class="text-gray-600 mt-2">
                    Elige el plan que mejor se adapte a tus necesidades.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Plan Mensual -->
                <div class="bg-white p-6 rounded-lg shadow">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">Mensual</h3>
                    <p class="text-gray-600 mb-6">Precio: 10$/mes</p>
                    <a href="{{route('subscription.registrar')}}"
                        class="block bg-blue-500 text-white px-6 py-3 rounded-lg shadow hover:bg-blue-600 text-center">
                        Seleccionar Plan
                    </a>
                </div>

                <!-- Plan Trimestral -->
                <div class="bg-white p-6 rounded-lg shadow">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">Trimestral</h3>
                    <p class="text-gray-600 mb-6">Precio: 27$/trimestre</p>
                    <a href="{{route('subscription.registrar')}}"
                        class="block bg-blue-500 text-white px-6 py-3 rounded-lg shadow hover:bg-blue-600 text-center">
                        Seleccionar Plan
                    </a>
                </div>

                <!-- Plan Semestral -->
                <div class="bg-white p-6 rounded-lg shadow">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">Semestral</h3>
                    <p class="text-gray-600 mb-6">Precio: 50$/semestre</p>
                    <a href="{{route('subscription.registrar')}}"
                        class="block bg-blue-500 text-white px-6 py-3 rounded-lg shadow hover:bg-blue-600 text-center">
                        Seleccionar Plan
                    </a>
                </div>

                <!-- Plan Anual -->
                <div class="bg-white p-6 rounded-lg shadow">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">Anual</h3>
                    <p class="text-gray-600 mb-6">Precio: 90$/año</p>
                    <a href="{{route('subscription.registrar')}}"
                        class="block bg-blue-500 text-white px-6 py-3 rounded-lg shadow hover:bg-blue-600 text-center">
                        Seleccionar Plan
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Toggle del menú móvil
        const menuButton = document.getElementById('mobile-menu-button');
        const mobileMenu = document.getElementById('mobile-menu');

        menuButton.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });
    </script>

</body>

</html>
