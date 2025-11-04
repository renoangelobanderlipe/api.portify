<?php

namespace App\Http\Requests;

use App\DTO\Project\UpdateProjectDTO;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProjectRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',

            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'project_type' => 'nullable|string|max:255',

            'url' => 'nullable|url|max:2048',
            'repository' => 'nullable|url|max:2048',

            'tags' => 'nullable|string',
            // 'tags.*' => 'nullable|string|max:50',

            'thumbnail' => 'nullable',
            'thumbnail' => [
                'nullable',
                function ($attribute, $value, $fail) {
                    if ($value instanceof \Illuminate\Http\UploadedFile) {
                        if (!$value->isValid()) {
                            $fail("The {$attribute} file is invalid.");
                        }
                        if (!in_array($value->extension(), ['jpeg', 'png', 'jpg', 'gif', 'svg'])) {
                            $fail("The {$attribute} must be a jpeg, png, jpg, gif, or svg.");
                        }
                        if ($value->getSize() > 10 * 1024 * 1024) {
                            $fail("The {$attribute} may not be greater than 10MB.");
                        }
                    }
                },
            ],

            'other_images' => 'nullable|array',
            // 'other_images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:10240',

            'published_at' => 'boolean',

            'metadata' => 'nullable|array',
        ];
    }

    public function toDTO(): UpdateProjectDTO
    {
        return UpdateProjectDTO::fromArray($this->validated());
    }
}
