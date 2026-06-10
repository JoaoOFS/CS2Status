<?php

namespace App\Console\Commands\Cs2;

use App\Console\Commands\Cs2\Concerns\ResolvesCs2Provider;
use App\Jobs\Cs2\SyncEventsJob;
use Illuminate\Console\Command;

class SyncEventsCommand extends Command
{
    use ResolvesCs2Provider;

    protected $signature = 'cs2:sync-events
        {--provider=pandascore}
        {--serie-id=}
        {--tournament-id=}
        {--league-id=}
        {--search=}
        {--from=}
        {--to=}
        {--per-page=100}';

    protected $description = 'Sync CS2 events from an external provider.';

    public function handle(): int
    {
        SyncEventsJob::dispatchSync($this->providerClass((string) $this->option('provider')), $this->providerFilters());
        $this->info('CS2 events sync finished.');

        return self::SUCCESS;
    }
}
