<div class="lg:col-span-4 order-3">
    <div class="sticky top-20 space-y-4">
        <div class="glass-card p-5 border-2" style="border-color: {{ $currentPrize ? $prizeColors[$currentPrize->id] : '#bc13fe' }}40;">
            <div class="text-center">
                <h3 class="font-display text-sm font-bold mb-4 text-white/90">Draw Control</h3>

                @if($currentPrize && !$showNextPrizeButton && $remainingWinnersToSelect > 0)
                    @include('admin.draw.controls-rolling')
                @elseif($showNextPrizeButton && !$isLastPrize)
                    @include('admin.draw.controls-next-prize')
                @elseif($remainingWinnersToSelect == 0 && $isLastPrize)
                    @include('admin.draw.controls-finish')
                @endif
            </div>
        </div>

        @include('admin.draw.legend')
    </div>
</div>
