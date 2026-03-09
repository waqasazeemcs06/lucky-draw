<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Winner extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'draw_id',
        'prize_id',
        'participant_id',
    ];

    public function draw(): BelongsTo
    {
        return $this->belongsTo(Draw::class);
    }

    public function participant(): BelongsTo
    {
        return $this->belongsTo(Participant::class);
    }

    public function prize(): BelongsTo
    {
        return $this->belongsTo(Prize::class);
    }
}
