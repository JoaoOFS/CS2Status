<?php

namespace App\Console\Commands\Cs2;

use App\Console\Commands\Cs2\Concerns\ResolvesCs2Provider;
use App\Jobs\Cs2\SyncResultsJob;
use Illuminate\Console\Command;

class SyncResultsCommand extends Command
{
    use ResolvesCs2Provider;

    protected $signature = 'cs2:sync-results
        {--provider=pandascore}
        {--serie-id=}
        {--tournament-id=}
        {--league-id=}
        {--search=}
        {--from=}
        {--to=}
        {--per-page=100}';

    protected $description = 'Sync finished CS2 match results from an external provider.';

    public function handle(): int
    {
        SyncResultsJob::dispatchSync($this->providerClass((string) $this->option('provider')), $this->providerFilters());
        $this->info('CS2 results sync finished.');

        return self::SUCCESS;
    }
}
