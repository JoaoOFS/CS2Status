<?php

namespace App\Repositories\Cs2\Eloquent;

use App\Models\Cs2\Stage;
use App\Repositories\Cs2\Contracts\StageRepositoryInterface;
use App\Repositories\Cs2\DTOs\StageData;
use Illuminate\Database\Eloquent\Collection;

class StageRepository implements StageRepositoryInterface
{
    public function find(int $id): ?Stage
    {
        return Stage::query()->with('event')->find($id);
    }

    public function listByEvent(int $eventId, array $filters = []): Collection
    {
        return Stage::query()
            ->where('event_id', $eventId)
            ->when($filters['format'] ?? null, fn ($query, string $format) => $query->where('format', $format))
            ->when($filters['status'] ?? null, fn ($query, string $status) => $query->where('status', $status))
            ->orderBy('starts_at')
            ->get();
    }

    public function upsertByExternalId(StageData $data): Stage
    {
        return Stage::query()->updateOrCreate(
            ['source' => $data->source, 'external_id' => $data->externalId],
            $data->toArray(),
        );
    }
}
