<?php

namespace App\Providers\Cs2\Draft5;

use App\Providers\Cs2\Contracts\Cs2DataProviderInterface;

class Draft5Provider implements Cs2DataProviderInterface
{
    public function source(): string
    {
        return 'draft5';
    }

    public function fetchEvents(array $filters = []): array
    {
        return [];
    }

    public function fetchStages(string $eventExternalId): array
    {
        return [];
    }

    public function fetchTeams(string $stageExternalId): array
    {
        return [];
    }

    public function fetchMatches(array $filters = []): array
    {
        return [];
    }
}
