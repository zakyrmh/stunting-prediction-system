<x-layouts::app :title="isset($child) ? __('Edit Data Balita') : __('Pendaftaran Balita Baru')">
    <div class="min-h-screen bg-canvas font-sans">

        {{-- ── Breadcrumb ──────────────────────────────────────────────────── --}}
        <div class="px-4 pt-5 md:px-8">
            <nav class="flex items-center gap-2 text-sm text-ink-muted" aria-label="Breadcrumb">
                <a href="{{ route('balita.index') }}" class="hover:text-primary-teal transition-colors">
                    Data Induk Balita
                </a>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-ink-subtle" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                </svg>
                <span class="text-ink font-semibold">{{ isset($child) ? 'Edit Data Balita' : 'Pendaftaran Balita Baru' }}</span>
            </nav>
        </div>

        {{-- ── Page Container ──────────────────────────────────────────────── --}}
        <div class="max-w-2xl mx-auto px-4 pb-16 pt-6 md:px-8">

            {{-- ── Page Header ─────────────────────────────────────────────── --}}
            <div class="mb-8">
                <p class="text-xs font-semibold text-primary-teal uppercase tracking-widest mb-2">
                    {{ isset($child) ? 'perbarui data' : 'registrasi & input' }}
                </p>
                <h1 class="text-3xl font-bold text-ink tracking-tight leading-tight">
                    {{ isset($child) ? 'Edit Data Balita' : 'Pendaftaran Balita Baru' }}
                </h1>
                <p class="mt-2 text-base text-ink-muted leading-relaxed">
                    {{ isset($child) ? 'Perbarui informasi data diri balita terdaftar.' : 'Daftarkan data balita ke sistem posyandu. Data ini akan menjadi rekam medis awal untuk memantau tumbuh kembang dan mendeteksi risiko stunting.' }}
                </p>
            </div>

            {{-- ── Validation Error Summary ─────────────────────────────────── --}}
            @if($errors->any())
                <div
                    role="alert"
                    class="mb-6 flex gap-3 bg-red-50 border border-red-200 rounded-xl p-4"
                    style="border-left: 3px solid #DC2626;"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-600 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    <div>
                        <p class="font-semibold text-red-700 text-sm">Terdapat kesalahan pada formulir:</p>
                        <ul class="mt-1.5 space-y-1 list-disc list-inside text-red-600 text-sm">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            {{-- ════════════════════════════════════════════════════════════════ --}}
            {{-- FORM                                                             --}}
            {{-- ════════════════════════════════════════════════════════════════ --}}
            <form
                method="POST"
                action="{{ isset($child) ? route('balita.update', $child->id) : route('balita.store') }}"
                id="form-daftar-balita"
                novalidate
            >
                @csrf
                @if(isset($child))
                    @method('PUT')
                @endif

                {{-- ──────────────────────────────────────────────────────────── --}}
                {{-- Section 1 · Identitas Anak                                  --}}
                {{-- ──────────────────────────────────────────────────────────── --}}
                <div class="bg-white border border-hairline rounded-xl p-6 md:p-8 shadow-sm mb-6">

                    {{-- Section Header --}}
                    <div class="flex items-center gap-3 mb-6 pb-4 border-b border-hairline">
                        <div class="h-9 w-9 rounded-lg bg-primary-light flex items-center justify-center shrink-0" aria-hidden="true">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary-teal" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-lg font-bold text-ink leading-none">Identitas Anak</h2>
                            <p class="text-xs text-ink-subtle mt-0.5">Data diri balita yang akan didaftarkan</p>
                        </div>
                    </div>

                    {{-- Nama Lengkap --}}
                    <div class="flex flex-col gap-1.5 mb-6">
                        <label for="input-name" class="text-sm font-semibold text-ink">
                            Nama Lengkap Anak <span class="text-red-500 ml-0.5" aria-hidden="true">*</span>
                        </label>
                        <input
                            type="text"
                            name="name"
                            id="input-name"
                            value="{{ old('name', isset($child) ? $child->name : '') }}"
                            placeholder="Contoh: Budi Santoso"
                            required
                            autocomplete="name"
                            class="w-full px-3.5 py-3 rounded-[10px] border text-sm text-ink placeholder:text-ink-tertiary bg-white transition-colors min-h-[48px]
                                focus:outline-none focus:ring-2 focus:ring-primary-teal focus:ring-offset-0
                                {{ $errors->has('name') ? 'border-red-400 bg-red-50' : 'border-hairline focus:border-primary-teal' }}"
                            aria-describedby="{{ $errors->has('name') ? 'error-name' : 'hint-name' }}"
                        />
                        @error('name')
                            <p id="error-name" role="alert" class="flex items-center gap-1.5 text-xs text-red-600 mt-0.5">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                {{ $message }}
                            </p>
                        @else
                            <p id="hint-name" class="text-xs text-ink-subtle">Nama sesuai dokumen / akta kelahiran.</p>
                        @enderror
                    </div>

                    {{-- NIK (opsional) --}}
                    <div class="flex flex-col gap-1.5 mb-6">
                        <label for="input-nik" class="text-sm font-semibold text-ink">
                            NIK Anak
                            <span class="text-ink-subtle font-normal ml-1">(opsional)</span>
                        </label>
                        <input
                            type="text"
                            name="nik"
                            id="input-nik"
                            value="{{ old('nik', isset($child) ? $child->nik : '') }}"
                            placeholder="16 digit angka"
                            maxlength="16"
                            inputmode="numeric"
                            autocomplete="off"
                            class="w-full px-3.5 py-3 rounded-[10px] border text-sm text-ink placeholder:text-ink-tertiary bg-white transition-colors min-h-[48px] font-mono
                                focus:outline-none focus:ring-2 focus:ring-primary-teal focus:ring-offset-0
                                {{ $errors->has('nik') ? 'border-red-400 bg-red-50' : 'border-hairline focus:border-primary-teal' }}"
                            aria-describedby="{{ $errors->has('nik') ? 'error-nik' : 'hint-nik' }}"
                        />
                        @error('nik')
                            <p id="error-nik" role="alert" class="flex items-center gap-1.5 text-xs text-red-600 mt-0.5">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                {{ $message }}
                            </p>
                        @else
                            <p id="hint-nik" class="text-xs text-ink-subtle">Jika belum ada NIK, kosongkan saja.</p>
                        @enderror
                    </div>

                    {{-- Jenis Kelamin — radio-card pattern dari DESIGN.md --}}
                    <div class="flex flex-col gap-2 mb-6">
                        <fieldset>
                            <legend class="text-sm font-semibold text-ink mb-3">
                                Jenis Kelamin <span class="text-red-500 ml-0.5" aria-hidden="true">*</span>
                            </legend>
                            <div class="grid grid-cols-2 gap-3">

                                {{-- Laki-laki --}}
                                <label
                                    for="gender-male"
                                    id="label-gender-male"
                                    class="flex items-center gap-3 px-4 py-3.5 rounded-xl border-2 cursor-pointer transition-all min-h-[56px]
                                        {{ old('gender', isset($child) ? $child->gender : '') === 'male' ? 'border-primary-teal bg-primary-light' : 'border-hairline bg-canvas hover:border-primary-teal/50' }}"
                                >
                                    <input
                                        type="radio"
                                        name="gender"
                                        id="gender-male"
                                        value="male"
                                        {{ old('gender', isset($child) ? $child->gender : '') === 'male' ? 'checked' : '' }}
                                        class="sr-only"
                                        onchange="updateGenderCard()"
                                    />
                                    <span class="text-2xl" aria-hidden="true">👦</span>
                                    <span class="text-sm font-semibold text-ink">Laki-laki</span>
                                </label>

                                {{-- Perempuan --}}
                                <label
                                    for="gender-female"
                                    id="label-gender-female"
                                    class="flex items-center gap-3 px-4 py-3.5 rounded-xl border-2 cursor-pointer transition-all min-h-[56px]
                                        {{ old('gender', isset($child) ? $child->gender : '') === 'female' ? 'border-primary-teal bg-primary-light' : 'border-hairline bg-canvas hover:border-primary-teal/50' }}"
                                >
                                    <input
                                        type="radio"
                                        name="gender"
                                        id="gender-female"
                                        value="female"
                                        {{ old('gender', isset($child) ? $child->gender : '') === 'female' ? 'checked' : '' }}
                                        class="sr-only"
                                        onchange="updateGenderCard()"
                                    />
                                    <span class="text-2xl" aria-hidden="true">👧</span>
                                    <span class="text-sm font-semibold text-ink">Perempuan</span>
                                </label>

                            </div>
                        </fieldset>
                        @error('gender')
                            <p role="alert" class="flex items-center gap-1.5 text-xs text-red-600 mt-0.5">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Tanggal Lahir + Tempat Lahir (2-kolom di sm+) --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">

                        {{-- Tanggal Lahir --}}
                        <div class="flex flex-col gap-1.5">
                            <label for="input-birth-date" class="text-sm font-semibold text-ink">
                                Tanggal Lahir <span class="text-red-500 ml-0.5" aria-hidden="true">*</span>
                            </label>
                            <input
                                type="date"
                                name="birth_date"
                                id="input-birth-date"
                                value="{{ old('birth_date', isset($child) && $child->birth_date ? $child->birth_date->toDateString() : '') }}"
                                required
                                max="{{ now()->subDay()->toDateString() }}"
                                min="{{ now()->subYears(6)->toDateString() }}"
                                class="w-full px-3.5 py-3 rounded-[10px] border text-sm text-ink bg-white transition-colors min-h-[48px]
                                    focus:outline-none focus:ring-2 focus:ring-primary-teal focus:ring-offset-0
                                    {{ $errors->has('birth_date') ? 'border-red-400 bg-red-50' : 'border-hairline focus:border-primary-teal' }}"
                                aria-describedby="{{ $errors->has('birth_date') ? 'error-birth-date' : 'hint-birth-date' }}"
                            />
                            @error('birth_date')
                                <p id="error-birth-date" role="alert" class="flex items-center gap-1.5 text-xs text-red-600 mt-0.5">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                    {{ $message }}
                                </p>
                            @else
                                <p id="hint-birth-date" class="text-xs text-ink-subtle">Usia maksimal 5 tahun (balita).</p>
                            @enderror
                        </div>

                        {{-- Tempat Lahir --}}
                        <div class="flex flex-col gap-1.5">
                            <label for="input-birth-place" class="text-sm font-semibold text-ink">
                                Tempat Lahir <span class="text-red-500 ml-0.5" aria-hidden="true">*</span>
                            </label>
                            <input
                                type="text"
                                name="birth_place"
                                id="input-birth-place"
                                value="{{ old('birth_place', isset($child) ? $child->birth_place : '') }}"
                                placeholder="Contoh: Bogor"
                                required
                                autocomplete="off"
                                class="w-full px-3.5 py-3 rounded-[10px] border text-sm text-ink placeholder:text-ink-tertiary bg-white transition-colors min-h-[48px]
                                    focus:outline-none focus:ring-2 focus:ring-primary-teal focus:ring-offset-0
                                    {{ $errors->has('birth_place') ? 'border-red-400 bg-red-50' : 'border-hairline focus:border-primary-teal' }}"
                                aria-describedby="{{ $errors->has('birth_place') ? 'error-birth-place' : null }}"
                            />
                            @error('birth_place')
                                <p id="error-birth-place" role="alert" class="flex items-center gap-1.5 text-xs text-red-600 mt-0.5">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                    </div>
                </div>

                {{-- ──────────────────────────────────────────────────────────── --}}
                {{-- Section 2 · Alamat & Posyandu                               --}}
                {{-- ──────────────────────────────────────────────────────────── --}}
                <div class="bg-white border border-hairline rounded-xl p-6 md:p-8 shadow-sm mb-6">

                    {{-- Section Header --}}
                    <div class="flex items-center gap-3 mb-6 pb-4 border-b border-hairline">
                        <div class="h-9 w-9 rounded-lg bg-primary-light flex items-center justify-center shrink-0" aria-hidden="true">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary-teal" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-lg font-bold text-ink leading-none">Alamat & Posyandu</h2>
                            <p class="text-xs text-ink-subtle mt-0.5">Lokasi tempat tinggal dan posyandu yang dituju</p>
                        </div>
                    </div>

                    {{-- Alamat Tinggal --}}
                    <div class="flex flex-col gap-1.5 mb-6">
                        <label for="input-address" class="text-sm font-semibold text-ink">
                            Alamat Tinggal <span class="text-red-500 ml-0.5" aria-hidden="true">*</span>
                        </label>
                        <textarea
                            name="address"
                            id="input-address"
                            rows="3"
                            placeholder="Contoh: Jl. Melati No. 5, RT 02/RW 04, Desa Sukamaju"
                            required
                            class="w-full px-3.5 py-3 rounded-[10px] border text-sm text-ink placeholder:text-ink-tertiary bg-white resize-vertical transition-colors
                                focus:outline-none focus:ring-2 focus:ring-primary-teal focus:ring-offset-0
                                {{ $errors->has('address') ? 'border-red-400 bg-red-50' : 'border-hairline focus:border-primary-teal' }}"
                            aria-describedby="{{ $errors->has('address') ? 'error-address' : null }}"
                        >{{ old('address', isset($child) ? $child->address : '') }}</textarea>
                        @error('address')
                            <p id="error-address" role="alert" class="flex items-center gap-1.5 text-xs text-red-600 mt-0.5">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Posyandu --}}
                    <div class="flex flex-col gap-1.5">
                        <label for="input-posyandu" class="text-sm font-semibold text-ink">
                            Posyandu <span class="text-red-500 ml-0.5" aria-hidden="true">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute left-3.5 top-1/2 -translate-y-1/2 pointer-events-none" aria-hidden="true">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-ink-subtle" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                            </div>
                            <select
                                name="posyandu_id"
                                id="input-posyandu"
                                required
                                class="w-full pl-10 pr-10 py-3 rounded-[10px] border text-sm text-ink bg-white appearance-none transition-colors min-h-[48px]
                                    focus:outline-none focus:ring-2 focus:ring-primary-teal focus:ring-offset-0 cursor-pointer
                                    {{ $errors->has('posyandu_id') ? 'border-red-400 bg-red-50' : 'border-hairline focus:border-primary-teal' }}"
                                aria-describedby="{{ $errors->has('posyandu_id') ? 'error-posyandu' : 'hint-posyandu' }}"
                            >
                                <option value="">— Pilih Posyandu —</option>
                                @foreach($posyandus as $p)
                                    <option value="{{ $p->id }}"
                                        {{ old('posyandu_id', isset($child) ? $child->posyandu_id : '') == $p->id ? 'selected' : '' }}>
                                        {{ $p->name }}{{ $p->village ? ' – ' . $p->village : '' }}
                                    </option>
                                @endforeach
                            </select>
                            {{-- Custom chevron --}}
                            <div class="absolute right-3.5 top-1/2 -translate-y-1/2 pointer-events-none" aria-hidden="true">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-ink-subtle" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </div>
                        </div>
                        @error('posyandu_id')
                            <p id="error-posyandu" role="alert" class="flex items-center gap-1.5 text-xs text-red-600 mt-0.5">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                {{ $message }}
                            </p>
                        @else
                            <p id="hint-posyandu" class="text-xs text-ink-subtle">Pilih posyandu tempat balita terdaftar.</p>
                        @enderror
                    </div>
                </div>

                {{-- ──────────────────────────────────────────────────────────── --}}
                {{-- Section 3 · Tautan Akun Orang Tua (opsional)                --}}
                {{-- ──────────────────────────────────────────────────────────── --}}
                <div class="bg-white border border-hairline rounded-xl p-6 md:p-8 shadow-sm mb-6">

                    {{-- Section Header --}}
                    <div class="flex items-center gap-3 mb-1 pb-4 border-b border-hairline">
                        <div class="h-9 w-9 rounded-lg bg-primary-light flex items-center justify-center shrink-0" aria-hidden="true">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary-teal" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <h2 class="text-lg font-bold text-ink leading-none">Akun Orang Tua</h2>
                                <span class="text-xs font-semibold text-ink-subtle bg-surface-2 px-2 py-0.5 rounded-full">Opsional</span>
                            </div>
                            <p class="text-xs text-ink-subtle mt-0.5">Hubungkan ke akun orang tua agar bisa memantau mandiri</p>
                        </div>
                    </div>

                    {{-- Info box --}}
                    <div class="flex gap-3 rounded-[10px] p-3.5 my-4" style="background-color: #E0F2FE; border-left: 3px solid #0369A1;">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="#0369A1" stroke-width="2" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p class="text-xs text-ink leading-relaxed">
                            Jika orang tua belum punya akun, kosongkan bagian ini.
                            Tautan akun dapat ditambahkan kapan saja setelah balita terdaftar.
                        </p>
                    </div>

                    {{-- Dropdown Orang Tua --}}
                    <div class="flex flex-col gap-1.5">
                        <label for="input-user" class="text-sm font-semibold text-ink">
                            Akun Orang Tua / Wali
                        </label>
                        <div class="relative">
                            <div class="absolute left-3.5 top-1/2 -translate-y-1/2 pointer-events-none" aria-hidden="true">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-ink-subtle" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                            <select
                                name="user_id"
                                id="input-user"
                                class="w-full pl-10 pr-10 py-3 rounded-[10px] border border-hairline text-sm text-ink bg-white appearance-none transition-colors min-h-[48px]
                                    focus:outline-none focus:ring-2 focus:ring-primary-teal focus:ring-offset-0 focus:border-primary-teal cursor-pointer"
                            >
                                <option value="">— Tidak dihubungkan —</option>
                                @foreach($orangTuaList as $ortu)
                                    <option value="{{ $ortu->id }}"
                                        {{ old('user_id', isset($child) ? $child->user_id : '') == $ortu->id ? 'selected' : '' }}>
                                        {{ $ortu->name }} ({{ $ortu->email }})
                                    </option>
                                @endforeach
                            </select>
                            <div class="absolute right-3.5 top-1/2 -translate-y-1/2 pointer-events-none" aria-hidden="true">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-ink-subtle" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </div>
                        </div>
                        @error('user_id')
                            <p role="alert" class="flex items-center gap-1.5 text-xs text-red-600 mt-0.5">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>

                {{-- ── Info Box — Privasi Data ──────────────────────────────── --}}
                <div class="flex gap-3 rounded-[10px] p-4 mb-8"
                    style="background-color: #FEF3C7; border-left: 3px solid #D97706;">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="#D97706" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                    <p class="text-sm text-ink leading-relaxed">
                        <strong class="font-semibold" style="color: #D97706;">Privasi Data:</strong>
                        Data balita bersifat rahasia dan hanya dapat diakses oleh tenaga medis posyandu yang berwenang.
                        Pastikan data yang dimasukkan sudah sesuai persetujuan orang tua / wali.
                    </p>
                </div>

                {{-- ── Action Buttons ──────────────────────────────────────────── --}}
                <div class="flex flex-col sm:flex-row items-center gap-3 sm:gap-4">

                    {{-- Primary: Daftarkan Balita --}}
                    <button
                        type="submit"
                        id="btn-submit"
                        class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 rounded-[10px] bg-primary-teal text-white text-sm font-semibold transition-colors
                            hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-primary-teal focus:ring-offset-2 min-h-[48px]"
                    >
                        @if(isset($child))
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                            Simpan Perubahan
                        @else
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                            </svg>
                            Daftarkan Balita
                        @endif
                    </button>

                    {{-- Secondary: Batal --}}
                    <a
                        href="{{ isset($child) ? route('balita.show', $child->id) : route('balita.index') }}"
                        class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 rounded-[10px] border border-primary-teal text-primary-teal text-sm font-semibold bg-white transition-colors
                            hover:bg-primary-light focus:outline-none focus:ring-2 focus:ring-primary-teal focus:ring-offset-2 min-h-[48px]"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Batal
                    </a>

                </div>

            </form>

        </div>{{-- /max-w-2xl --}}
    </div>{{-- /min-h-screen --}}

    {{-- ── Script: radio-card interactivity & anti double-submit ─────────── --}}
    <script>
        // Update tampilan radio-card jenis kelamin saat dipilih
        function updateGenderCard() {
            const labels = {
                male:   document.getElementById('label-gender-male'),
                female: document.getElementById('label-gender-female'),
            };
            const radios = document.querySelectorAll('input[name="gender"]');

            radios.forEach(function (radio) {
                const label = labels[radio.value];
                if (!label) return;
                if (radio.checked) {
                    label.classList.add('border-primary-teal', 'bg-primary-light');
                    label.classList.remove('border-hairline', 'bg-canvas');
                } else {
                    label.classList.remove('border-primary-teal', 'bg-primary-light');
                    label.classList.add('border-hairline', 'bg-canvas');
                }
            });
        }

        // Prevent double-submit
        document.getElementById('form-daftar-balita').addEventListener('submit', function () {
            const btn = document.getElementById('btn-submit');
            if (btn) {
                btn.disabled = true;
                btn.innerHTML = '<svg class="h-4 w-4 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path></svg> Menyimpan...';
            }
        });

        // Inisialisasi state radio saat halaman dimuat (untuk old() value)
        document.addEventListener('DOMContentLoaded', function () {
            const checked = document.querySelector('input[name="gender"]:checked');
            if (checked) updateGenderCard();

            // NIK: hanya angka
            const nikInput = document.getElementById('input-nik');
            if (nikInput) {
                nikInput.addEventListener('input', function () {
                    this.value = this.value.replace(/\D/g, '').slice(0, 16);
                });
            }
        });
    </script>
</x-layouts::app>
