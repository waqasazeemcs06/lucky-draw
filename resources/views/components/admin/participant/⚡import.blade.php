<?php

use App\Imports\ParticipantsImport;
use App\Models\Draw;
use App\Models\Participant;
use Illuminate\Support\Collection;
use Livewire\Component;
use Livewire\WithFileUploads;
use PhpOffice\PhpSpreadsheet\IOFactory;

new class extends Component {
    use WithFileUploads;

    public $draws;
    public $draw_id;
    public $file;

    public function mount(): void
    {
        $this->draws = Draw::active()->get();
    }

    public function import()
    {
        $this->validate([
            'draw_id' => 'required|integer',
            'file' => 'required|file|mimes:csv,xls,xlsx|max:10240',
        ], [
            'draw_id.required' => 'The lucky draw field is required.'
        ]);

        try {
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($this->file->getRealPath());
            $worksheet   = $spreadsheet->getActiveSheet();
            $headers     = array_map('strtolower', array_map('trim', $worksheet->rangeToArray('A1:Z1')[0] ?? []));

            $required = [
                'dsr name',
                'store code',
                'store name',
                'invoice number',
                'store address',
                'no of coupons',
            ];

            $missing = array_diff($required, $headers);

            if ($missing) {
                $this->addError('file', 'Missing required columns: ' . implode(', ', $missing) . '. Please check the file format.');
                return;
            }

        } catch (\Throwable $e) {
            $this->addError('file', 'Unable to read the file. Make sure it is a valid Excel/CSV file.');
            return;
        }

        \Maatwebsite\Excel\Facades\Excel::import(
            new ParticipantsImport($this->draw_id),
            $this->file
        );

        return redirect()->route('admin.participants.index')->with('message', 'The participants have been imported successfully.');
    }
}
?>
<div class="fixed inset-0 z-[100] flex items-center justify-center bg-black/60 backdrop-blur-sm transition-opacity duration-300" wire:ignore.self>
    <div class="relative w-full max-w-2xl mx-4 bg-white rounded-xl shadow-2xl transform transition-all duration-300 scale-100"
         wire:click.stop>
        <div class="p-6 space-y-6">
            <div class="flex items-center justify-between border-b pb-4">
                <h3 class="text-xl font-semibold text-gray-900">
                    Import Participants
                </h3>
                <button
                    type="button"
                    wire:click="$dispatch('closeImportModal')"
                    class="text-gray-500 hover:text-gray-700 rounded-full p-1 hover:bg-gray-100 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <form
                wire:submit="import"
                class="space-y-6"
                x-data="{
                        isUploading: false,
                        progress: 0
                    }"
                x-on:livewire-upload-start="isUploading = true"
                x-on:livewire-upload-finish="isUploading = false"
                x-on:livewire-upload-error="isUploading = false"
                x-on:livewire-upload-progress="progress = $event.detail.progress"
            >

                <div class="col-span-2">
                    <label for="draw_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Lucky Draw <span class="text-red-500">*</span></label>
                    <select wire:model="draw_id" id="draw_id"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-600 focus:border-indigo-600 block w-full p-2.5">
                        <option selected>Select</option>
                        @foreach($this->draws as $draw)
                            <option value="{{ $draw->id }}">{{ $draw->title }}</option>
                        @endforeach
                    </select>
                    @error('draw_id') <span class="text-red-600">{{ $message }}</span> @enderror
                </div>

                <div class="col-span-2">
                    <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Upload Participants File (CSV or Excel) <span class="text-red-500">*</span></label>
                    <input type="file" wire:model="file" class="bg-gray-50 border border-gray-300 text-sm rounded-lg block w-full p-2.5">
                    @error('file')<span class="text-red-600">{{ $message }}</span>@enderror

                    <!-- Progress Bar -->
                    <div x-show="isUploading" class="mt-3">
                        <div class="w-full bg-gray-200 rounded-full h-2 overflow-hidden">
                            <div
                                class="bg-indigo-600 h-2 rounded-full transition-all duration-300"
                                :style="`width: ${progress}%`"
                            ></div>
                        </div>
                        <p class="text-xs text-gray-600 mt-1">
                            Uploading... <span x-text="progress"></span>%
                        </p>
                    </div>
                </div>

                <div class="flex justify-end pt-4 border-t gap-3">
                    <button type="button" wire:click="$dispatch('closeImportModal')"
                            class="text-body bg-neutral-secondary-medium box-border border border-default-medium hover:bg-neutral-tertiary-medium hover:text-heading focus:ring-4 focus:ring-neutral-tertiary shadow-xs font-medium leading-5 rounded-lg text-sm px-4 py-2.5 focus:outline-none">
                        Cancel
                    </button>
                    <button
                        type="submit"
                        wire:loading.attr="disabled"
                        wire:target="import"
                        :disabled="isUploading || !@js($this->file)"
                        class="px-5 py-2.5 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 focus:ring-4 focus:ring-green-300 transition disabled:opacity-50 disabled:cursor-not-allowed">

                        <!-- Normal State -->
                        <span wire:loading.remove wire:target="import">
                            Import Now
                        </span>

                        <!-- Import Processing State -->
                        <span wire:loading wire:target="import" class="flex items-center gap-2">
                            <svg class="animate-spin h-5 w-5 text-white inline-block" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                      d="M4 12a8 8 0 018-8v8z"></path>
                            </svg>
                            Importing...
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
