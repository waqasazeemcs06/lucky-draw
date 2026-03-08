<div class="py-4">
    <div class="w-20 h-20 mx-auto mb-3 rounded-full flex items-center justify-center border-2 border-green-400 bg-green-400/20 animate-pulse">
        <svg class="w-10 h-10 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
        </svg>
    </div>
    <p class="text-green-400 font-bold text-lg mb-1">All Prizes Done!</p>
    <p class="text-xs text-gray-400 mb-4">Ready to finalize draw</p>

    <button wire:click="finishDraw"
            class="finish-btn w-full py-4 rounded-xl font-bold text-black bg-gradient-to-r from-green-400 to-emerald-500 mb-3">
        Finish Lucky Draw ✓
    </button>

    <div class="text-4xl font-display font-black text-yellow-400 mb-1" style="text-shadow: 0 0 20px rgba(255, 215, 0, 0.5);">
        {{ count($allWinnersHistory) }}
    </div>
    <div class="text-xs text-gray-500 uppercase tracking-wider">Total Winners</div>
</div>
