<?php

namespace App\Models\Cs2;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MatchMap extends Model
{
    use HasFactory;

    protected $table = 'cs2_match_maps';

    protected $fillable = [
        'match_id',
        'source',
        'external_id',
        'order',
        'map_name',
        'winner_team_id',
        'team_one_score',
        'team_two_score',
        'status',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'order' => 'integer',
            'team_one_score' => 'integer',
            'team_two_score' => 'integer',
            'metadata' => 'array',
        ];
    }

    public function match(): BelongsTo
    {
        return $this->belongsTo(MatchModel::class, 'match_id');
    }

    public function winnerTeam(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'winner_team_id');
    }
}
