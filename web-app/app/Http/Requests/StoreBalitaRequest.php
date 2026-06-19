<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBalitaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * Bidan dan Kader boleh mendaftarkan balita baru.
     */
    public function authorize(): bool
    {
        $user = auth()->user();
        return $user && ($user->isBidan() || $user->isKader());
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name'        => ['required', 'string', 'max:255'],
            'nik'         => ['nullable', 'string', 'size:16', 'unique:children,nik'],
            'birth_date'  => ['required', 'date', 'before:today', 'after:' . now()->subYears(6)->toDateString()],
            'birth_place' => ['required', 'string', 'max:100'],
            'gender'      => ['required', 'in:male,female'],
            'address'     => ['required', 'string', 'max:1000'],
            'posyandu_id' => ['required', 'integer', 'exists:posyandus,id'],
            'user_id'     => ['nullable', 'integer', 'exists:users,id'],
        ];
    }

    /**
     * Human-readable attribute names for validation error messages.
     */
    public function attributes(): array
    {
        return [
            'name'        => 'Nama Lengkap Anak',
            'nik'         => 'NIK Anak',
            'birth_date'  => 'Tanggal Lahir',
            'birth_place' => 'Tempat Lahir',
            'gender'      => 'Jenis Kelamin',
            'address'     => 'Alamat Tinggal',
            'posyandu_id' => 'Posyandu',
            'user_id'     => 'Akun Orang Tua',
        ];
    }

    /**
     * Custom error messages.
     */
    public function messages(): array
    {
        return [
            'nik.size'           => 'NIK anak harus terdiri dari tepat 16 digit angka.',
            'nik.unique'         => 'NIK ini sudah terdaftar di sistem. Periksa kembali datanya.',
            'birth_date.before'  => 'Tanggal lahir tidak boleh hari ini atau di masa depan.',
            'birth_date.after'   => 'Usia anak tidak boleh lebih dari 5 tahun (balita).',
            'gender.in'          => 'Pilih jenis kelamin: Laki-laki atau Perempuan.',
        ];
    }
}
