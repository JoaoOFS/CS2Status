<?php

namespace App\Models\Cs2;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Stage extends Model
{
    use HasFactory;

    protected $table = 'cs2_stages';

    protected $fillable = [
        'event_id',
        'source',
        'external_id',
        'name',
        'slug',
        'format',
        'status',
        'starts_at',
        'ends_at',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
            'metadata' => 'array',
        ];
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function stageTeams(): HasMany
    {
        return $this->hasMany(StageTeam::class);
    }

    public function matches(): HasMany
    {
        return $this->hasMany(MatchModel::class);
    }
}
