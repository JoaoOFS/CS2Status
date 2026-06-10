<?php

namespace App\Repositories\Cs2\DTOs;

final readonly class TeamData
{
    public function __construct(
        public string $source,
        public string $externalId,
        public string $name,
        public ?string $slug = null,
        public ?string $country = null,
        public ?string $logoUrl = null,
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
            'country' => $this->country,
            'logo_url' => $this->logoUrl,
            'metadata' => $this->metadata,
        ];
    }
}
