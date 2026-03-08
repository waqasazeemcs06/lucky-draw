<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Draw extends Model
{
    use SoftDeletes;

    const STATUS_ACTIVE = 'active';
    const STATUS_COMPLETED = 'completed';

    protected $fillable = [
        'title',
        'draw_date',
        'status',
        'id',
    ];

    protected function casts(): array
    {
        return [
            'draw_date' => 'datetime',
        ];
    }

    protected function drawDate(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => Carbon::parse($value)->format('M d, Y h:i:s A'),
        );
    }

    protected function status(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => ucfirst($value),
        );
    }

    #[Scope]
    protected function active(Builder $query): void
    {
        $query->where('status', self::STATUS_ACTIVE);
    }

    #[Scope]
    protected function completed(Builder $query): void
    {
        $query->where('status', self::STATUS_COMPLETED);
    }

    public function prizes(): HasMany
    {
        return $this->hasMany(Prize::class);
    }

    public function participants(): HasMany
    {
        return $this->hasMany(Participant::class);
    }
}
