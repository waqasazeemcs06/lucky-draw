<?php

use App\Helpers\AlertHelper;
use App\Traits\DeleteTrait;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Draw;

new class extends Component {
    use WithPagination;
    use DeleteTrait;

    protected string $modelClass = Draw::class;

    public function render()
    {
        return view('components.admin.draw.table', [
            'draws' => Draw::withSum('prizes', 'quantity')
                ->withCount('participants')
                ->orderByDesc('id')
                ->paginate(10),
        ]);
    }
};
?>

<div class="bg-white shadow rounded-lg overflow-hidden">
    <div class="overflow-x-auto" @record-saved="$refresh">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
            <tr>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Winners</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider text-right">Prize Count</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider text-right">Participants Count</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lucky Draw Date</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase first:pl-3 last:pr-3 bg-slate-100 first:rounded-l last:rounded-r last:pl-5 last:sticky last:right-0">Actions</th>
            </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
            @forelse($draws as $draw)
                <tr class="hover:bg-gray-50" wire:key="draw-{{ $draw->id }}">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ ($draws->currentPage() - 1) * $draws->perPage() + $loop->iteration }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-green-600">
                        <a href="{{ route('admin.draw.luck-draw', ['draw_id' => $draw->id]) }}">{{ $draw->title }}</a>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-green-600">
                        <a href="{{ route('admin.draw.winners', ['draw_id' => $draw->id]) }}">See List</a>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-lg font-medium text-green-600 text-right"><a href="{{ route('admin.prizes.index', ['draw_id' => $draw->id]) }}">{{ number_format($draw->prizes_sum_quantity) }}</a></td>
                    <td class="px-6 py-4 whitespace-nowrap text-lg font-medium text-green-600 text-right"><a href="{{ route('admin.participants.index', ['draw_id' => $draw->id]) }}">{{ number_format($draw->participants_count) }}</a></td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $draw->draw_date }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        @php
                            $color = $draw->getRawOriginal('status') == Draw::STATUS_ACTIVE ? 'green' : 'indigo';
                        @endphp
                        <span class="inline-flex items-center rounded-md bg-{{ $color }}-50 text-{{ $color }}-700">{{ $draw->status }}</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 last:border-none first:pl-3 last:pr-3 last:bg-gradient-to-r last:from-transparent last:to-white last:to-[12px] last:sticky last:right-0">
                        <button wire:click="$dispatch('editRecord', { id: {{ $draw->id }} })" class="text-blue-600 hover:text-blue-900">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                        </button>
                        <button wire:click="deleteConfirm({{ $draw->id }})" class="text-red-600 hover:text-red-900">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center text-gray-500">No luck draws found.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="px-6 py-4 border-t border-gray-200">
        {{ $draws->links() }}
    </div>
</div>
