<!-- resources/views/components/button.blade.php -->
@props(['type' => 'button'])

<button {{ $attributes->merge(['class' => 'bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg focus:outline-none focus:shadow-outline']) }} type="{{ $type }}">
    {{ $slot }}
</button>
