<?php

namespace App\Services;

use App\Actions\UploadFileAction;
use App\DTO\Project\CreateProjectDTO;
use App\DTO\Project\UpdateProjectDTO;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;

class ProjectService
{
    public function __construct(
        protected UploadFileAction $uploadFileAction
    ) {}

    public function list()
    {
        $projects = Auth::user()->projects();

        $projects->orderBy('created_at', 'desc')->paginate(5);

        return $projects->get();
    }

    public function show(string $id)
    {
        return Auth::user()->projects()->findOrFail($id);
    }

    /**
     * Store a new project and handle file uploads.
     */
    public function store(CreateProjectDTO $createProjectDTO): array
    {
        $payload = [];

        if ($createProjectDTO->thumbnail) {
            $payload['thumbnail_url'] = $this->uploadSingleFile(
                file: $createProjectDTO->thumbnail,
                directory: 'projects/thumbnails'
            );
        }

        if (is_array($createProjectDTO->other_image_url)) {
            $payload['other_image_url'] = $this->uploadMultipleFiles(
                files: $createProjectDTO->other_image_url,
                directory: 'projects/other_images'
            );
        }

        $baseData = collect(get_object_vars($createProjectDTO))
            ->except(['thumbnail', 'other_image_url'])
            ->toArray();

        $data = array_merge($baseData, $payload);

        $project = Auth::user()->projects()->create($data);

        return $project->toArray();
    }

    public function update(int $id, UpdateProjectDTO $updateProjectDTO)
    {
        $project = Auth::user()->projects()->findOrFail($id);
        $payload = [];

        if ($updateProjectDTO->thumbnail) {
            if ($updateProjectDTO->thumbnail instanceof UploadedFile) {
                $payload['thumbnail_url'] = $this->uploadSingleFile(
                    file: $updateProjectDTO->thumbnail,
                    directory: 'projects/thumbnails'
                );
            } else {
                $payload['thumbnail_url'] = $updateProjectDTO->thumbnail;
            }
        } else {
            $payload['thumbnail_url'] = null;
        }

        if ($updateProjectDTO->other_image_url) {
            $otherImages = [];
            if ($updateProjectDTO->other_image_url) {
                foreach ($updateProjectDTO->other_image_url as $image) {
                    if ($image instanceof UploadedFile) {
                        $uploadedImage = $this->uploadSingleFile(
                            file: $image,
                            directory: 'projects/other_images'
                        );
                        if ($uploadedImage) {
                            $otherImages[] = $uploadedImage;
                        }
                    } else {
                        $otherImages[] = $image;
                    }
                }
            }

            $payload['other_image_url'] = $otherImages;
        } else {
            $payload['other_image_url'] = null;
        }

        $baseData = collect(get_object_vars($updateProjectDTO))
            ->except(['thumbnail', 'other_image_url'])
            ->toArray();

        $data = array_merge($baseData, $payload);

        $project->update($data);

        return response()->noContent();
    }

    public function delete(int $id)
    {
        $project = Auth::user()->projects()->findOrFail($id);
        $project->delete();

        return response()->noContent();
    }

    private function uploadSingleFile(?UploadedFile $file, string $directory): ?string
    {
        if (! $file instanceof UploadedFile) {
            return null;
        }

        return $this->uploadFileAction->execute(
            file: $file,
            directory: $directory
        );
    }

    private function uploadMultipleFiles(array $files, string $directory): array
    {
        return collect($files)
            ->filter(fn ($file) => $file instanceof UploadedFile)
            ->map(fn ($file) => $this->uploadFileAction->execute(
                file: $file,
                directory: $directory
            ))
            ->values()
            ->all();
    }
}
