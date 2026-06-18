<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChildrenOverrideRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Only bidan is allowed to override/verify child status
        return auth()->check() && auth()->user()->isBidan();
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'status' => ['required', 'string', 'in:normal,stunting_risk,stunted,severely_stunted,verified'],
            'search' => ['nullable', 'string', 'max:255'],
            'filterPosyandu' => ['nullable', 'string', 'max:255'],
            'filterStatus' => ['nullable', 'string', 'in:normal,stunting_risk,stunted,severely_stunted'],
        ];
    }
}
