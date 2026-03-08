<!-- Rolling Display -->
<div class="mb-6">
    <div class="slot-machine-frame p-1 mb-2" style="border-color: {{ $prizeColors[$currentPrize->id] }};">
        <div class="rolling-container bg-black/50 rounded-lg">
            <div class="rolling-display"
                 :class="{ 'active': isRolling }"
                 style="color: {{ $prizeColors[$currentPrize->id] }};"
                 x-text="currentDisplay">
            </div>
        </div>
        <div class="rolling-name" x-show="currentName" x-text="currentName"></div>
    </div>

    <div class="flex items-center justify-center gap-2">
        <div class="text-3xl font-display font-black"
             style="color: {{ $prizeColors[$currentPrize->id] }}; text-shadow: 0 0 10px {{ $prizeColors[$currentPrize->id] }}80;">
            {{ $remainingWinnersToSelect }}
        </div>
        <div class="text-xs text-gray-400 uppercase">Left</div>
    </div>
</div>

<!-- Draw Button -->
<button @click="startRoll"
        :disabled="isRolling"
        class="glow-button w-full py-4 rounded-xl text-base mb-3 relative overflow-hidden"
        style="background: linear-gradient(45deg, {{ $prizeColors[$currentPrize->id] }}, #bc13fe);">

    <!-- Ready State -->
    <span x-show="!isRolling">
        {{ count($winnersForCurrent) > 0 ? 'SPIN AGAIN' : 'START SPIN' }}
    </span>

    <!-- Rolling State -->
    <span x-show="isRolling" class="flex items-center justify-center gap-2">
        <svg class="animate-spin h-5 w-5 inline-flex" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        ROLLING...
    </span>
</button>
