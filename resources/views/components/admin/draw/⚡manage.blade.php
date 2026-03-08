<?php

use App\Helpers\AlertHelper;
use Livewire\Attributes\On;
use Livewire\Component;
use App\Models\Draw;

new class extends Component {
    public ?int $draw_id = null;
    public string $title = '';
    public string $draw_date = '';

    protected $rules = [
        'title' => 'required|string|max:255',
        'draw_date' => 'required|date',
    ];

    public function submit()
    {
        $this->validate();

        Draw::updateOrCreate(
            ['id' => $this->draw_id],
            [
                'title' => $this->title,
                'draw_date' => $this->draw_date,
            ]
        );

        return redirect()->route('admin.draws.index')->with('message', 'The record has been saved successfully.');
    }

    #[On('edit')]
    public function edit($id)
    {
        $draw = Draw::findOrFail($id);

        $this->fill([
            'draw_id' => $draw->id,
            'title' => $draw->title,
            'draw_date' => $draw->getRawOriginal('draw_date'),
        ]);
    }
};
?>
<div class="fixed inset-0 z-[100] flex items-center justify-center bg-black/60 backdrop-blur-sm transition-opacity duration-300" wire:ignore.self>
    <div class="relative w-full max-w-2xl mx-4 bg-white rounded-xl shadow-2xl transform transition-all duration-300 scale-100"
         wire:click.stop>

        <div class="p-6 space-y-6">
            <div class="flex items-center justify-between border-b pb-4">
                <h3 class="text-xl font-semibold text-gray-900">
                    {{ $draw_id ? 'Edit Lucky Draw' : 'Add Lucky Draw' }}
                </h3>

                <button
                    type="button"
                    wire:click="$dispatch('closeModal')"
                    class="text-gray-500 hover:text-gray-700 rounded-full p-1 hover:bg-gray-100 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <form wire:submit="submit" class="space-y-6">
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-900">Title <span class="text-red-500">*</span></label>
                    <input type="text" wire:model.live="title" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5" autocomplete="off">
                    @error('title')<span class="text-red-600 text-sm mt-1">{{ $message }}</span>@enderror
                </div>
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-900">Draw Date & Time <span class="text-red-500">*</span></label>
                    <input type="datetime-local" wire:model.live="draw_date" min="{{ today()->format('Y-m-d') }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5">
                    @error('draw_date')<span class="text-red-600 text-sm mt-1">{{ $message }}</span>@enderror
                </div>
                <div class="flex justify-end pt-4 border-t gap-3">
                    <button type="button" wire:click="$dispatch('closeModal')" class="text-body bg-neutral-secondary-medium box-border border border-default-medium hover:bg-neutral-tertiary-medium hover:text-heading focus:ring-4 focus:ring-neutral-tertiary shadow-xs font-medium leading-5 rounded-lg text-sm px-4 py-2.5 focus:outline-none">
                        Cancel
                    </button>
                    <button type="submit" class="px-6 py-2.5 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 focus:ring-4 focus:ring-indigo-300 transition">
                        Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
