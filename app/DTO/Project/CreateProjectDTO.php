<?php

namespace App\DTO\Project;

use Illuminate\Support\Carbon;
use Illuminate\Http\UploadedFile;

class CreateProjectDTO
{
    /**
     * @param string $title
     * @param string|null $description
     * @param Carbon|null $startDate
     * @param Carbon|null $endDate
     * @param string|null $url
     * @param string|null $repository_link
     * @param string|null $project_type
     * @param array|null $tags
     * @param string|UploadedFile|null $thumbnail
     * @param array|null $other_image // array of UploadedFile|string (for existing URLs)
     * @param bool|null $is_featured
     * @param array|null $metadata
     */
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
        public readonly ?array $other_image,
        public readonly ?bool $is_featured,
        public readonly ?array $metadata,
    ) {}

    public static function fromArray(array $data): self
    {
        // Normalize other_image: ensure it's always an array if present
        $otherImage = $data['other_image'] ?? null;
        if ($otherImage !== null && !is_array($otherImage)) {
            $otherImage = [$otherImage];
        }

        return new self(
            title: $data['title'],
            description: $data['description'] ?? null,
            start_date: isset($data['start_date']) ? Carbon::parse($data['start_date']) : null,
            end_date: isset($data['end_date']) ? Carbon::parse($data['end_date']) : null,
            url: $data['url'] ?? null,
            repository_link: $data['repository_link'] ?? null,
            project_type: $data['project_type'] ?? null,
            tags: json_decode($data['tags'], true) ?? null,
            thumbnail: $data['thumbnail'] ?? null,
            other_image: $otherImage,
            is_featured: $data['is_featured'] ?? null,
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
            'repository_link' => $this->repository_link,
            'project_type' => $this->project_type,
            'tags' => $this->tags,
            'thumbnail' => $this->thumbnail,
            'other_image' => $this->other_image,
            'is_featured' => $this->is_featured,
            'metadata' => $this->metadata,
        ];
    }
}
