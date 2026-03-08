<div class="glass-card p-4">
    <h4 class="text-xs font-bold text-gray-400 uppercase mb-2">Prizes</h4>
    <div class="space-y-1.5">
        @foreach($prizes as $prize)
            @php
                $isCompleted = \App\Models\Winner::where('prize_id', $prize->id)->count() >= $prize->quantity;
                $isCurrent = $currentPrize && $currentPrize->id === $prize->id;
            @endphp
            <div class="flex items-center gap-2 text-xs {{ $isCompleted ? 'opacity-50' : '' }} {{ $isCurrent ? 'font-bold' : '' }}">
                <div class="w-2.5 h-2.5 rounded-full {{ $isCompleted ? 'bg-gray-500' : '' }}"
                     style="{{ !$isCompleted ? 'background: ' . $prizeColors[$prize->id] . '; box-shadow: 0 0 8px ' . $prizeColors[$prize->id] : '' }}"></div>
                <span class="text-gray-300 truncate flex-1">{{ \Illuminate\Support\Str::limit($prize->title, 12) }}</span>
                <span class="{{ $isCompleted ? 'text-green-400' : 'text-gray-500' }}">
                    {{ collect($allWinnersHistory)->where('prize_id', $prize->id)->count() }}/{{ $prize->quantity }}
                    @if($isCompleted) ✓ @endif
                </span>
            </div>
        @endforeach
    </div>
</div>
