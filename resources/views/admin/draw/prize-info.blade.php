<div class="mb-4" style="--current-color: {{ $prizeColors[$currentPrize->id] }}">
    <div class="glass-card p-4 relative overflow-hidden">
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
            <div class="flex-1">
                <div class="flex items-center gap-2 mb-1">
                    <span class="px-3 py-1 rounded-full text-xs font-bold uppercase text-white"
                          style="background: {{ $prizeColors[$currentPrize->id] }}">
                        @if($isLastPrize) Final Prize @else Current Prize @endif
                    </span>
                    @if($isLastPrize)
                        <span class="text-xs text-yellow-400 font-bold">LAST ONE!</span>
                    @endif
                </div>
                <h2 class="font-display text-2xl font-black"
                    style="color: {{ $prizeColors[$currentPrize->id] }}; text-shadow: 0 0 20px {{ $prizeColors[$currentPrize->id] }}80;">
                    {{ $currentPrize->title }}
                </h2>
            </div>

            <div class="flex items-center gap-4">
                <div class="text-center">
                    <div class="relative w-20 h-20">
                        <div class="absolute inset-0 flex items-center justify-center font-display font-bold text-lg">
                            {{ count($winnersForCurrent) }}/{{ $totalSlots }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-3">
            <div class="h-2 bg-white/10 rounded-full overflow-hidden">
                <div class="h-full rounded-full transition-all duration-500"
                     style="width: {{ $totalSlots > 0 ? (count($winnersForCurrent) / $totalSlots) * 100 : 0 }}%; background: {{ $prizeColors[$currentPrize->id] }};"></div>
            </div>
        </div>
    </div>
</div>
