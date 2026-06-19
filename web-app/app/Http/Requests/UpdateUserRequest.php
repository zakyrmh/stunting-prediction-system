<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * Only Bidan can update kader accounts.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->isBidan();
    }

    /**
     * Use a named error bag so update errors don't collide with store form errors.
     */
    protected $errorBag = 'updateKader';

    /**
     * Get the validation rules that apply to the request.
     *
     * Email and phone must be unique — but excluded from the kader being updated.
     * Password is optional: only updated when explicitly provided.
     */
    public function rules(): array
    {
        /** @var \App\Models\User $kader */
        $kader = $this->route('user');

        return [
            'name'        => ['required', 'string', 'max:255'],
            'email'       => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($kader->id)],
            'phone'       => ['nullable', 'string', 'max:20', Rule::unique('users', 'phone')->ignore($kader->id)],
            'posyandu_id' => ['required', 'integer', 'exists:posyandus,id'],
            'password'    => ['nullable', 'string', 'min:8', 'confirmed'],
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
            'posyandu_id' => 'Posyandu',
            'password'    => 'Kata Sandi Baru',
        ];
    }
}
