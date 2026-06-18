<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePosyanduRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * Only Bidan (midwife) can register a new Posyandu.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->isBidan();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name'     => ['required', 'string', 'max:255', 'unique:posyandus,name'],
            'address'  => ['required', 'string', 'max:1000'],
            'village'  => ['required', 'string', 'max:100'],
            'district' => ['required', 'string', 'max:100'],
            'city'     => ['required', 'string', 'max:100'],
        ];
    }

    /**
     * Human-readable attribute names for validation error messages.
     */
    public function attributes(): array
    {
        return [
            'name'     => 'Nama Posyandu',
            'address'  => 'Alamat Lengkap',
            'village'  => 'Desa / Kelurahan',
            'district' => 'Kecamatan',
            'city'     => 'Kabupaten / Kota',
        ];
    }

    /**
     * Custom error messages.
     */
    public function messages(): array
    {
        return [
            'name.unique' => 'Nama posyandu ini sudah terdaftar. Gunakan nama yang berbeda.',
        ];
    }
}
