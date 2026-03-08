<div class="min-h-screen flex items-center justify-center p-8 relative z-10">
    <div class="text-center max-w-4xl mx-auto w-full">
        <div class="mb-8">
            <div class="w-40 h-40 mx-auto text-yellow-400 animate-pulse">
                <svg class="w-full h-full" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                </svg>
            </div>
        </div>
        <h2 class="font-display text-6xl md:text-8xl font-black mb-6 neon-text tracking-tighter">DRAW COMPLETED!</h2>
        <p class="text-xl text-gray-400 mb-8">All prizes have been awarded successfully</p>

        <div class="glass-card p-6 max-w-2xl mx-auto mb-8">
            <h3 class="font-display text-xl font-bold mb-4 text-white">Final Results</h3>
            <div class="grid grid-cols-2 md:grid-cols-3 gap-3 text-left">
                @foreach($prizes as $prize)
                    @php
                        $winners = collect($allWinnersHistory)->where('prize_id', $prize->id);
                        $color = $prizeColors[$prize->id];
                    @endphp
                    <div class="p-3 rounded-lg border" style="border-color: {{ $color }}40; background: {{ $color }}10;">
                        <div class="text-xs uppercase tracking-wider mb-1" style="color: {{ $color }}">{{ $prize->title }}</div>
                        <div class="text-xl font-bold">{{ $winners->count() }} <span class="text-sm text-gray-400">winners</span></div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
