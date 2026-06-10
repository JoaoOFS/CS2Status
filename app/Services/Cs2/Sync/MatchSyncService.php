<?php

namespace App\Services\Cs2\Sync;

use App\Providers\Cs2\Contracts\Cs2DataProviderInterface;
use App\Repositories\Cs2\Contracts\EventRepositoryInterface;
use App\Repositories\Cs2\Contracts\MatchMapRepositoryInterface;
use App\Repositories\Cs2\Contracts\MatchRepositoryInterface;
use App\Repositories\Cs2\Contracts\StageRepositoryInterface;
use App\Repositories\Cs2\Contracts\StageTeamRepositoryInterface;
use App\Repositories\Cs2\Contracts\TeamRepositoryInterface;
use App\Repositories\Cs2\DTOs\EventData;
use App\Repositories\Cs2\DTOs\MatchData;
use App\Repositories\Cs2\DTOs\MatchMapData;
use App\Repositories\Cs2\DTOs\StageData;
use App\Repositories\Cs2\DTOs\StageTeamData;
use App\Repositories\Cs2\DTOs\TeamData;
use Illuminate\Database\Eloquent\Collection;

class MatchSyncService
{
    public function __construct(
        private readonly EventRepositoryInterface $events,
        private readonly StageRepositoryInterface $stages,
        private readonly TeamRepositoryInterface $teams,
        private readonly StageTeamRepositoryInterface $stageTeams,
        private readonly MatchRepositoryInterface $matches,
        private readonly MatchMapRepositoryInterface $matchMaps,
    ) {
    }

    public function sync(Cs2DataProviderInterface $provider, array $filters = []): Collection
    {
        return new Collection(collect($provider->fetchMatches($filters))
            ->map(fn (array $match) => $this->syncMatch($provider, $match))
            ->all());
    }

    private function syncMatch(Cs2DataProviderInterface $provider, array $match)
    {
        $event = $this->events->upsertByExternalId(new EventData(
            source: $provider->source(),
            externalId: (string) $match['event']['external_id'],
            name: $match['event']['name'],
            slug: $match['event']['slug'] ?? null,
            region: $match['event']['region'] ?? null,
            startsOn: $match['event']['starts_on'] ?? null,
            endsOn: $match['event']['ends_on'] ?? null,
            metadata: $match['event']['metadata'] ?? null,
        ));

        $stage = $this->stages->upsertByExternalId(new StageData(
            eventId: $event->id,
            source: $provider->source(),
            externalId: (string) $match['stage']['external_id'],
            name: $match['stage']['name'],
            slug: $match['stage']['slug'] ?? null,
            format: $match['stage']['format'] ?? 'swiss',
            status: $match['stage']['status'] ?? 'scheduled',
            startsAt: $match['stage']['starts_at'] ?? null,
            endsAt: $match['stage']['ends_at'] ?? null,
            metadata: $match['stage']['metadata'] ?? null,
        ));

        $teamOne = $this->syncTeam($provider, $stage->id, $match['team_one'] ?? null);
        $teamTwo = $this->syncTeam($provider, $stage->id, $match['team_two'] ?? null);
        $winnerTeamId = $this->winnerTeamId($match['winner_external_id'] ?? null, $teamOne?->id, $teamTwo?->id, $teamOne?->external_id, $teamTwo?->external_id);

        $matchModel = $this->matches->upsertByExternalId(new MatchData(
            eventId: $event->id,
            stageId: $stage->id,
            source: $provider->source(),
            externalId: (string) $match['external_id'],
            teamOneId: $teamOne?->id,
            teamTwoId: $teamTwo?->id,
            winnerTeamId: $winnerTeamId,
            round: $match['round'] ?? null,
            recordGroup: $match['record_group'] ?? null,
            bestOf: $match['best_of'] ?? 'bo1',
            status: $match['status'] ?? 'scheduled',
            startsAt: $match['starts_at'] ?? null,
            teamOneScore: $match['team_one_score'] ?? null,
            teamTwoScore: $match['team_two_score'] ?? null,
            metadata: $match['metadata'] ?? null,
        ));

        collect($match['maps'] ?? [])->each(function (array $map) use ($provider, $matchModel, $teamOne, $teamTwo): void {
            $this->matchMaps->upsertByExternalId(new MatchMapData(
                matchId: $matchModel->id,
                source: $provider->source(),
                externalId: (string) $map['external_id'],
                order: $map['order'] ?? 1,
                mapName: $map['map_name'] ?? null,
                winnerTeamId: $this->winnerTeamId($map['winner_external_id'] ?? null, $teamOne?->id, $teamTwo?->id, $teamOne?->external_id, $teamTwo?->external_id),
                status: $map['status'] ?? 'scheduled',
                metadata: $map['metadata'] ?? null,
            ));
        });

        return $matchModel;
    }

    private function syncTeam(Cs2DataProviderInterface $provider, int $stageId, ?array $teamData)
    {
        if (! $teamData) {
            return null;
        }

        $team = $this->teams->upsertByExternalId(new TeamData(
            source: $provider->source(),
            externalId: (string) $teamData['external_id'],
            name: $teamData['name'],
            slug: $teamData['slug'] ?? null,
            country: $teamData['country'] ?? null,
            logoUrl: $teamData['logo_url'] ?? null,
            metadata: $teamData['metadata'] ?? null,
        ));

        $this->stageTeams->upsertByExternalId(new StageTeamData(
            stageId: $stageId,
            teamId: $team->id,
            source: $provider->source(),
            externalId: "{$stageId}-{$provider->source()}-{$team->external_id}",
        ));

        return $team;
    }

    private function winnerTeamId(?string $winnerExternalId, ?int $teamOneId, ?int $teamTwoId, ?string $teamOneExternalId, ?string $teamTwoExternalId): ?int
    {
        return match ($winnerExternalId) {
            $teamOneExternalId => $teamOneId,
            $teamTwoExternalId => $teamTwoId,
            default => null,
        };
    }
}
