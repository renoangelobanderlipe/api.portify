<?php

namespace App\DTO\ApiKey;

class CreateApiKeyDTO
{
    public function __construct(
        public readonly string $name,
        public readonly ?int $expiresAt,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'],
            expiresAt: $data['expires_at'] ?? null,
        );
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'expires_at' => $this->expiresAt,
        ];
    }
}
