<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateMediaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // API demo lokal tanpa autentikasi.
    }

    protected function prepareForValidation(): void
    {
        if (is_string($this->input('title'))) {
            $this->merge(['title' => trim($this->input('title'))]);
        }
    }

    public function rules(): array
    {
        return [
            'title' => ['sometimes', 'required', 'string', 'max:255',
                Rule::unique('media', 'title')->ignore($this->route('media'))],
            'type' => ['sometimes', 'required', Rule::in(['Anime', 'Manga', 'Drama'])],
            'genres' => ['sometimes', 'required', 'array', 'min:1'],
            'genres.*' => ['required', 'string', 'max:80', 'distinct'],
            'rating' => ['sometimes', 'required', 'numeric', 'between:0,10'],
            'year' => ['sometimes', 'required', 'integer', 'between:1800,2100'],
            'synopsis' => ['sometimes', 'required', 'string', 'max:5000'],
        ];
    }
}
