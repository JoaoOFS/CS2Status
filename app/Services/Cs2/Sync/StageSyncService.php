<?php

namespace App\Services\Cs2\Sync;

use App\Models\Cs2\Event;
use App\Providers\Cs2\Contracts\Cs2DataProviderInterface;
use App\Repositories\Cs2\Contracts\StageRepositoryInterface;
use App\Repositories\Cs2\DTOs\StageData;
use Illuminate\Database\Eloquent\Collection;

class StageSyncService
{
    public function __construct(private readonly StageRepositoryInterface $stages)
    {
    }

    public function syncForEvent(Cs2DataProviderInterface $provider, Event $event): Collection
    {
        return new Collection(collect($provider->fetchStages($event->external_id))
            ->map(fn (array $stage) => $this->stages->upsertByExternalId(new StageData(
                eventId: $event->id,
                source: $provider->source(),
                externalId: (string) $stage['external_id'],
                name: $stage['name'],
                slug: $stage['slug'] ?? null,
                format: $stage['format'] ?? 'swiss',
                status: $stage['status'] ?? 'scheduled',
                startsAt: $stage['starts_at'] ?? null,
                endsAt: $stage['ends_at'] ?? null,
                metadata: $stage['metadata'] ?? null,
            )))
            ->all());
    }
}
