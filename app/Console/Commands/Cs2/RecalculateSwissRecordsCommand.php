<?php

namespace App\Console\Commands\Cs2;

use App\Jobs\Cs2\RecalculateSwissRecordsJob;
use Illuminate\Console\Command;

class RecalculateSwissRecordsCommand extends Command
{
    protected $signature = 'cs2:recalculate-swiss-records {stage_id}';

    protected $description = 'Recalculate Swiss records for a CS2 stage.';

    public function handle(): int
    {
        RecalculateSwissRecordsJob::dispatchSync((int) $this->argument('stage_id'));
        $this->info('CS2 Swiss records recalculated.');

        return self::SUCCESS;
    }
}
