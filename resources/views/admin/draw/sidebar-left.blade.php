<div class="lg:col-span-3 space-y-4 order-2 lg:order-1">
    <div class="glass-card p-4">
        <h4 class="font-display text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">Session Stats</h4>
        <div class="space-y-2 text-sm">
            <div class="flex justify-between p-2 rounded bg-white/5">
                <span class="text-gray-400">Total Winners</span>
                <span class="font-display font-bold text-yellow-400">{{ count($allWinnersHistory) }}</span>
            </div>
            <div class="flex justify-between p-2 rounded bg-white/5">
                <span class="text-gray-400">Prizes</span>
                <span class="font-bold text-green-400">
                    {{ count($prizes) - collect($prizes)->filter(fn($p) => \App\Models\Winner::where('prize_id', $p->id)->count() < $p->quantity)->count() }}/{{ count($prizes) }}
                </span>
            </div>
        </div>
    </div>

    <div class="glass-card p-4 max-h-[50vh] overflow-y-auto">
        <h4 class="font-display text-xs font-bold text-gray-400 uppercase tracking-wider mb-3 flex justify-between">
            <span>Recent Winners</span>
            <span class="text-gray-500">{{ count($allWinnersHistory) }}</span>
        </h4>

        <div class="space-y-2">
            @foreach($allWinnersHistory as $winner)
                <div class="flex items-center gap-2 p-2 rounded text-xs border"
                     style="border-color: {{ $winner['prize_color'] }}30; background: {{ $winner['prize_color'] }}10;">
                    <div class="flex-1 min-w-0">
                        <div class="font-mono text-lg font-bold truncate" style="color: {{ $winner['prize_color'] }}">{{ $winner['invoice'] }}</div>
                        <div class="text-gray-500 text-[12px]">{{ $winner['winner_name'] }}</div>
                        <div class="font-mono text-sm font-bold truncate" style="color: {{ $winner['prize_color'] }}">Won {{ $winner['prize_title'] }}</div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
