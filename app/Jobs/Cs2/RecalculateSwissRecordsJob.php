<?php

namespace App\Jobs\Cs2;

use App\Services\Cs2\Swiss\SwissRecordService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class RecalculateSwissRecordsJob implements ShouldQueue
{
    use Queueable;

    public function __construct(private readonly int $stageId)
    {
    }

    public function handle(SwissRecordService $swissRecords): void
    {
        $swissRecords->recalculateStage($this->stageId);
    }
}
