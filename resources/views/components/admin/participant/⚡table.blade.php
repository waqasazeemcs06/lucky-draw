<?php

use App\Helpers\AlertHelper;
use App\Models\Participant;
use App\Models\Prize;
use App\Traits\DeleteTrait;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Draw;

new class extends Component {
    use WithPagination;
    use DeleteTrait;

    protected string $modelClass = Participant::class;
    public string $search = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Participant::has('draw')
            ->with('draw')
            ->orderByDesc('id');

        // Add search filter
        if ($this->search !== '') {
            $search = trim($this->search);

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('invoice_number', 'like', "%{$search}%")
                    ->orWhere('store_name', 'like', "%{$search}%")
                    ->orWhere('store_code', 'like', "%{$search}%");
                // → you can add more fields: email, phone, etc.
            });
        }

        $participants = $query->paginate(10);

        return view('components.admin.participant.⚡table', [
            'participants' => $participants,
        ]);
    }
};
?>
<div>

    <!-- Search Input -->
    <div class="p-4 bg-white border-b border-gray-200">
        <div class="relative max-w-md">
            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
            <input
                type="text"
                wire:model.live.debounce.350ms="search"
                class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                placeholder="Search by name, invoice#, store name or code..."
            >
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lucky Draw</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Invoice#</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Store Code</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Store Name</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Store Address</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider first:pl-3 last:pr-3 bg-slate-100 first:rounded-l last:rounded-r last:pl-5 last:sticky last:right-0">Actions</th>
                </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                @forelse($participants as $participant)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $participant->id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $participant?->draw?->title }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $participant->invoice_number }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $participant->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $participant->store_code }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $participant->store_name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $participant->store_address }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 last:border-none first:pl-3 last:pr-3 last:bg-gradient-to-r last:from-transparent last:to-white last:to-[12px] last:sticky last:right-0">
                            <button wire:click="$dispatch('editRecord', { id: {{ $participant->id }} })" class="text-blue-600 hover:text-blue-900">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </button>
                            <button wire:click="deleteConfirm({{ $participant->id }})" class="text-red-600 hover:text-red-900 ml-3">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                            No participants found matching "{{ $search }}".
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 border-t border-gray-200">
            {{ $participants->links() }}
        </div>
    </div>

</div>
