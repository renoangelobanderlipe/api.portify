<?php

namespace App\DTO\Auth;

use App\Enums\SuffixEnum;
use Illuminate\Http\UploadedFile;

class UpdateAccountDTO
{
    public function __construct(
        public ?string $first_name,
        public ?string $middle_name,
        public ?string $last_name,
        public ?SuffixEnum $suffix,
        public ?string $contact_number,
        public ?string $headline,
        public ?string $bio,
        public ?UploadedFile $avatar,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            first_name: $data['first_name'] ?? null,
            middle_name: $data['middle_name'] ?? null,
            last_name: $data['last_name'] ?? null,
            suffix: isset($data['suffix']) ? SuffixEnum::tryFrom($data['suffix']) : null,
            contact_number: $data['contact_number'] ?? null,
            headline: $data['headline'] ?? null,
            bio: $data['bio'] ?? null,
            avatar: $data['avatar'] ?? null,
        );
    }

    public function toArray(): array
    {
        return [
            'first_name' => $this->first_name,
            'middle_name' => $this->middle_name,
            'last_name' => $this->last_name,
            'suffix' => $this->suffix?->value,
            'contact_number' => $this->contact_number,
            'headline' => $this->headline,
            'bio' => $this->bio,
            'avatar' => $this->avatar,
        ];
    }
}
