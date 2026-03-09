@php use App\Models\Draw; @endphp
<x-guest-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    @php
        $draw = Draw::completed()->latest('id')->first();
    @endphp

    @livewire('admin.winner.table', ['draw_id' => $draw?->id])

</x-guest-layout>
