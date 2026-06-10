<?php

namespace App\Repositories\Cs2\Eloquent;

use App\Models\Cs2\Event;
use App\Repositories\Cs2\Contracts\EventRepositoryInterface;
use App\Repositories\Cs2\DTOs\EventData;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class EventRepository implements EventRepositoryInterface
{
    public function paginate(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return Event::query()
            ->when($filters['status'] ?? null, fn ($query) => $query->whereHas('stages', fn ($stageQuery) => $stageQuery->where('status', $filters['status'])))
            ->when($filters['source'] ?? null, fn ($query, string $source) => $query->where('source', $source))
            ->orderByDesc('starts_on')
            ->paginate($perPage);
    }

    public function find(int $id): ?Event
    {
        return Event::query()->with('stages')->find($id);
    }

    public function listActive(): Collection
    {
        return Event::query()
            ->whereHas('stages', fn ($query) => $query->whereIn('status', ['scheduled', 'live', 'in_progress']))
            ->orderBy('starts_on')
            ->get();
    }

    public function upsertByExternalId(EventData $data): Event
    {
        return Event::query()->updateOrCreate(
            ['source' => $data->source, 'external_id' => $data->externalId],
            $data->toArray(),
        );
    }
}
