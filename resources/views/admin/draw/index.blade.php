<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Lucky Draws') }}
        </h2>
    </x-slot>

    @livewire('admin.draw.index')

</x-app-layout>
