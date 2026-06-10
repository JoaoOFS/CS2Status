<?php

namespace App\Models\Cs2;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StageTeam extends Model
{
    use HasFactory;

    protected $table = 'cs2_stage_teams';

    protected $fillable = [
        'stage_id',
        'team_id',
        'source',
        'external_id',
        'wins',
        'losses',
        'record',
        'status',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'wins' => 'integer',
            'losses' => 'integer',
            'metadata' => 'array',
        ];
    }

    public function stage(): BelongsTo
    {
        return $this->belongsTo(Stage::class);
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }
}
