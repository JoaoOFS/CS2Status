<?php

namespace App\Repositories\Cs2\DTOs;

final readonly class StageTeamData
{
    public function __construct(
        public int $stageId,
        public int $teamId,
        public string $source,
        public string $externalId,
        public int $wins = 0,
        public int $losses = 0,
        public string $record = '0-0',
        public string $status = 'active',
        public ?array $metadata = null,
    ) {
    }

    public function toArray(): array
    {
        return [
            'stage_id' => $this->stageId,
            'team_id' => $this->teamId,
            'source' => $this->source,
            'external_id' => $this->externalId,
            'wins' => $this->wins,
            'losses' => $this->losses,
            'record' => $this->record,
            'status' => $this->status,
            'metadata' => $this->metadata,
        ];
    }
}
