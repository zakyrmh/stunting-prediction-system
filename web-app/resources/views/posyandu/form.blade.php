<x-layouts::app :title="__('Daftarkan Posyandu Baru')">
    <div class="min-h-screen bg-canvas font-sans">

        {{-- ── Breadcrumb ──────────────────────────────────────────────────── --}}
        <div class="px-4 pt-5 md:px-8">
            <nav class="flex items-center gap-2 text-sm text-ink-muted" aria-label="Breadcrumb">
                <a href="{{ route('posyandu.index') }}" class="hover:text-primary-teal transition-colors">
                    Data Posyandu
                </a>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-ink-subtle" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                </svg>
                <span class="text-ink font-semibold">Daftarkan Posyandu Baru</span>
            </nav>
        </div>

        {{-- ── Page Container ──────────────────────────────────────────────── --}}
        <div class="max-w-2xl mx-auto px-4 pb-16 pt-6 md:px-8">

            {{-- ── Page Header ─────────────────────────────────────────────── --}}
            <div class="mb-8">
                <p class="text-xs font-semibold text-primary-teal uppercase tracking-widest mb-2">
                    manajemen sistem
                </p>
                <h1 class="text-3xl font-bold text-ink tracking-tight leading-tight">
                    Daftarkan Posyandu Baru
                </h1>
                <p class="mt-2 text-base text-ink-muted leading-relaxed">
                    Isi formulir di bawah untuk menambahkan posyandu baru ke dalam sistem.
                    Data ini akan digunakan untuk mengelompokkan balita dan kader per wilayah.
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

            {{-- ══════════════════════════════════════════════════════════════ --}}
            {{-- Form Card (measurement-input-card pattern dari DESIGN.md)     --}}
            {{-- ══════════════════════════════════════════════════════════════ --}}
            <form
                method="POST"
                action="{{ route('posyandu.store') }}"
                id="form-daftar-posyandu"
                novalidate
            >
                @csrf

                {{-- ── Section 1: Identitas Posyandu ─────────────────────────── --}}
                <div class="bg-white border border-hairline rounded-xl p-6 md:p-8 shadow-sm mb-6">

                    {{-- Section Header --}}
                    <div class="flex items-center gap-3 mb-6 pb-4 border-b border-hairline">
                        <div class="h-9 w-9 rounded-lg bg-primary-light flex items-center justify-center shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary-teal" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-lg font-bold text-ink leading-none">Identitas Posyandu</h2>
                            <p class="text-xs text-ink-subtle mt-0.5">Nama resmi yang tercatat di kelurahan</p>
                        </div>
                    </div>

                    {{-- Field: Nama Posyandu --}}
                    <div class="flex flex-col gap-1.5 mb-6">
                        <label for="input-name" class="text-sm font-semibold text-ink">
                            Nama Posyandu
                            <span class="text-red-500 ml-0.5" aria-hidden="true">*</span>
                        </label>
                        <input
                            type="text"
                            name="name"
                            id="input-name"
                            value="{{ old('name') }}"
                            placeholder="Contoh: Posyandu Melati I"
                            required
                            autocomplete="off"
                            class="w-full px-3.5 py-3 rounded-[10px] border text-sm text-ink placeholder:text-ink-tertiary bg-white transition-colors
                                focus:outline-none focus:ring-2 focus:ring-primary-teal focus:ring-offset-0
                                {{ $errors->has('name') ? 'border-red-400 bg-red-50' : 'border-hairline focus:border-primary-teal' }}"
                            aria-describedby="{{ $errors->has('name') ? 'error-name' : 'hint-name' }}"
                        />
                        @error('name')
                            <p id="error-name" role="alert" class="flex items-center gap-1.5 text-xs text-red-600 mt-0.5">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                                {{ $message }}
                            </p>
                        @else
                            <p id="hint-name" class="text-xs text-ink-subtle">
                                Nama harus unik dan belum pernah terdaftar sebelumnya.
                            </p>
                        @enderror
                    </div>

                    {{-- Field: Alamat Lengkap --}}
                    <div class="flex flex-col gap-1.5">
                        <label for="input-address" class="text-sm font-semibold text-ink">
                            Alamat Lengkap
                            <span class="text-red-500 ml-0.5" aria-hidden="true">*</span>
                        </label>
                        <textarea
                            name="address"
                            id="input-address"
                            rows="3"
                            placeholder="Contoh: Jl. Kenanga No. 12, RT 03/RW 05"
                            required
                            class="w-full px-3.5 py-3 rounded-[10px] border text-sm text-ink placeholder:text-ink-tertiary bg-white resize-vertical transition-colors
                                focus:outline-none focus:ring-2 focus:ring-primary-teal focus:ring-offset-0
                                {{ $errors->has('address') ? 'border-red-400 bg-red-50' : 'border-hairline focus:border-primary-teal' }}"
                            aria-describedby="{{ $errors->has('address') ? 'error-address' : null }}"
                        >{{ old('address') }}</textarea>
                        @error('address')
                            <p id="error-address" role="alert" class="flex items-center gap-1.5 text-xs text-red-600 mt-0.5">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>

                {{-- ── Section 2: Lokasi Wilayah ──────────────────────────────── --}}
                <div class="bg-white border border-hairline rounded-xl p-6 md:p-8 shadow-sm mb-6">

                    {{-- Section Header --}}
                    <div class="flex items-center gap-3 mb-6 pb-4 border-b border-hairline">
                        <div class="h-9 w-9 rounded-lg bg-primary-light flex items-center justify-center shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary-teal" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-lg font-bold text-ink leading-none">Lokasi Wilayah</h2>
                            <p class="text-xs text-ink-subtle mt-0.5">Digunakan untuk pemetaan dan analisis prevalensi per wilayah</p>
                        </div>
                    </div>

                    {{-- Field: Desa / Kelurahan --}}
                    <div class="flex flex-col gap-1.5 mb-6">
                        <label for="input-village" class="text-sm font-semibold text-ink">
                            Desa / Kelurahan
                            <span class="text-red-500 ml-0.5" aria-hidden="true">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute left-3.5 top-1/2 -translate-y-1/2 pointer-events-none" aria-hidden="true">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-ink-subtle" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                </svg>
                            </div>
                            <input
                                type="text"
                                name="village"
                                id="input-village"
                                value="{{ old('village') }}"
                                placeholder="Contoh: Desa Sukamaju"
                                required
                                class="w-full pl-10 pr-3.5 py-3 rounded-[10px] border text-sm text-ink placeholder:text-ink-tertiary bg-white transition-colors
                                    focus:outline-none focus:ring-2 focus:ring-primary-teal focus:ring-offset-0
                                    {{ $errors->has('village') ? 'border-red-400 bg-red-50' : 'border-hairline focus:border-primary-teal' }}"
                                aria-describedby="{{ $errors->has('village') ? 'error-village' : null }}"
                            />
                        </div>
                        @error('village')
                            <p id="error-village" role="alert" class="flex items-center gap-1.5 text-xs text-red-600 mt-0.5">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Field: Kecamatan --}}
                    <div class="flex flex-col gap-1.5 mb-6">
                        <label for="input-district" class="text-sm font-semibold text-ink">
                            Kecamatan
                            <span class="text-red-500 ml-0.5" aria-hidden="true">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute left-3.5 top-1/2 -translate-y-1/2 pointer-events-none" aria-hidden="true">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-ink-subtle" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                                </svg>
                            </div>
                            <input
                                type="text"
                                name="district"
                                id="input-district"
                                value="{{ old('district') }}"
                                placeholder="Contoh: Kec. Cikaret"
                                required
                                class="w-full pl-10 pr-3.5 py-3 rounded-[10px] border text-sm text-ink placeholder:text-ink-tertiary bg-white transition-colors
                                    focus:outline-none focus:ring-2 focus:ring-primary-teal focus:ring-offset-0
                                    {{ $errors->has('district') ? 'border-red-400 bg-red-50' : 'border-hairline focus:border-primary-teal' }}"
                                aria-describedby="{{ $errors->has('district') ? 'error-district' : null }}"
                            />
                        </div>
                        @error('district')
                            <p id="error-district" role="alert" class="flex items-center gap-1.5 text-xs text-red-600 mt-0.5">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Field: Kabupaten / Kota --}}
                    <div class="flex flex-col gap-1.5">
                        <label for="input-city" class="text-sm font-semibold text-ink">
                            Kabupaten / Kota
                            <span class="text-red-500 ml-0.5" aria-hidden="true">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute left-3.5 top-1/2 -translate-y-1/2 pointer-events-none" aria-hidden="true">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-ink-subtle" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"/>
                                </svg>
                            </div>
                            <input
                                type="text"
                                name="city"
                                id="input-city"
                                value="{{ old('city') }}"
                                placeholder="Contoh: Kab. Bogor"
                                required
                                class="w-full pl-10 pr-3.5 py-3 rounded-[10px] border text-sm text-ink placeholder:text-ink-tertiary bg-white transition-colors
                                    focus:outline-none focus:ring-2 focus:ring-primary-teal focus:ring-offset-0
                                    {{ $errors->has('city') ? 'border-red-400 bg-red-50' : 'border-hairline focus:border-primary-teal' }}"
                                aria-describedby="{{ $errors->has('city') ? 'error-city' : null }}"
                            />
                        </div>
                        @error('city')
                            <p id="error-city" role="alert" class="flex items-center gap-1.5 text-xs text-red-600 mt-0.5">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>

                {{-- ── Info Box (info-box pattern dari DESIGN.md) ─────────────── --}}
                <div class="flex gap-3 rounded-[10px] p-4 mb-8"
                    style="background-color: #E0F2FE; border-left: 3px solid #0369A1;">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="#0369A1" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="text-sm text-ink leading-relaxed">
                        <strong class="font-semibold" style="color: #0369A1;">Catatan:</strong>
                        Setelah posyandu terdaftar, Anda dapat menambahkan akun kader melalui menu
                        <strong class="font-semibold">Manajemen Kader</strong>
                        dan mengaitkan kader ke posyandu ini.
                    </p>
                </div>

                {{-- ── Action Buttons ──────────────────────────────────────────── --}}
                <div class="flex flex-col sm:flex-row items-center gap-3 sm:gap-4">

                    {{-- Primary: Simpan (button-primary dari DESIGN.md) --}}
                    <button
                        type="submit"
                        id="btn-submit"
                        class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 rounded-[10px] bg-primary-teal text-white text-sm font-semibold transition-colors
                            hover:bg-opacity-90 focus:outline-none focus:ring-2 focus:ring-primary-teal focus:ring-offset-2 min-h-[48px]"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                        Daftarkan Posyandu
                    </button>

                    {{-- Secondary: Batal (button-secondary dari DESIGN.md) --}}
                    <a
                        href="{{ route('posyandu.index') }}"
                        class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 rounded-[10px] border border-primary-teal text-primary-teal text-sm font-semibold bg-white transition-colors
                            hover:bg-primary-light focus:outline-none focus:ring-2 focus:ring-primary-teal focus:ring-offset-2 min-h-[48px]"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Kembali
                    </a>
                </div>

            </form>

        </div>{{-- /max-w-2xl --}}
    </div>{{-- /min-h-screen --}}

    {{-- ── Live preview: isi nama di header saat mengetik ───────────────── --}}
    <script>
        (function () {
            const nameInput   = document.getElementById('input-name');
            const submitBtn   = document.getElementById('btn-submit');

            if (!nameInput || !submitBtn) return;

            // Prevent double-submit
            document.getElementById('form-daftar-posyandu').addEventListener('submit', function () {
                submitBtn.disabled = true;
                submitBtn.textContent = 'Menyimpan...';
            });
        })();
    </script>
</x-layouts::app>
