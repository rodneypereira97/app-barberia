@props(['header'])

<x-layouts.app>
    <x-slot name="header">
        {{ $header ?? '' }}
    </x-slot>

    {{ $slot }}
</x-layouts.app>
