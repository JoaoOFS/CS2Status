<?php

namespace App\Models\Cs2;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    use HasFactory;

    protected $table = 'cs2_events';

    protected $fillable = [
        'source',
        'external_id',
        'name',
        'slug',
        'region',
        'starts_on',
        'ends_on',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'starts_on' => 'date',
            'ends_on' => 'date',
            'metadata' => 'array',
        ];
    }

    public function stages(): HasMany
    {
        return $this->hasMany(Stage::class);
    }

    public function matches(): HasMany
    {
        return $this->hasMany(MatchModel::class);
    }
}
