<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * Only Bidan can create kader accounts.
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
            'name'        => ['required', 'string', 'max:255'],
            'email'       => ['required', 'email', 'max:255', 'unique:users,email'],
            'phone'       => ['nullable', 'string', 'max:20', 'unique:users,phone'],
            'password'    => ['required', 'string', 'min:8', 'confirmed'],
            'posyandu_id' => ['required', 'integer', 'exists:posyandus,id'],
        ];
    }

    /**
     * Human-readable attribute names for error messages.
     */
    public function attributes(): array
    {
        return [
            'name'        => 'Nama Lengkap',
            'email'       => 'Alamat Email',
            'phone'       => 'Nomor HP/WA',
            'password'    => 'Kata Sandi',
            'posyandu_id' => 'Posyandu',
        ];
    }
}
