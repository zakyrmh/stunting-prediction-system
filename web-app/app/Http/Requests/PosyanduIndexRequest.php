<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PosyanduIndexRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Posyandu management is restricted to Bidan only
        return auth()->check() && auth()->user()->isBidan();
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'selectedPosyanduId' => ['nullable', 'integer', 'exists:posyandus,id'],
        ];
    }
}
