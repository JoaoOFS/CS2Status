<?php

namespace App\Providers\Cs2\PandaScore;

use App\Providers\Cs2\Contracts\Cs2DataProviderInterface;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class PandaScoreProvider implements Cs2DataProviderInterface
{
    private const VIDEOGAME_PATH = 'csgo';

    public function source(): string
    {
        return 'pandascore';
    }

    public function fetchEvents(array $filters = []): array
    {
        if (isset($filters['serie_id'])) {
            return collect($this->get('series', [
                'filter' => ['id' => $filters['serie_id']],
                'per_page' => 1,
            ]))
                ->map(fn (array $serie): array => $this->normalizeSerie($serie))
                ->all();
        }

        return collect($this->get('series', $this->query($filters)))
            ->map(fn (array $serie): array => $this->normalizeSerie($serie))
            ->all();
    }

    public function fetchStages(string $eventExternalId): array
    {
        return collect($this->get('tournaments', [
            'filter' => ['serie_id' => $eventExternalId],
            'per_page' => 100,
        ]))
            ->map(fn (array $tournament): array => $this->normalizeTournament($tournament, (int) $eventExternalId))
            ->all();
    }

    public function fetchTeams(string $stageExternalId): array
    {
        return [];
    }

    public function fetchMatches(array $filters = []): array
    {
        $status = $filters['status'] ?? null;
        $endpoint = match ($status) {
            'upcoming' => 'matches/upcoming',
            'live', 'running' => 'matches/running',
            'completed', 'finished', 'past' => 'matches/past',
            default => 'matches',
        };

        return collect($this->get($endpoint, $this->query($filters)))
            ->map(fn (array $match): array => $this->normalizeMatch($match))
            ->all();
    }

    private function client(): PendingRequest
    {
        $token = config('services.cs2.pandascore.token');

        if (! $token) {
            throw new RuntimeException('PANDASCORE_TOKEN is not configured.');
        }

        return Http::baseUrl(rtrim((string) config('services.cs2.pandascore.base_url'), '/').'/'.self::VIDEOGAME_PATH)
            ->acceptJson()
            ->withToken($token)
            ->timeout(20);
    }

    private function get(string $endpoint, array $query = []): array
    {
        $response = $this->client()->get($endpoint, $query);

        if ($response->failed()) {
            throw new RuntimeException("PandaScore request failed for [{$endpoint}]: ".$response->body());
        }

        return $response->json() ?? [];
    }

    private function query(array $filters): array
    {
        $query = [
            'page' => (int) ($filters['page'] ?? 1),
            'per_page' => (int) ($filters['per_page'] ?? 100),
        ];

        if (isset($filters['sort'])) {
            $query['sort'] = $filters['sort'];
        } else {
            $query['sort'] = 'begin_at';
        }

        foreach (['serie_id', 'tournament_id', 'league_id'] as $filter) {
            if (isset($filters[$filter])) {
                $query['filter'][$filter] = $filters[$filter];
            }
        }

        if (isset($filters['search'])) {
            $query['search']['name'] = $filters['search'];
        }

        if (isset($filters['from']) || isset($filters['to'])) {
            $query['range']['begin_at'] = implode(',', array_filter([
                $filters['from'] ?? null,
                $filters['to'] ?? null,
            ]));
        }

        return $query;
    }

    private function normalizeSerie(array $serie): array
    {
        return [
            'external_id' => (string) $serie['id'],
            'name' => $serie['full_name'] ?? trim(($serie['name'] ?? '').' '.($serie['year'] ?? '')),
            'slug' => $serie['slug'] ?? null,
            'region' => Arr::get($serie, 'league.name'),
            'starts_on' => isset($serie['begin_at']) ? substr((string) $serie['begin_at'], 0, 10) : null,
            'ends_on' => isset($serie['end_at']) ? substr((string) $serie['end_at'], 0, 10) : null,
            'metadata' => [
                'league_id' => $serie['league_id'] ?? null,
                'league' => $serie['league'] ?? null,
                'tournaments' => $serie['tournaments'] ?? [],
                'year' => $serie['year'] ?? null,
                'season' => $serie['season'] ?? null,
            ],
        ];
    }

    private function normalizeTournament(array $tournament, ?int $serieId = null): array
    {
        return [
            'external_id' => (string) $tournament['id'],
            'event_external_id' => (string) ($serieId ?? $tournament['serie_id']),
            'name' => $tournament['name'],
            'slug' => $tournament['slug'] ?? null,
            'format' => $this->stageFormat($tournament),
            'status' => $this->periodStatus($tournament['begin_at'] ?? null, $tournament['end_at'] ?? null),
            'starts_at' => $tournament['begin_at'] ?? null,
            'ends_at' => $tournament['end_at'] ?? null,
            'metadata' => [
                'type' => $tournament['type'] ?? null,
                'country' => $tournament['country'] ?? null,
                'tier' => $tournament['tier'] ?? null,
                'region' => $tournament['region'] ?? null,
                'has_bracket' => $tournament['has_bracket'] ?? null,
                'live_supported' => $tournament['live_supported'] ?? null,
                'prizepool' => $tournament['prizepool'] ?? null,
            ],
        ];
    }

    private function normalizeMatch(array $match): array
    {
        $opponents = collect($match['opponents'] ?? [])
            ->pluck('opponent')
            ->filter(fn ($team): bool => is_array($team) && isset($team['id']))
            ->values();

        $teamOne = $opponents->get(0);
        $teamTwo = $opponents->get(1);

        return [
            'external_id' => (string) $match['id'],
            'event' => $this->normalizeSerie($match['serie']),
            'stage' => $this->normalizeTournament($match['tournament'], (int) $match['serie_id']),
            'team_one' => $teamOne ? $this->normalizeTeam($teamOne) : null,
            'team_two' => $teamTwo ? $this->normalizeTeam($teamTwo) : null,
            'winner_external_id' => isset($match['winner_id']) ? (string) $match['winner_id'] : null,
            'round' => $this->roundFromName($match['name'] ?? null),
            'record_group' => $this->recordGroupFromName($match['name'] ?? null),
            'best_of' => 'bo'.($match['number_of_games'] ?? 1),
            'status' => $this->matchStatus($match['status'] ?? null),
            'starts_at' => $match['begin_at'] ?? $match['scheduled_at'] ?? null,
            'team_one_score' => $this->scoreFor($match['results'] ?? [], $teamOne['id'] ?? null),
            'team_two_score' => $this->scoreFor($match['results'] ?? [], $teamTwo['id'] ?? null),
            'maps' => collect($match['games'] ?? [])
                ->map(fn (array $game): array => $this->normalizeGame($game))
                ->all(),
            'metadata' => [
                'name' => $match['name'] ?? null,
                'slug' => $match['slug'] ?? null,
                'scheduled_at' => $match['scheduled_at'] ?? null,
                'original_scheduled_at' => $match['original_scheduled_at'] ?? null,
                'streams' => $match['streams_list'] ?? [],
                'live' => $match['live'] ?? null,
            ],
        ];
    }

    private function normalizeTeam(array $team): array
    {
        return [
            'external_id' => (string) $team['id'],
            'name' => $team['name'],
            'slug' => $team['slug'] ?? null,
            'country' => $team['location'] ?? null,
            'logo_url' => $team['image_url'] ?? null,
            'metadata' => [
                'acronym' => $team['acronym'] ?? null,
                'dark_mode_image_url' => $team['dark_mode_image_url'] ?? null,
            ],
        ];
    }

    private function normalizeGame(array $game): array
    {
        return [
            'external_id' => (string) $game['id'],
            'order' => (int) ($game['position'] ?? 1),
            'map_name' => $game['map'] ?? null,
            'winner_external_id' => isset($game['winner']['id']) ? (string) $game['winner']['id'] : null,
            'status' => $this->matchStatus($game['status'] ?? null),
            'metadata' => [
                'begin_at' => $game['begin_at'] ?? null,
                'end_at' => $game['end_at'] ?? null,
                'length' => $game['length'] ?? null,
                'complete' => $game['complete'] ?? null,
                'forfeit' => $game['forfeit'] ?? null,
            ],
        ];
    }

    private function matchStatus(?string $status): string
    {
        return match ($status) {
            'finished' => 'completed',
            'running' => 'live',
            'not_started' => 'scheduled',
            default => $status ?? 'scheduled',
        };
    }

    private function periodStatus(?string $startsAt, ?string $endsAt): string
    {
        $now = now();

        if ($endsAt && $now->greaterThan($endsAt)) {
            return 'completed';
        }

        if ($startsAt && $now->greaterThanOrEqualTo($startsAt)) {
            return 'in_progress';
        }

        return 'scheduled';
    }

    private function stageFormat(array $tournament): string
    {
        return ($tournament['has_bracket'] ?? false) ? 'bracket' : 'swiss';
    }

    private function roundFromName(?string $name): ?int
    {
        if (! $name || ! preg_match('/Round\s+(\d+)/i', $name, $matches)) {
            return null;
        }

        return (int) $matches[1];
    }

    private function recordGroupFromName(?string $name): ?string
    {
        if (! $name || ! preg_match('/\b([0-3]-[0-3])\b/', $name, $matches)) {
            return null;
        }

        return $matches[1];
    }

    private function scoreFor(array $results, ?int $teamId): ?int
    {
        if (! $teamId) {
            return null;
        }

        $result = collect($results)->firstWhere('team_id', $teamId);

        return isset($result['score']) ? (int) $result['score'] : null;
    }
}
