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
                        <a href="#"
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

                <!-- Botones de Registro y Login -->
                <div class="hidden md:flex space-x-4">
                    <a href="{{ route('login') }}"
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
                <a href="{{route('subscription.index')}}"
                    class="block text-gray-800 hover:text-purple-600 font-medium px-3 py-2 rounded-md">
                    Precio
                </a>
                <a href="#"
                    class="block text-gray-800 hover:text-purple-600 font-medium px-3 py-2 rounded-md">
                    Funcionalidades
                </a>
                <a href="{{ route('login') }}"
                    class="block text-gray-800 hover:text-purple-600 font-medium px-3 py-2 rounded-md">
                    Log in
                </a>
            </div>
        </div>
    </nav>

    <!-- Sección de Inicio -->
    <div class="bg-gray-100 py-16">
        <div class="max-w-7xl mx-auto text-center px-6 lg:px-8">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">
                Bienvenido a Nuestra Plataforma
            </h1>
            <p class="text-lg text-gray-600 mb-8">
                Explora nuestras increíbles características y descubre cómo podemos ayudarte a mejorar tu experiencia.
            </p>
            <a href="{{route('subscription.index')}}" class="bg-blue-500 text-white px-6 py-3 rounded-lg shadow hover:bg-blue-600">
                Ver Suscripciones
            </a>
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
