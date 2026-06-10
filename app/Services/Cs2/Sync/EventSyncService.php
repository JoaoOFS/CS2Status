<?php

namespace App\Services\Cs2\Sync;

use App\Providers\Cs2\Contracts\Cs2DataProviderInterface;
use App\Repositories\Cs2\Contracts\EventRepositoryInterface;
use App\Repositories\Cs2\DTOs\EventData;
use Illuminate\Database\Eloquent\Collection;

class EventSyncService
{
    public function __construct(private readonly EventRepositoryInterface $events)
    {
    }

    public function sync(Cs2DataProviderInterface $provider, array $filters = []): Collection
    {
        return new Collection(collect($provider->fetchEvents($filters))
            ->map(fn (array $event) => $this->events->upsertByExternalId(new EventData(
                source: $provider->source(),
                externalId: (string) $event['external_id'],
                name: $event['name'],
                slug: $event['slug'] ?? null,
                region: $event['region'] ?? null,
                startsOn: $event['starts_on'] ?? null,
                endsOn: $event['ends_on'] ?? null,
                metadata: $event['metadata'] ?? null,
            )))
            ->all());
    }
}
