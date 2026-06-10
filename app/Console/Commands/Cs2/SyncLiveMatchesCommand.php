<?php

namespace App\Console\Commands\Cs2;

use App\Console\Commands\Cs2\Concerns\ResolvesCs2Provider;
use App\Jobs\Cs2\SyncLiveMatchesJob;
use Illuminate\Console\Command;

class SyncLiveMatchesCommand extends Command
{
    use ResolvesCs2Provider;

    protected $signature = 'cs2:sync-live-matches
        {--provider=pandascore}
        {--serie-id=}
        {--tournament-id=}
        {--league-id=}
        {--search=}
        {--from=}
        {--to=}
        {--per-page=100}';

    protected $description = 'Sync live CS2 matches from an external provider.';

    public function handle(): int
    {
        SyncLiveMatchesJob::dispatchSync($this->providerClass((string) $this->option('provider')), $this->providerFilters());
        $this->info('CS2 live matches sync finished.');

        return self::SUCCESS;
    }
}
