<?php

namespace App\Repositories\Cs2\DTOs;

final readonly class MatchData
{
    public function __construct(
        public int $eventId,
        public int $stageId,
        public string $source,
        public string $externalId,
        public ?int $teamOneId = null,
        public ?int $teamTwoId = null,
        public ?int $winnerTeamId = null,
        public ?int $round = null,
        public ?string $recordGroup = null,
        public string $bestOf = 'bo1',
        public string $status = 'scheduled',
        public ?string $startsAt = null,
        public ?int $teamOneScore = null,
        public ?int $teamTwoScore = null,
        public ?array $metadata = null,
    ) {
    }

    public function toArray(): array
    {
        return [
            'event_id' => $this->eventId,
            'stage_id' => $this->stageId,
            'team_one_id' => $this->teamOneId,
            'team_two_id' => $this->teamTwoId,
            'winner_team_id' => $this->winnerTeamId,
            'source' => $this->source,
            'external_id' => $this->externalId,
            'round' => $this->round,
            'record_group' => $this->recordGroup,
            'best_of' => $this->bestOf,
            'status' => $this->status,
            'starts_at' => $this->startsAt,
            'team_one_score' => $this->teamOneScore,
            'team_two_score' => $this->teamTwoScore,
            'metadata' => $this->metadata,
        ];
    }
}
