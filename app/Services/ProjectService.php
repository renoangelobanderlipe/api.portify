<?php

namespace App\Services;

use App\Actions\UploadFileAction;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use App\DTO\Project\CreateProjectDTO;

class ProjectService
{
    public function __construct(
        protected UploadFileAction $uploadFileAction
    ) {}

    public function list()
    {
        $projects = Auth::user()->projects();

        $projects->paginate(5);

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

        if (is_array($createProjectDTO->other_image)) {
            $payload['other_image_url'] = json_encode($this->uploadMultipleFiles(
                files: $createProjectDTO->other_image,
                directory: 'projects/other_images'
            ));
        }

        $baseData = collect(get_object_vars($createProjectDTO))
            ->except(['thumbnail', 'other_image'])
            ->toArray();

        $data = array_merge($baseData, $payload);

        $project = Auth::user()->projects()->create($data);

        return $project->toArray();
    }

    public function delete(int $id)
    {
        $project = Auth::user()->projects()->findOrFail($id);
        $project->delete();

        return response()->noContent();
    }


    private function uploadSingleFile(?UploadedFile $file, string $directory): ?string
    {
        if (!$file instanceof UploadedFile) {
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
            ->filter(fn($file) => $file instanceof UploadedFile)
            ->map(fn($file) => $this->uploadFileAction->execute(
                file: $file,
                directory: $directory
            ))
            ->values()
            ->all();
    }
}
