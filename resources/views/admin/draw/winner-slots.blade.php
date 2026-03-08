<div class="lg:col-span-5 order-1 lg:order-2 flex flex-col">
    @if($currentPrize)
        <div class="flex items-center justify-between mb-3">
            <h3 class="font-display text-lg font-bold flex items-center gap-2">
                <span class="w-2 h-2 rounded-full animate-pulse" style="background: {{ $prizeColors[$currentPrize->id] }}"></span>
                Winner Slots
            </h3>
            @if(!$showNextPrizeButton && $remainingWinnersToSelect > 0)
                <span class="px-2 py-1 rounded text-xs font-bold"
                      style="background: {{ $prizeColors[$currentPrize->id] }}20; color: {{ $prizeColors[$currentPrize->id] }}; border: 1px solid {{ $prizeColors[$currentPrize->id] }};">
                    {{ $remainingWinnersToSelect }} Left
                </span>
            @elseif($remainingWinnersToSelect == 0 && $isLastPrize)
                <span class="px-2 py-1 rounded text-xs font-bold bg-green-500/20 text-green-400 border border-green-500">
                    All Done!
                </span>
            @endif
        </div>

        <div id="winners-container" class="winners-scroll-container space-y-3">
            @foreach($winnersForCurrent as $index => $winner)
                @php
                    $boxColor = $prizeColors[$winner['prize_id']] ?? '#00f3ff';
                    $hex = ltrim($boxColor, '#');
                    $r = hexdec(substr($hex, 0, 2));
                    $g = hexdec(substr($hex, 2, 2));
                    $b = hexdec(substr($hex, 4, 2));
                    $rgba = "rgba($r, $g, $b, 0.3)";
                    $displayNumber = count($winnersForCurrent) - $index;
                @endphp
                <div id="winner-box-{{ $index }}"
                     wire:key="winner-{{ $currentPrize->id }}-{{ $winner['id'] ?? $index }}"
                     class="winner-box filled {{ $index === 0 ? 'highlight-winner' : '' }}"
                     style="--box-color: {{ $boxColor }}; --box-color-alpha: {{ $rgba }};">

                    <div class="winner-number">{{ $displayNumber }}</div>

                    <div class="winner-details">
                        <div class="winner-invoice" style="color: {{ $boxColor }}">
                            {{ $winner['invoice_number'] }}
                        </div>
                        @if($winner['name'])
                            <div class="winner-name">{{ $winner['name'] }}</div>
                        @endif
                        <div class="winner-time">Won at {{ $winner['won_at'] }}</div>
                    </div>

                    <div class="winner-status"></div>

                    <div class="absolute -right-10 -top-10 w-32 h-32 rounded-full opacity-20 blur-3xl pointer-events-none"
                         style="background: {{ $boxColor }};"></div>
                </div>
            @endforeach

            @if($remainingWinnersToSelect > 0)
                @foreach(range(1, $remainingWinnersToSelect) as $i)
                    @php
                        $emptyNumber = $remainingWinnersToSelect - $i + 1;
                        $boxColor = $prizeColors[$currentPrize->id];
                        $hex = ltrim($boxColor, '#');
                        $r = hexdec(substr($hex, 0, 2));
                        $g = hexdec(substr($hex, 2, 2));
                        $b = hexdec(substr($hex, 4, 2));
                        $rgba = "rgba($r, $g, $b, 0.3)";
                    @endphp
                    <div wire:key="empty-{{ $currentPrize->id }}-{{ $i }}"
                         class="winner-box empty"
                         style="--box-color: {{ $boxColor }}; --box-color-alpha: {{ $rgba }};">

                        <div class="winner-details">
                            <div class="text-gray-500 text-base italic">Waiting for winner...</div>
                            <div class="winner-time">Slot available</div>
                        </div>

                        <div class="winner-status"></div>
                    </div>
                @endforeach
            @endif
        </div>
    @endif
</div>
