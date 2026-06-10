<?php

namespace App\Repositories\Cs2\DTOs;

final readonly class EventData
{
    public function __construct(
        public string $source,
        public string $externalId,
        public string $name,
        public ?string $slug = null,
        public ?string $region = null,
        public ?string $startsOn = null,
        public ?string $endsOn = null,
        public ?array $metadata = null,
    ) {
    }

    public function toArray(): array
    {
        return [
            'source' => $this->source,
            'external_id' => $this->externalId,
            'name' => $this->name,
            'slug' => $this->slug,
            'region' => $this->region,
            'starts_on' => $this->startsOn,
            'ends_on' => $this->endsOn,
            'metadata' => $this->metadata,
        ];
    }
}
