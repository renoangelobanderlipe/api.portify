<?php

namespace App\DTO\Project;

use Carbon\Carbon;
use Illuminate\Http\UploadedFile;

class UpdateProjectDTO
{
    public function __construct(
        public readonly string $title,
        public readonly ?string $description,
        public readonly ?Carbon $start_date,
        public readonly ?Carbon $end_date,
        public readonly ?string $url,
        public readonly ?string $repository_link,
        public readonly ?string $project_type,
        public readonly ?array $tags,
        public readonly string|UploadedFile|null $thumbnail,
        public readonly ?array $other_image_url,
        public readonly ?bool $published_at,
        public readonly ?array $metadata,
    ) {}

    public static function fromArray(array $data): self
    {
        // Normalize other_image: ensure it's always an array if present
        $otherImage = $data['other_images'] ?? [];
        if ($otherImage !== null && ! is_array($otherImage)) {
            $otherImage = [$otherImage];
        }
        return new self(
            title: $data['title'],
            description: $data['description'] ?? null,
            start_date: isset($data['start_date']) ? Carbon::parse($data['start_date']) : null,
            end_date: isset($data['end_date']) ? Carbon::parse($data['end_date']) : null,
            url: $data['url'] ?? null,
            repository_link: $data['repository'] ?? null,
            project_type: $data['project_type'] ?? null,
            tags: json_decode($data['tags'], true) ?? null,
            thumbnail: $data['thumbnail'] ?? null,
            other_image_url: $otherImage,
            published_at: $data['published_at'] ?? null,
            metadata: $data['metadata'] ?? null,
        );
    }

    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'description' => $this->description,
            'start_date' => $this->start_date?->toDateString(),
            'end_date' => $this->end_date?->toDateString(),
            'url' => $this->url,
            'repository' => $this->repository_link,
            'project_type' => $this->project_type,
            'tags' => $this->tags ? json_encode($this->tags) : null,
            'thumbnail' => $this->thumbnail,
            'other_image_url' => $this->other_image_url,
            'published_at' => $this->published_at,
            'metadata' => $this->metadata,
        ];
    }
}
