<?php

namespace App\Providers\Cs2\Hltv;

use App\Providers\Cs2\Contracts\Cs2DataProviderInterface;

class HltvProvider implements Cs2DataProviderInterface
{
    public function source(): string
    {
        return 'hltv';
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
