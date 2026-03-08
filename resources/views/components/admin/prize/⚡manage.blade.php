<?php

use App\Models\Draw;
use App\Models\Prize;
use Livewire\Attributes\On;
use Livewire\Component;

new class extends Component {
    public $draws;

    public ?int $prize_id = null;
    public $draw_id;
    public $title;
    public $description;
    public $quantity;
    public $order;

    public function mount(): void
    {
        $this->draws = Draw::active()->get();
    }

    public function submit()
    {
        $this->validate([
            'draw_id' => 'required|integer',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
            'quantity' => 'required|numeric|min:1',
            'order' => 'nullable|integer|min:0',
        ], [
            'draw_id.required' => 'The lucky draw field is required.'
        ]);

        Prize::updateOrCreate(
            ['id' => $this->prize_id],
            [
                'draw_id' => $this->draw_id,
                'title' => $this->title,
                'description' => $this->description,
                'quantity' => $this->quantity,
                'order' => $this->order,
            ]
        );

        return redirect()->route('admin.prizes.index', ['draw_id' => $this->draw_id])->with('message', 'The record has been saved successfully.');
    }

    #[On('edit')]
    public function edit($id)
    {
        $prize = Prize::findOrFail($id);

        $this->fill([
            'prize_id' => $prize->id,
            'draw_id' => $prize->draw_id,
            'title' => $prize->title,
            'description' => $prize->description,
            'quantity' => $prize->quantity,
            'order' => $prize->order,
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
                    {{ $prize_id ? 'Edit Prize' : 'Add Prize' }}
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
                <div class="grid gap-4 grid-cols-2">
                    <div class="col-span-2">
                        <label for="draw_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Lucky Draw <span class="text-red-500">*</span></label>
                        <select wire:model="draw_id" id="draw_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-600 focus:border-indigo-600 block w-full p-2.5">
                            <option selected>Select</option>
                            @foreach($this->draws as $draw)
                                <option value="{{ $draw->id }}">{{ $draw->title }}</option>
                            @endforeach
                        </select>
                        @error('draw_id') <span class="text-red-600">{{ $message }}</span> @enderror
                    </div>

                    <div class="col-span-2">
                        <label for="title" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Title <span class="text-red-500">*</span></label>
                        <input type="text" wire:model="title" id="title" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-600 focus:border-indigo-600 block w-full p-2.5" autocomplete="off">
                        @error('title') <span class="text-red-600">{{ $message }}</span> @enderror
                    </div>

                    <div class="col-span-2">
                        <label for="quantity" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Quantity <span class="text-red-500">*</span></label>
                        <input type="number" wire:model="quantity" id="quantity" min="1" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-600 focus:border-indigo-600 block w-full p-2.5">
                        @error('quantity') <span class="text-red-600">{{ $message }}</span> @enderror
                    </div>

                    <div class="col-span-2">
                        <label for="order" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Order</label>
                        <input type="number" wire:model="order" id="order" min="0" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-600 focus:border-indigo-600 block w-full p-2.5">
                        @error('order') <span class="text-red-600">{{ $message }}</span> @enderror
                    </div>

                    <div class="col-span-2">
                        <label for="description" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Description</label>
                        <textarea wire:model="description" name="description" id="description" rows="3" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-600 focus:border-indigo-600 block w-full p-2.5"></textarea>
                        @error('description') <span class="text-red-600">{{ $message }}</span> @enderror
                    </div>
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
