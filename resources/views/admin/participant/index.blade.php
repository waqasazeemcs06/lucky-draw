<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Lucky Draw Participants') }}
        </h2>
    </x-slot>

    @livewire('admin.participant.index')

</x-app-layout>
