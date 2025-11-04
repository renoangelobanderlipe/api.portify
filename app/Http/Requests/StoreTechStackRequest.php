<?php

namespace App\Http\Requests;

use App\DTO\TechStack\CreateTechStackDTO;
use Illuminate\Foundation\Http\FormRequest;

class StoreTechStackRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'placeholder' => 'nullable|string|max:255',
            'icon_tag' => 'required|string|max:255|unique:tech_stacks,icon_tag',
            'icon_size' => 'nullable|integer|max:100',
            'provider' => 'nullable|string|max:255',
            'category' => 'required|string|max:255',
        ];
    }


    public function toDTO(): CreateTechStackDTO
    {
        return CreateTechStackDTO::fromArray($this->validated());
    }
}
