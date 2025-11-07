<?php

namespace App\DTO\ApiKey;

use Carbon\Carbon;

class CreateApiKeyDTO
{
    public function __construct(
        public readonly string $name,
        public readonly ?Carbon $expires_at,
    ) {}

    public static function fromArray(array $data): self
    {
        $expiresAt = Carbon::now()->addDays($data['expires_at'] ?? 0);

        return new self(
            name: $data['name'],
            expires_at: $expiresAt,
        );
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'expires_at' => $this->expires_at,
        ];
    }
}
