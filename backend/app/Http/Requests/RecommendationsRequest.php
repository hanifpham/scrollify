<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RecommendationsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'format' => ['required', 'string', 'in:manga,manhwa,manhua'],
            'limit' => ['nullable', 'integer', 'min:1', 'max:100'],
        ];
    }
}
