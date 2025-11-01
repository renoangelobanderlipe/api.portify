<?php

namespace App\Http\Requests\Auth;

use App\DTO\Auth\UpdateAccountDTO;
use App\Enums\SuffixEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAccountRequest extends FormRequest
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
            'first_name' => 'nullable|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'suffix' => ['nullable', 'string', 'max:10', Rule::in(SuffixEnum::values())],
            'contact_number' => 'nullable|string|max:20|regex:/^[0-9+\-\s()]*$/',
            'headline' => 'nullable|string|max:255',
            'bio' => 'nullable|string',
            'avatar' => 'nullable|file|mimes:jpeg,png,jpg,gif,webp|max:10240',
        ];
    }

    public function toDTO(): UpdateAccountDTO
    {
        return UpdateAccountDTO::fromArray($this->validated());
    }
}
