<x-app-layout>

    <!-- Load confetti before Livewire -->
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.5.1/dist/confetti.browser.min.js"></script>

    @vite(['resources/css/lucky-draw.css', 'resources/js/lucky-draw.js'])

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white-800 leading-tight">
            {{ __('Lucky Draws') }}
        </h2>
    </x-slot>

    @livewire('admin.draw.lucky-draw', ['draw_id' => request()->draw_id])

</x-app-layout>
