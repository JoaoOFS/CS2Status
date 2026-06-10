<?php

namespace App\Models\Cs2;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MatchModel extends Model
{
    use HasFactory;

    protected $table = 'cs2_matches';

    protected $fillable = [
        'event_id',
        'stage_id',
        'team_one_id',
        'team_two_id',
        'winner_team_id',
        'source',
        'external_id',
        'round',
        'record_group',
        'best_of',
        'status',
        'starts_at',
        'team_one_score',
        'team_two_score',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'round' => 'integer',
            'starts_at' => 'datetime',
            'team_one_score' => 'integer',
            'team_two_score' => 'integer',
            'metadata' => 'array',
        ];
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function stage(): BelongsTo
    {
        return $this->belongsTo(Stage::class);
    }

    public function teamOne(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'team_one_id');
    }

    public function teamTwo(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'team_two_id');
    }

    public function winnerTeam(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'winner_team_id');
    }

    public function maps(): HasMany
    {
        return $this->hasMany(MatchMap::class, 'match_id')->orderBy('order');
    }
}
