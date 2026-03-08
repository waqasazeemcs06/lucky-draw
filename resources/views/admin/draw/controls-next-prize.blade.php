<div class="py-2">
    <div class="w-16 h-16 mx-auto mb-3 rounded-full flex items-center justify-center border-2 border-yellow-400 bg-yellow-400/20 animate-pulse">
        <svg class="w-8 h-8 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
        </svg>
    </div>
    <p class="text-yellow-400 font-bold mb-1">Prize Complete!</p>
    <p class="text-xs text-gray-400 mb-4">Ready for next prize</p>

    <button wire:click="proceedToNextPrize"
            class="next-prize-btn w-full py-4 rounded-xl font-bold text-black bg-gradient-to-r from-yellow-400 to-orange-500">
        Next Prize →
    </button>

    <div class="text-[10px] text-gray-500 mt-2">Press Enter</div>
</div>
