<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FilterBookRequest extends FormRequest
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
            'page'            => 'nullable|numeric',
            'sort_read_pages' => 'nullable|boolean',
            'search'          => 'nullable|string',
        ];
    }
}
