<?php

use App\Helpers\AlertHelper;
use App\Models\Participant;
use App\Models\Prize;
use App\Models\Winner;
use App\Traits\DeleteTrait;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Draw;

new class extends Component {
    use WithPagination;
    use DeleteTrait;

    protected string $modelClass = Participant::class;
    public string $search = '';
    public $draw_id;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Winner::with([
            'draw'  => fn($q) => $q->whereNull('deleted_at'),
            'prize' => fn($q) => $q->whereNull('deleted_at'),
            'participant',
        ])
            ->when($this->draw_id, fn($q) => $q->where('draw_id', $this->draw_id))
            ->whereHas('draw',  fn($q) => $q->whereNull('deleted_at'))
            ->whereHas('prize', fn($q) => $q->whereNull('deleted_at'))
            ->orderByDesc('id');

        // Add search filter
        if ($this->search !== '') {
            $search = trim($this->search);

            $query->where(function ($q) use ($search) {
                // Search in Prize title
                $q->whereHas('prize', function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%");
                })

                    // Search in Draw title
                    ->orWhereHas('draw', function ($q) use ($search) {
                        $q->where('title', 'like', "%{$search}%");
                    })

                    // Search in Participant fields
                    ->orWhereHas('participant', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%")
                            ->orWhere('invoice_number', 'like', "%{$search}%")
                            ->orWhere('store_name', 'like', "%{$search}%")
                            ->orWhere('store_code', 'like', "%{$search}%")
                            ->orWhere('store_address', 'like', "%{$search}%");
                    });
            });
        }

        $winners = $query->paginate(10);

        return view('components.admin.winner.⚡table', [
            'winners' => $winners,
        ]);
    }
};
?>
<div>

    <!-- Search Input -->
    <div class="p-4 border-b border-gray-200">
        <div class="relative max-w-md">
            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
            <input
                type="text"
                wire:model.live.debounce.350ms="search"
                class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                placeholder="Type to search"
            >
        </div>
    </div>

    <!-- Table -->
    <div class="shadow rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider bg-blue-100">Lucky Draw</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Store Name</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Invoice#</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider bg-green-100">Prize Won</th>
                </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                @forelse($winners as $winner)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ ($winners->currentPage() - 1) * $winners->perPage() + $loop->iteration }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 bg-blue-100">{{ $winner?->draw?->title }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $winner->participant->store_name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $winner->participant->invoice_number }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 bg-green-100">{{ $winner->prize->title }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                            No winner found.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 border-t border-gray-200">
            {{ $winners->links() }}
        </div>
    </div>

</div>
