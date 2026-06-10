<?php

namespace App\Repositories\Cs2\DTOs;

final readonly class MatchMapData
{
    public function __construct(
        public int $matchId,
        public string $source,
        public string $externalId,
        public int $order = 1,
        public ?string $mapName = null,
        public ?int $winnerTeamId = null,
        public ?int $teamOneScore = null,
        public ?int $teamTwoScore = null,
        public string $status = 'scheduled',
        public ?array $metadata = null,
    ) {
    }

    public function toArray(): array
    {
        return [
            'match_id' => $this->matchId,
            'source' => $this->source,
            'external_id' => $this->externalId,
            'order' => $this->order,
            'map_name' => $this->mapName,
            'winner_team_id' => $this->winnerTeamId,
            'team_one_score' => $this->teamOneScore,
            'team_two_score' => $this->teamTwoScore,
            'status' => $this->status,
            'metadata' => $this->metadata,
        ];
    }
}
