<?php

namespace App\Jobs\Cs2;

use App\Providers\Cs2\Contracts\Cs2DataProviderInterface;
use App\Services\Cs2\Sync\MatchSyncService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SyncLiveMatchesJob implements ShouldQueue
{
    use Queueable;

    public function __construct(
        private readonly string $providerClass,
        private readonly array $filters = [],
    ) {
    }

    public function handle(MatchSyncService $sync): void
    {
        $provider = app($this->providerClass);

        if ($provider instanceof Cs2DataProviderInterface) {
            $sync->sync($provider, ['status' => 'live', ...$this->filters]);
        }
    }
}
