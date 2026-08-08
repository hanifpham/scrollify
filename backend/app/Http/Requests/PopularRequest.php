<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PopularRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'period' => ['required', 'string', 'in:daily,weekly,all'],
            'limit' => ['nullable', 'integer', 'min:1', 'max:100'],
        ];
    }
}
