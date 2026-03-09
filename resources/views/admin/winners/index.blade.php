<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white-800 leading-tight">
            {{ __('Luck Draw Winners') }}
        </h2>
    </x-slot>

    @livewire('admin.winner.index', ['draw_id' => request()->draw_id])

</x-app-layout>
