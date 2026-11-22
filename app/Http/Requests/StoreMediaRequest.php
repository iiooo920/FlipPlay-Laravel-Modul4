<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreMediaRequest extends FormRequest
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
            'title' => ['required', 'string', 'max:255', Rule::unique('media', 'title')],
            'type' => ['required', Rule::in(['Anime', 'Manga', 'Drama'])],
            'genres' => ['required', 'array', 'min:1'],
            'genres.*' => ['required', 'string', 'max:80', 'distinct'],
            'rating' => ['required', 'numeric', 'between:0,10'],
            'year' => ['required', 'integer', 'between:1800,2100'],
            'synopsis' => ['required', 'string', 'max:5000'],
        ];
    }
}
