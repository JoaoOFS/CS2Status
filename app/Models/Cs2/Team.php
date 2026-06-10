<?php

namespace App\Models\Cs2;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Team extends Model
{
    use HasFactory;

    protected $table = 'cs2_teams';

    protected $fillable = [
        'source',
        'external_id',
        'name',
        'slug',
        'country',
        'logo_url',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'metadata' => 'array',
        ];
    }

    public function stageTeams(): HasMany
    {
        return $this->hasMany(StageTeam::class);
    }
}
