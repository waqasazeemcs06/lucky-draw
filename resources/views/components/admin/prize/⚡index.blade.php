<?php

use App\Traits\ModalTrait;
use Livewire\Component;

new class extends Component {
    use ModalTrait;

    public $draw_id = null;
};
?>

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">

                <div class="flex justify-end mb-6">
                    <button
                        wire:click="$dispatch('openModal')"
                        class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white font-medium rounded-lg shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition">
                        Add Prize
                    </button>
                </div>

                @livewire('admin.prize.table', ['draw_id' => $draw_id])

                @if($showingModal ?? false)
                    @livewire('admin.prize.manage', ['draw_id' => $draw_id])
                @endif

            </div>
        </div>
    </div>
</div>
