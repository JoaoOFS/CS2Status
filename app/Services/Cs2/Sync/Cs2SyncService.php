<?php

namespace App\Services\Cs2\Sync;

use App\Providers\Cs2\Contracts\Cs2DataProviderInterface;
use App\Repositories\Cs2\Contracts\EventRepositoryInterface;
use Illuminate\Support\Collection;

class Cs2SyncService
{
    public function __construct(
        private readonly EventRepositoryInterface $events,
        private readonly EventSyncService $eventSync,
        private readonly StageSyncService $stageSync,
        private readonly MatchSyncService $matchSync,
    ) {
    }

    public function sync(Cs2DataProviderInterface $provider, array $filters = []): Collection
    {
        $events = $this->eventSync->sync($provider, $filters);

        $events->each(fn ($event) => $this->stageSync->syncForEvent($provider, $event));

        $matches = $this->matchSync->sync($provider, $filters);

        return collect([
            'events' => $events->count(),
            'active_events' => $this->events->listActive()->count(),
            'matches' => $matches->count(),
        ]);
    }
}
