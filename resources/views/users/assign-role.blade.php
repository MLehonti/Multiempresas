{{-- resources/views/users/assign-role.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Asignar Rol a ') . $user->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <!-- Mensajes de éxito o error -->
                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                @endif

                @if(session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <span class="block sm:inline">{{ session('error') }}</span>
                    </div>
                @endif

                <!-- Mostrar el rol actual del usuario -->
                @if($user->roles->isNotEmpty())
                    <div class="mb-4 p-4 bg-yellow-100 border border-gray-300 rounded-lg">
                        <strong>Rol Actual:</strong>
                        <span class="text-blue-600">{{ $user->roles->pluck('name')->join(', ') }}</span>
                    </div>
                @else
                    <div class="mb-4 p-4 bg-yellow-100 border border-gray-300 rounded-lg">
                        <strong>Rol Actual:</strong>
                        <span class="text-red-600">No tiene ningún rol asignado.</span>
                    </div>
                @endif

                <!-- Formulario para asignar rol -->
                <form action="{{ route('users.store-role', $user->id) }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="role">
                            {{ __('Selecciona un Rol') }}
                        </label>
                        <select name="role" id="role" class="form-control w-full border-gray-300 rounded-lg">
                            @foreach($roles as $role)
                                <option value="{{ $role->name }}" @if($user->roles->contains($role)) selected @endif>
                                    {{ $role->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Botón para guardar cambios -->
                    <div class="flex items-center justify-center">
                        <button style="background-color: #38a169; color: white; padding: 0.75rem 1.5rem; border-radius: 0.5rem; margin-top: 1rem; ">
                            {{ __('Asignar Rol') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
