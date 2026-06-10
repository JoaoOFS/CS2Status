<?php

namespace App\Providers\Cs2\Contracts;

interface Cs2DataProviderInterface
{
    public function source(): string;

    public function fetchEvents(array $filters = []): array;

    public function fetchStages(string $eventExternalId): array;

    public function fetchTeams(string $stageExternalId): array;

    public function fetchMatches(array $filters = []): array;
}
