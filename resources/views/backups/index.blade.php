<x-app-layout>
    <div class="container mx-auto p-6">
        @if (session('success'))
            <div class="bg-green-600 text-black p-4 rounded-lg shadow-md mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if ($backups->isNotEmpty())
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white border border-gray-200 rounded-lg shadow-lg">
                    <thead>
                        <tr class="bg-gray-100 text-gray-700">
                            <th class="py-3 px-4 border-b text-left">Backup</th>
                            <th class="py-3 px-4 border-b text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($backups as $backup)
                            <tr class="hover:bg-gray-50 transition duration-200">
                                <td class="py-3 px-4 border-b">{{ htmlspecialchars(basename($backup)) }}</td>
                                <td class="py-3 px-4 border-b text-right flex justify-end space-x-2">
                                    <!-- Botón de descargar backup -->
                                    <form action="{{ route('backups.download', ['backup' => basename($backup)]) }}" method="GET">
                                        <button type="submit" class="bg-green-600 text-black px-4 py-2 rounded-lg hover:bg-green-700 transition duration-200">
                                            Descargar
                                        </button>
                                    </form>

                                    <!-- Botón de eliminar backup -->
                                    <form action="{{ route('backups.delete', ['backup' => basename($backup)]) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition duration-200" onclick="return confirm('¿Estás seguro de que quieres eliminar este backup?')">
                                            Eliminar
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-gray-500 text-center mt-4">No hay backups disponibles.</p>
        @endif
    </div>
</x-app-layout>
