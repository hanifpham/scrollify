<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateReadingHistoryRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'manga_id' => ['required', 'string', 'uuid'],
            'chapter_id' => ['required', 'string', 'uuid'],
            'chapter_number' => ['required', 'string'],
            'last_page_read' => ['nullable', 'integer', 'min:1'],
        ];
    }
}
