<?php

namespace App\Repositories\Cs2\DTOs;

final readonly class StageData
{
    public function __construct(
        public int $eventId,
        public string $source,
        public string $externalId,
        public string $name,
        public ?string $slug = null,
        public string $format = 'swiss',
        public string $status = 'scheduled',
        public ?string $startsAt = null,
        public ?string $endsAt = null,
        public ?array $metadata = null,
    ) {
    }

    public function toArray(): array
    {
        return [
            'event_id' => $this->eventId,
            'source' => $this->source,
            'external_id' => $this->externalId,
            'name' => $this->name,
            'slug' => $this->slug,
            'format' => $this->format,
            'status' => $this->status,
            'starts_at' => $this->startsAt,
            'ends_at' => $this->endsAt,
            'metadata' => $this->metadata,
        ];
    }
}
