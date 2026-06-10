<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

$serieId = config('services.cs2.default_serie_id');

if ($serieId) {
    Schedule::command("cs2:sync-events --provider=pandascore --serie-id={$serieId}")
        ->hourly()
        ->withoutOverlapping();

    Schedule::command("cs2:sync-upcoming-matches --provider=pandascore --serie-id={$serieId} --per-page=100")
        ->everyTenMinutes()
        ->withoutOverlapping();

    Schedule::command("cs2:sync-live-matches --provider=pandascore --serie-id={$serieId} --per-page=100")
        ->everyMinute()
        ->withoutOverlapping();

    Schedule::command("cs2:sync-results --provider=pandascore --serie-id={$serieId} --per-page=100")
        ->everyFiveMinutes()
        ->withoutOverlapping();
}

foreach (config('services.cs2.swiss_stage_ids', []) as $stageId) {
    Schedule::command("cs2:recalculate-swiss-records {$stageId}")
        ->everyFiveMinutes()
        ->withoutOverlapping();
}
