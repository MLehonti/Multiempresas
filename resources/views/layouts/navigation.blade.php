<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-20 items-center"> <!-- Añadido items-center -->
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-10 w-auto fill-current text-gray-600" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex items-center"> <!-- Añadido items-center -->
                    <!-- Enlace a Dashboard -->
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>

                    <!-- Enlace a Crear Empresa -->
                    <x-nav-link :href="route('empresa.create')" :active="request()->routeIs('empresa.create')">
                        {{ __('Crear Empresa') }}
                    </x-nav-link>

                    <!-- Enlace a Backups
                    <x-nav-link :href="route('backups.index')" :active="request()->routeIs('backups.index')">
                        {{ __('Backups') }}
                    </x-nav-link> -->

                    <!-- Mostrar el submenú de Recursos si existe una empresa asociada al usuario -->
                    @php
                        $empresa = Auth::user()->empresa;
                        $tieneBalance = $empresa ? \App\Models\BalanceApertura::where('empresa_id', $empresa->id)->exists() : false;
                    @endphp

                    @if ($empresa)
                        <!-- Dropdown de Recursos -->
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button class="flex items-center text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out">
                                    <div>{{ __('Recursos') }}</div>
                                    <div class="ml-1">
                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.293 9.293a1 1 0 011.414 0L10 12.586l3.293-3.293a1 1 011.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </button>
                            </x-slot>



                            <x-slot name="content">
                                <!-- Opciones dentro del submenú Recursos -->
                                <x-dropdown-link :href="route('plan-cuentas.index', ['empresa_id' => $empresa->id])">
                                    {{ __('Ver Plan de Cuentas') }}
                                </x-dropdown-link>
                                <x-dropdown-link :href="route('balance.show', ['empresa_id' => $empresa->id])">
                                    {{ __('Ver Balance de Apertura') }}
                                </x-dropdown-link>

                                <x-dropdown-link :href="route('estado_resultados.index', ['empresa_id' => $empresa->id])">
                                    {{ __('Ver Estado Resultado') }}
                                </x-dropdown-link>
                            </x-slot>



                        </x-dropdown>
                    @endif

                    <!-- <x-nav-link :href="route('bitacora.index')" :active="request()->routeIs('bitacora.index')">
                        {{ __('Bitacora') }}
                    </x-nav-link> -->
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ml-6">
                <!-- Dropdown para el nombre de usuario y perfil -->
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="flex items-center text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out">
                            <div>{{ Auth::user()->name }}</div>
                            <div class="ml-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 9.293a1 1 0 011.414 0L10 12.586l3.293-3.293a1 1 011.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <!-- Enlace al perfil -->
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Perfil') }}
                        </x-dropdown-link>

                        <!-- Enlace a Usuarios (solo visible para Admin) -->
                        @if(Auth::user()->hasRole('admin'))
                            <x-dropdown-link :href="route('users.index')">
                                {{ __('Usuarios') }}
                            </x-dropdown-link>
                        @endif

                        <!-- Enlace a Empresas (solo visible para Admin) -->
                        @if(Auth::user()->hasRole('admin'))
                            <x-dropdown-link :href="route('empresas.index')">
                                {{ __('Empresas') }}
                            </x-dropdown-link>
                        @endif


                         <!-- Enlace a cuentas (solo visible para Admin) -->
                         @if(Auth::user()->hasRole('admin'))
                            <x-dropdown-link :href="route('cuentas.index')">
                                {{ __('Ver cuentas y tipos') }}
                            </x-dropdown-link>
                        @endif



                        <!-- Enlace a bitacora (solo visible para Admin) -->
                        @if(Auth::user()->hasRole('admin'))
                            <x-dropdown-link :href="route('bitacora.index')">
                                {{ __('Ver Bitacora') }}
                            </x-dropdown-link>
                        @endif

                        <!-- Enlace a bitacora (solo visible para Admin) -->
                        @if(Auth::user()->hasRole('admin'))
                            <x-dropdown-link :href="route('backups.index')">
                                {{ __('Backups') }}
                            </x-dropdown-link>
                        @endif




                        <!-- Enlace para cerrar sesión -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Cerrar Sesion') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger para vista móvil -->
            <div class="-mr-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
                        <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Menú responsivo -->
    <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>

            @if (!Auth::user()->empresa)
                <x-responsive-nav-link :href="route('empresa.create')" :active="request()->routeIs('empresa.create')">
                    {{ __('Crear Empresa') }}
                </x-responsive-nav-link>
            @endif
        </div>

        <!-- Opciones de configuración en vista móvil -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Perfil') }}
                </x-responsive-nav-link>

                @if(Auth::user()->hasRole('admin'))
                    <x-responsive-nav-link :href="route('users.index')">
                        {{ __('Usuarios') }}
                    </x-responsive-nav-link>
                @endif

                @if(Auth::user()->hasRole('admin'))
                    <x-responsive-nav-link :href="route('empresas.index')">
                        {{ __('Empresas') }}
                    </x-responsive-nav-link>
                @endif

                @if(Auth::user()->hasRole('admin'))
                    <x-responsive-nav-link :href="route('cuentas.index')">
                        {{ __('Ver cuentas y tipos') }}
                    </x-responsive-nav-link>
                @endif

                @if(Auth::user()->hasRole('admin'))
                    <x-responsive-nav-link :href="route('bitacora.index')">
                        {{ __('Ver Bitacora') }}
                    </x-responsive-nav-link>
                @endif

                @if(Auth::user()->hasRole('admin'))
                    <x-responsive-nav-link :href="route('backups.index')">
                        {{ __('Backups') }}
                    </x-responsive-nav-link>
                @endif



                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Cerrar Sesion') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
