<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Participant extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'store_code',
        'store_name',
        'invoice_number',
        'store_address',
        'draw_id',
        'id',
    ];

    public function draw(): BelongsTo
    {
        return $this->belongsTo(Draw::class);
    }
}
