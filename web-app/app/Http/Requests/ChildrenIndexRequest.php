<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChildrenIndexRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'search' => ['nullable', 'string', 'max:255'],
            'filterPosyandu' => ['nullable', 'string', 'max:255'],
            'filterStatus' => ['nullable', 'string', 'in:normal,stunting_risk,stunted,severely_stunted'],
            'selectedChildId' => ['nullable', 'integer', 'exists:children,id'],
        ];
    }
}
