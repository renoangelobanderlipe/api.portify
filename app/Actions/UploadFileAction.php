<?php

namespace App\Actions;

use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UploadFileAction
{
    /**
     * Upload a file to the given directory with unique naming.
     */
    public function execute(
        UploadedFile $file,
        string $directory,
        ?string $prefix = null,
        string $disk = 'public'
    ): string {
        $userId = Auth::id() ?? 'guest';
        $uniqueName = $this->generateUniqueFilename($file, $prefix, $userId);

        $directoryPath = "{$directory}/{$userId}";

        // Store file
        $path = $file->storeAs($directoryPath, $uniqueName, $disk);

        return $path; // Returns something like "projects/1/myfile_uuid.jpg"
    }

    /**
     * Generate a unique and readable filename.
     */
    protected function generateUniqueFilename(UploadedFile $file, ?string $prefix, string|int $userId): string
    {
        $baseName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $extension = $file->getClientOriginalExtension();
        $slug = Str::slug($prefix ? "{$prefix}-{$baseName}" : $baseName);

        return "{$slug}-{$userId}-" . Str::uuid() . ".{$extension}";
    }
}
