<?php

namespace App\DTO\TechStack;

class CreateTechStackDTO
{
    public function __construct(
        public string $name,
        public ?string $placeholder,
        public string $icon_tag,
        public ?string $icon_size = '54',
        public ?string $provider,
        public string $category,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'],
            placeholder: $data['placeholder'] ?? null,
            icon_tag: $data['icon_tag'],
            icon_size: $data['icon_size'] ?? '54',
            provider: $data['provider'] ?? null,
            category: $data['category'],
        );
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'placeholder' => $this->placeholder,
            'icon_tag' => $this->icon_tag,
            'icon_size' => $this->icon_size,
            'provider' => $this->provider,
            'category' => $this->category,
        ];
    }
}
