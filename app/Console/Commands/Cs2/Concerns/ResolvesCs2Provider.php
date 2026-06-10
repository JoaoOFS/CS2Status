<?php

namespace App\Console\Commands\Cs2\Concerns;

use App\Providers\Cs2\Draft5\Draft5Provider;
use App\Providers\Cs2\Hltv\HltvProvider;
use App\Providers\Cs2\PandaScore\PandaScoreProvider;

trait ResolvesCs2Provider
{
    protected function providerClass(string $provider): string
    {
        return match ($provider) {
            'hltv' => HltvProvider::class,
            'draft5' => Draft5Provider::class,
            default => PandaScoreProvider::class,
        };
    }

    protected function providerFilters(): array
    {
        return collect([
            'serie_id' => $this->option('serie-id'),
            'tournament_id' => $this->option('tournament-id'),
            'league_id' => $this->option('league-id'),
            'search' => $this->option('search'),
            'from' => $this->option('from'),
            'to' => $this->option('to'),
            'per_page' => $this->option('per-page'),
        ])
            ->filter(fn ($value): bool => filled($value))
            ->all();
    }
}
