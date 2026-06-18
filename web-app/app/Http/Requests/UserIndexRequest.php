<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserIndexRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * Only Bidan can access user management.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->isBidan();
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'search'      => ['nullable', 'string', 'max:100'],
            'status'      => ['nullable', 'in:0,1'],
            'posyandu_id' => ['nullable', 'integer', 'exists:posyandus,id'],
        ];
    }
}
