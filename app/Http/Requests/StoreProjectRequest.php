<?php

namespace App\Http\Requests;

use App\DTO\Project\CreateProjectDTO;
use Illuminate\Foundation\Http\FormRequest;

class StoreProjectRequest extends FormRequest
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
            'repository_link' => 'nullable|url|max:2048',

            'tags' => 'nullable|string',
            'tags.*' => 'string|max:50',

            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:10240',

            'other_image' => ['nullable', 'array'],
            'other_image.*' => ['image', 'mimes:jpeg,png,jpg,gif,svg', 'max:10240'],

            'is_featured' => 'boolean',
            'metadata' => 'nullable|array',
        ];
    }

    public function toDTO(): CreateProjectDTO
    {
        return CreateProjectDTO::fromArray($this->validated());
    }
}
