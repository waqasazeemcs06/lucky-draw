<?php

use App\Helpers\AlertHelper;
use App\Models\Prize;
use App\Traits\DeleteTrait;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Draw;

new class extends Component {
    use WithPagination;
    use DeleteTrait;

    protected string $modelClass = Prize::class;

    public $draw_id;

    public function render()
    {
        $prizes = Prize::has('draw')
            ->with('draw')
            ->when($this->draw_id, function (Builder $query) {
                $query->where('draw_id', $this->draw_id);
            })
            ->orderByDesc('id')
            ->paginate(10);

        return view('components.admin.prize.⚡table', [
            'prizes' => $prizes,
        ]);
    }
};
?>

<div class="bg-white shadow rounded-lg overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
            <tr>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider bg-blue-100">Lucky Draw</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prize</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order</th>
                <th scope="col"
                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase first:pl-3 last:pr-3 bg-slate-100 first:rounded-l last:rounded-r last:pl-5 last:sticky last:right-0">
                    Actions
                </th>
            </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
            @forelse($prizes as $prize)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $prize->id }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 bg-blue-100">{{ $prize?->draw?->title }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $prize->title }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $prize->description }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $prize->quantity }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $prize->order }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 last:border-none first:pl-3 last:pr-3 last:bg-gradient-to-r last:from-transparent last:to-white last:to-[12px] last:sticky last:right-0">
                        <button wire:click="$dispatch('editRecord', { id: {{ $prize->id }} })" class="text-blue-600 hover:text-blue-900">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                        </button>
                        <button wire:click="deleteConfirm({{ $prize->id }})" class="text-red-600 hover:text-red-900">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center text-gray-500">No prizes found.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="px-6 py-4 border-t border-gray-200">
        {{ $prizes->links() }}
    </div>
</div>
