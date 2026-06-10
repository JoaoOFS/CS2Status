<?php

namespace App\Services\Cs2\Swiss;

use App\Models\Cs2\MatchModel;
use App\Models\Cs2\StageTeam;
use App\Repositories\Cs2\Contracts\MatchRepositoryInterface;
use App\Repositories\Cs2\Contracts\StageTeamRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class SwissRecordService
{
    public function __construct(
        private readonly MatchRepositoryInterface $matches,
        private readonly StageTeamRepositoryInterface $stageTeams,
    ) {
    }

    public function statusFor(int $wins, int $losses): string
    {
        if ($wins >= 3) {
            return 'qualified';
        }

        if ($losses >= 3) {
            return 'eliminated';
        }

        return 'active';
    }

    public function recordFor(int $wins, int $losses): string
    {
        return "{$wins}-{$losses}";
    }

    public function isTerminalRecord(string $record): bool
    {
        return in_array($record, ['3-0', '3-1', '3-2', '2-3', '1-3', '0-3'], true);
    }

    public function recalculateStage(int $stageId): Collection
    {
        $stageTeams = $this->stageTeams->listByStage($stageId);
        $records = $stageTeams
            ->mapWithKeys(fn (StageTeam $stageTeam): array => [$stageTeam->team_id => ['wins' => 0, 'losses' => 0]]);

        $this->matches->completedByStage($stageId)->each(function (MatchModel $match) use (&$records): void {
            if (! $match->team_one_id || ! $match->team_two_id || ! $match->winner_team_id) {
                return;
            }

            $loserTeamId = $match->winner_team_id === $match->team_one_id
                ? $match->team_two_id
                : $match->team_one_id;

            if (isset($records[$match->winner_team_id])) {
                $records[$match->winner_team_id]['wins']++;
            }

            if (isset($records[$loserTeamId])) {
                $records[$loserTeamId]['losses']++;
            }
        });

        $stageTeams->each(function (StageTeam $stageTeam) use ($records): void {
            $wins = $records[$stageTeam->team_id]['wins'] ?? 0;
            $losses = $records[$stageTeam->team_id]['losses'] ?? 0;

            $this->stageTeams->updateRecord(
                $stageTeam,
                $wins,
                $losses,
                $this->recordFor($wins, $losses),
                $this->statusFor($wins, $losses),
            );
        });

        return $this->stageTeams->listByStage($stageId);
    }
}
