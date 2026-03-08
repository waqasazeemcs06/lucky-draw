<?php

use App\Models\Draw;
use App\Models\Participant;
use App\Models\Prize;
use Livewire\Attributes\On;
use Livewire\Component;

new class extends Component {
    public $draws;

    public ?int $participant_id = null;
    public $draw_id;
    public $name;
    public $store_code;
    public $store_name;
    public $invoice_number;
    public $store_address;

    public function mount(): void
    {
        $this->draws = Draw::active()->get();
    }

    public function submit()
    {
        $this->validate([
            'draw_id' => 'required|integer',
            'name' => 'required|string|max:255',
            'store_code' => 'required|string|max:255',
            'store_name' => 'required|string|max:255',
            'invoice_number' => 'required|string|max:255',
            'store_address' => 'required|string|max:255',
        ], [
            'draw_id.required' => 'The lucky draw field is required.'
        ]);

        Participant::updateOrCreate(
            ['id' => $this->participant_id],
            [
                'draw_id' => $this->draw_id,
                'name' => $this->name,
                'store_code' => $this->store_code,
                'store_name' => $this->store_name,
                'invoice_number' => $this->invoice_number,
                'store_address' => $this->store_address,
            ]
        );

        return redirect()->route('admin.participants.index')->with('message', 'The record has been saved successfully.');
    }

    #[On('edit')]
    public function edit($id)
    {
        $participant = Participant::findOrFail($id);

        $this->fill([
            'participant_id' => $participant->id,
            'draw_id' => $participant->draw_id,
            'name' => $participant->name,
            'store_code' => $participant->store_code,
            'store_name' => $participant->store_name,
            'invoice_number' => $participant->invoice_number,
            'store_address' => $participant->store_address,
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
                    {{ $participant_id ? 'Edit Participant' : 'Add Participant' }}
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
                        <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Full Name <span class="text-red-500">*</span></label>
                        <input type="text" wire:model="name" id="name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-600 focus:border-indigo-600 block w-full p-2.5" autocomplete="off">
                        @error('name') <span class="text-red-600">{{ $message }}</span> @enderror
                    </div>

                    <div class="col-span-2 sm:col-span-1">
                        <label for="store_code" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Store Code <span class="text-red-500">*</span></label>
                        <input type="text" wire:model="store_code" id="store_code" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-600 focus:border-indigo-600 block w-full p-2.5" autocomplete="off">
                        @error('store_code') <span class="text-red-600">{{ $message }}</span> @enderror
                    </div>

                    <div class="col-span-2 sm:col-span-1">
                        <label for="store_name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Store Name <span class="text-red-500">*</span></label>
                        <input type="text" wire:model="store_name" id="store_name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-600 focus:border-indigo-600 block w-full p-2.5" autocomplete="off">
                        @error('store_name') <span class="text-red-600">{{ $message }}</span> @enderror
                    </div>

                    <div class="col-span-2 sm:col-span-2">
                        <label for="invoice_number" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Invoice Number <span class="text-red-500">*</span></label>
                        <input type="text" wire:model="invoice_number" id="invoice_number" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-600 focus:border-indigo-600 block w-full p-2.5" autocomplete="off">
                        @error('invoice_number') <span class="text-red-600">{{ $message }}</span> @enderror
                    </div>

                    <div class="col-span-2">
                        <label for="store_address" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Store Address <span class="text-red-500">*</span></label>
                        <textarea wire:model="store_address" id="store_address" rows="3" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-600 focus:border-indigo-600 block w-full p-2.5"></textarea>
                        @error('store_address') <span class="text-red-600">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="flex justify-end pt-4 border-t gap-3">
                    <button type="button" wire:click="$dispatch('closeModal')"
                            class="text-body bg-neutral-secondary-medium box-border border border-default-medium hover:bg-neutral-tertiary-medium hover:text-heading focus:ring-4 focus:ring-neutral-tertiary shadow-xs font-medium leading-5 rounded-lg text-sm px-4 py-2.5 focus:outline-none">
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
