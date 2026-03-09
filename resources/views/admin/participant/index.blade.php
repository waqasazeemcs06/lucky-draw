<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white-800 leading-tight">
            {{ __('Lucky Draw Participants') }}
        </h2>
    </x-slot>

    @livewire('admin.participant.index', ['draw_id' => request()->draw_id])

</x-app-layout>
