<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddToCollectionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // API demo lokal tanpa autentikasi.
    }

    public function rules(): array
    {
        return [
            'media_id' => ['required', 'integer', 'exists:media,id'],
        ];
    }
}
