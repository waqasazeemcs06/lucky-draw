<?php

use App\Traits\ModalTrait;
use Livewire\Attributes\On;
use Livewire\Component;

new class extends Component {
    use ModalTrait;

    public bool $showingImportModal = false;

    #[On('openImportModal')]
    public function openImportModal()
    {
        $this->showingImportModal = true;
    }

    #[On('closeImportModal')]
    public function closeImportModal()
    {
        $this->showingImportModal = false;
    }
};
?>

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">

                <div class="flex justify-end mb-6 gap-3">
                    <button
                        wire:click="$dispatch('openImportModal')"
                        class="inline-flex items-center px-4 py-2 bg-green-600 text-white font-medium rounded-lg shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition">
                        Import Participants
                    </button>

                    <button
                        wire:click="$dispatch('openModal')"
                        class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white font-medium rounded-lg shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition">
                        Add Participant
                    </button>
                </div>

                @livewire('admin.participant.table')

                @if($showingModal ?? false)
                    @livewire('admin.participant.manage')
                @endif

                @if($showingImportModal ?? false)
                    @livewire('admin.participant.import')
                @endif

            </div>
        </div>
    </div>
</div>
