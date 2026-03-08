<?php

use Livewire\Component;
use Illuminate\Support\Facades\Cache;
use App\Models\Draw;
use App\Models\Prize;
use App\Models\Participant;
use App\Models\Winner;

new class extends Component
{
    public $draw_id;
    public $draw;
    public $prizes;
    public $currentPrize;
    public $winnersForCurrent = [];
    public $remainingWinnersToSelect = 0;
    public $totalSlots = 0;
    public $allWinnersHistory = [];
    public $prizeColors = [];
    public $showNextPrizeButton = false;
    public $isLastPrize = false;
    public $drawCompleted = false;
    public $isRolling = false;

    // Winner selected at start of roll (hidden until end)
    public $preSelectedWinner = null;

    public function mount($draw_id)
    {
        $this->draw_id = $draw_id;
        $this->draw = Draw::findOrFail($draw_id);
        $this->drawCompleted = strtolower($this->draw->status) === 'completed';

        $this->prizes = Prize::where('draw_id', $this->draw_id)
            ->orderBy('order')
            ->orderBy('id')
            ->get();

        $colors = ['#00f3ff', '#bc13fe', '#ffd700', '#ff006e', '#00ff88', '#ff6b35', '#7209b7', '#14c800'];
        foreach ($this->prizes as $index => $prize) {
            $this->prizeColors[$prize->id] = $colors[$index % count($colors)];
        }

        $this->loadAllWinnersHistory();
        $this->setCurrentPrize();
    }

    protected function loadAllWinnersHistory()
    {
        $this->allWinnersHistory = Winner::where('draw_id', $this->draw_id)
            ->with(['prize', 'participant'])
            ->orderBy('created_at', 'desc')
            ->limit(100)
            ->get()
            ->map(fn($w) => [
                'id' => $w->id,
                'prize_id' => $w->prize_id,
                'prize_title' => $w->prize->title,
                'prize_color' => $this->prizeColors[$w->prize_id] ?? '#00f3ff',
                'winner_name' => $w->participant->name ?? 'Anonymous',
                'invoice' => $w->participant->invoice_number,
                'won_at' => $w->created_at->format('H:i:s'),
                'is_recent' => $w->created_at->diffInMinutes(now()) < 5
            ])
            ->toArray();
    }

    protected function setCurrentPrize()
    {
        $this->currentPrize = null;
        $this->winnersForCurrent = [];
        $this->remainingWinnersToSelect = 0;
        $this->totalSlots = 0;
        $this->showNextPrizeButton = false;
        $this->isLastPrize = false;

        if ($this->drawCompleted) {
            return;
        }

        $remainingPrizes = [];

        foreach ($this->prizes as $prize) {
            $count = Winner::where('prize_id', $prize->id)->count();
            if ($count < (int)$prize->quantity) {
                $remainingPrizes[] = $prize;
            }
        }

        if (count($remainingPrizes) === 1) {
            $this->isLastPrize = true;
        }

        if (count($remainingPrizes) > 0) {
            $prize = $remainingPrizes[0];
            $this->currentPrize = $prize;
            $this->totalSlots = (int)$prize->quantity;

            $existingCount = Winner::where('prize_id', $prize->id)->count();
            $this->remainingWinnersToSelect = (int)$prize->quantity - $existingCount;

            $existingWinners = Winner::where('prize_id', $prize->id)
                ->with('participant')
                ->orderBy('created_at', 'desc')
                ->limit(50)
                ->get();

            $this->winnersForCurrent = $existingWinners
                ->map(fn($w) => [
                    'id' => $w->participant->id,
                    'invoice_number' => $w->participant->invoice_number,
                    'name' => $w->participant->name,
                    'won_at' => $w->created_at->format('H:i:s'),
                    'prize_id' => $w->prize_id
                ])
                ->toArray();

            return;
        }

        $this->loadAllWinnersHistory();
    }

    // Get batch for rolling AND pre-select the winner
    public function startRoll()
    {
        if ($this->isRolling || $this->remainingWinnersToSelect <= 0) {
            return;
        }

        // Get random participant for rolling batch
        $batchParticipants = Participant::where('draw_id', $this->draw_id)
            ->where('is_winner', false)
            ->inRandomOrder()
            ->limit(100)
            ->get(['id', 'invoice_number', 'name']);

        if ($batchParticipants->isEmpty()) {
            return;
        }

        // PRE-SELECT THE ACTUAL WINNER (random from batch or fresh random)
        // Remove winner from database immediately so they can't be selected again
        $winnerParticipant = Participant::where('draw_id', $this->draw_id)
            ->where('is_winner', false)
            ->inRandomOrder()
            ->first();

        if (!$winnerParticipant) {
            return;
        }

        // Mark as winner immediately to prevent double selection
        $winnerParticipant->is_winner = true;
        $winnerParticipant->save();

        // Store winner details
        $this->preSelectedWinner = [
            'id' => $winnerParticipant->id,
            'invoice_number' => $winnerParticipant->invoice_number,
            'name' => $winnerParticipant->name,
        ];

        // Create batch including the winner (so it appears in rolling)
        $rollBatch = $batchParticipants->map(fn($p) => [
            'invoice' => $p->invoice_number,
            'name' => $p->name
        ])->toArray();

        // Ensure winner is in the batch (replace random item or add)
        $winnerInBatch = collect($rollBatch)->firstWhere('invoice', $this->preSelectedWinner['invoice_number']);
        if (!$winnerInBatch) {
            // Replace last item with winner
            $rollBatch[array_key_last($rollBatch)] = [
                'invoice' => $this->preSelectedWinner['invoice_number'],
                'name' => $this->preSelectedWinner['name']
            ];
        }

        $this->isRolling = true;

        // Send batch to frontend (winner is hidden in there)
        $this->dispatch('roll-started', [
            'batch' => $rollBatch,
            'winner_invoice' => $this->preSelectedWinner['invoice_number'] // Tell frontend which one to land on
        ]);
    }

    // Reveal the pre-selected winner
    public function revealWinner()
    {
        if (!$this->isRolling || !$this->preSelectedWinner) {
            return;
        }

        // Create winner record
        $winner = new Winner();
        $winner->draw_id = $this->draw_id;
        $winner->prize_id = $this->currentPrize->id;
        $winner->participant_id = $this->preSelectedWinner['id'];
        $winner->save();

        $newWinner = [
            'id' => $this->preSelectedWinner['id'],
            'invoice_number' => $this->preSelectedWinner['invoice_number'],
            'name' => $this->preSelectedWinner['name'],
            'won_at' => now()->format('H:i:s'),
            'prize_id' => $this->currentPrize->id
        ];

        array_unshift($this->winnersForCurrent, $newWinner);
        $this->remainingWinnersToSelect--;

        array_unshift($this->allWinnersHistory, [
            'id' => $winner->id,
            'prize_id' => $this->currentPrize->id,
            'prize_title' => $this->currentPrize->title,
            'prize_color' => $this->prizeColors[$this->currentPrize->id],
            'winner_name' => $this->preSelectedWinner['name'] ?? 'Anonymous',
            'invoice' => $this->preSelectedWinner['invoice_number'],
            'won_at' => now()->format('H:i:s'),
            'is_recent' => true
        ]);

        if ($this->remainingWinnersToSelect == 0) {
            if (!$this->isLastPrize) {
                $this->showNextPrizeButton = true;
            }
            $this->dispatch('prize-completed');
        }

        $this->isRolling = false;
        $this->preSelectedWinner = null;

        $this->dispatch('winner-revealed', ['winner' => $newWinner]);
    }

    public function proceedToNextPrize()
    {
        $this->showNextPrizeButton = false;
        $this->setCurrentPrize();
        $this->dispatch('prize-changed');
    }

    public function finishDraw()
    {
        $draw = Draw::find($this->draw_id);
        if ($draw) {
            $draw->status = 'completed';
            $draw->save();
        }

        $this->drawCompleted = true;
        $this->currentPrize = null;
        $this->dispatch('draw-finished');
    }
}
?>

<div class="lucky-draw-component min-h-screen bg-[#0a0a1a] text-white overflow-x-hidden relative"
     x-data="luckyDraw()"
     x-init="init()"
     data-draw-id="{{ $draw_id }}"
     data-remaining="{{ $remainingWinnersToSelect }}"
     data-show-next="{{ $showNextPrizeButton ? 'true' : 'false' }}">

    <!-- Background -->
    <div class="fixed inset-0 bg-animated -z-20"></div>
    <div class="fixed inset-0 grid-pattern -z-10 opacity-30"></div>
    <div class="fixed inset-0 pointer-events-none -z-10" id="particles"></div>

    @if($drawCompleted || (!$currentPrize && count($allWinnersHistory) > 0))
        @include('admin.draw.completed')
    @else
        @include('admin.draw.draw-main')
    @endif

    <audio id="win-sound" preload="auto">
        <source src="{{ asset('win.mp3') }}" type="audio/mpeg">
    </audio>
</div>
