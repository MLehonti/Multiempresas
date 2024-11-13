
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
    <div class="min-h-screen flex items-center justify-center">
        <div class="bg-white p-8 rounded-lg shadow-md max-w-md w-full">
            <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">Crear Cuenta</h2>
            <form action="{{ route('subscription.storeuser') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="name" class="block text-gray-700">Nombre</label>
                    <input type="text" id="name" name="name" required class="mt-1 w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600">
                </div>
                <div class="mb-4">
                    <label for="email" class="block text-gray-700">Correo Electrónico</label>
                    <input type="email" id="email" name="email" required class="mt-1 w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600">
                </div>
                <div class="mb-4">
                    <label for="password" class="block text-gray-700">Contraseña</label>
                    <input type="password" id="password" name="password" required class="mt-1 w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600">
                </div>
                <button type="submit" class="w-full bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700">
                    Registrarse
                </button>
            </form>

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
