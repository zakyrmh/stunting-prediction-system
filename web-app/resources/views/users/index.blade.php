<x-layouts::app :title="__('Manajemen Kader')">
    <div class="flex flex-col gap-6 bg-canvas text-ink font-sans min-h-screen p-4 md:p-6">

        {{-- ── Header ────────────────────────────────────────────────────── --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-hairline">
            <div>
                <flux:heading size="xl" class="font-bold text-ink">Manajemen Akun Kader</flux:heading>
                <flux:text class="mt-1 text-ink-muted">
                    Kelola seluruh akun operator kader posyandu – tambah, aktifkan/nonaktifkan, dan hapus akun secara terpusat.
                </flux:text>
            </div>

            {{-- Trigger buka modal tambah kader --}}
            <button
                id="btn-open-modal"
                onclick="document.getElementById('modal-tambah-kader').classList.remove('hidden'); document.getElementById('modal-backdrop').classList.remove('hidden')"
                class="inline-flex items-center gap-2 bg-primary-teal text-white text-sm font-semibold px-4 py-2.5 rounded-lg shadow hover:bg-primary-teal/90 transition-all cursor-pointer shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                Tambah Akun Kader
            </button>
        </div>

        {{-- ── Flash Message ──────────────────────────────────────────────── --}}
        @if(session('success'))
            <div id="flash-success" class="flex items-center gap-3 bg-risk-low-surface border border-risk-low/40 text-risk-low rounded-lg px-4 py-3 text-sm font-medium">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                {{ session('success') }}
                <button onclick="document.getElementById('flash-success').remove()" class="ml-auto text-risk-low/70 hover:text-risk-low cursor-pointer">✕</button>
            </div>
        @endif

        {{-- ── Panel Atas: Statistik & Ringkasan ─────────────────────────── --}}
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3">
            <div class="bg-surface-1 border border-hairline rounded-xl p-4 flex flex-col gap-1">
                <span class="text-xs text-ink-subtle font-semibold uppercase tracking-wide">Total Kader</span>
                <span class="text-3xl font-black text-ink font-mono">{{ $stats['total_kader'] }}</span>
                <span class="text-xs text-ink-muted">Akun terdaftar</span>
            </div>
            <div class="bg-surface-1 border border-hairline rounded-xl p-4 flex flex-col gap-1">
                <span class="text-xs text-ink-subtle font-semibold uppercase tracking-wide">Kader Aktif</span>
                <span class="text-3xl font-black text-risk-low font-mono">{{ $stats['active_kader'] }}</span>
                <span class="text-xs text-ink-muted">Dapat login</span>
            </div>
            <div class="bg-surface-1 border border-hairline rounded-xl p-4 flex flex-col gap-1">
                <span class="text-xs text-ink-subtle font-semibold uppercase tracking-wide">Nonaktif</span>
                <span class="text-3xl font-black text-risk-high font-mono">{{ $stats['inactive_kader'] }}</span>
                <span class="text-xs text-ink-muted">Akses diblokir</span>
            </div>
            <div class="bg-surface-1 border border-hairline rounded-xl p-4 flex flex-col gap-1">
                <span class="text-xs text-ink-subtle font-semibold uppercase tracking-wide">Total Posyandu</span>
                <span class="text-3xl font-black text-ink font-mono">{{ $stats['total_posyandu'] }}</span>
                <span class="text-xs text-ink-muted">Wilayah terdaftar</span>
            </div>
            <div class="bg-surface-1 border border-hairline rounded-xl p-4 flex flex-col gap-1 {{ $stats['posyandu_without_kader'] > 0 ? 'border-risk-medium/40 bg-risk-medium-surface/30' : '' }}">
                <span class="text-xs text-ink-subtle font-semibold uppercase tracking-wide">Tanpa Kader</span>
                <span class="text-3xl font-black {{ $stats['posyandu_without_kader'] > 0 ? 'text-risk-medium' : 'text-risk-low' }} font-mono">{{ $stats['posyandu_without_kader'] }}</span>
                <span class="text-xs text-ink-muted">Posyandu perlu kader</span>
            </div>
        </div>

        {{-- ── Komponen Utama: Tabel + Filter ────────────────────────────── --}}
        <div class="bg-surface-1 border border-hairline rounded-xl shadow-sm flex flex-col">

            {{-- Header Tabel + Filter --}}
            <div class="p-5 border-b border-hairline flex flex-col md:flex-row md:items-center justify-between gap-3">
                <div>
                    <h3 class="text-headline font-bold text-ink">Daftar Akun Operator Kader</h3>
                    <p class="text-xs text-ink-subtle mt-0.5">Menampilkan {{ $kaders->total() }} akun kader ditemukan</p>
                </div>

                {{-- Filter Form --}}
                <form method="GET" action="{{ route('users.index') }}" class="flex flex-wrap items-center gap-2" id="filter-form">
                    {{-- Search --}}
                    <div class="relative">
                        <svg xmlns="http://www.w3.org/2000/svg" class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-ink-subtle pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <input
                            type="text"
                            name="search"
                            id="search-input"
                            value="{{ $filters['search'] ?? '' }}"
                            placeholder="Cari nama, email, atau HP..."
                            class="pl-9 pr-3 py-2 text-sm bg-canvas border border-hairline rounded-lg text-ink placeholder:text-ink-subtle focus:outline-none focus:ring-2 focus:ring-primary-teal/40 w-52"
                        />
                    </div>

                    {{-- Filter Status --}}
                    <select
                        name="status"
                        id="status-filter"
                        onchange="this.form.submit()"
                        class="text-sm bg-canvas border border-hairline rounded-lg text-ink px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-teal/40 cursor-pointer">
                        <option value="">Semua Status</option>
                        <option value="1" {{ isset($filters['status']) && $filters['status'] === '1' ? 'selected' : '' }}>Aktif</option>
                        <option value="0" {{ isset($filters['status']) && $filters['status'] === '0' ? 'selected' : '' }}>Nonaktif</option>
                    </select>

                    {{-- Filter Posyandu --}}
                    <select
                        name="posyandu_id"
                        id="posyandu-filter"
                        onchange="this.form.submit()"
                        class="text-sm bg-canvas border border-hairline rounded-lg text-ink px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-teal/40 cursor-pointer">
                        <option value="">Semua Posyandu</option>
                        @foreach($posyandus as $p)
                            <option value="{{ $p->id }}" {{ isset($filters['posyandu_id']) && $filters['posyandu_id'] == $p->id ? 'selected' : '' }}>
                                {{ $p->name }}
                            </option>
                        @endforeach
                    </select>

                    <button type="submit" class="text-sm bg-primary-teal text-white px-3 py-2 rounded-lg font-medium hover:bg-primary-teal/90 transition-all cursor-pointer">
                        Cari
                    </button>

                    @if(!empty($filters['search']) || isset($filters['status']) || !empty($filters['posyandu_id']))
                        <a href="{{ route('users.index') }}" class="text-sm text-ink-muted hover:text-ink underline px-1">Reset</a>
                    @endif
                </form>
            </div>

            {{-- Tabel --}}
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-canvas/60 border-b border-hairline">
                        <tr class="text-left">
                            <th class="px-5 py-3 text-xs font-bold text-ink-subtle uppercase tracking-wider">#</th>
                            <th class="px-5 py-3 text-xs font-bold text-ink-subtle uppercase tracking-wider">Kader</th>
                            <th class="px-5 py-3 text-xs font-bold text-ink-subtle uppercase tracking-wider">Email</th>
                            <th class="px-5 py-3 text-xs font-bold text-ink-subtle uppercase tracking-wider">No. HP/WA</th>
                            <th class="px-5 py-3 text-xs font-bold text-ink-subtle uppercase tracking-wider">Posyandu</th>
                            <th class="px-5 py-3 text-xs font-bold text-ink-subtle uppercase tracking-wider">Status</th>
                            <th class="px-5 py-3 text-xs font-bold text-ink-subtle uppercase tracking-wider">Bergabung</th>
                            <th class="px-5 py-3 text-xs font-bold text-ink-subtle uppercase tracking-wider text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-hairline-soft">
                        @forelse($kaders as $index => $kader)
                            <tr class="hover:bg-canvas/40 transition-colors group" id="row-kader-{{ $kader->id }}">
                                {{-- No urut --}}
                                <td class="px-5 py-3.5 text-ink-muted font-mono text-xs">
                                    {{ $kaders->firstItem() + $index }}
                                </td>

                                {{-- Avatar + Nama --}}
                                <td class="px-5 py-3.5">
                                    <div class="flex items-center gap-3">
                                        <div class="h-9 w-9 rounded-full bg-primary-teal/10 border border-primary-teal/20 flex items-center justify-center shrink-0">
                                            <span class="text-primary-teal text-xs font-black">{{ strtoupper(substr($kader->name, 0, 2)) }}</span>
                                        </div>
                                        <div>
                                            <p class="font-semibold text-ink leading-none">{{ $kader->name }}</p>
                                            <p class="text-xs text-ink-subtle mt-0.5">ID #{{ $kader->id }}</p>
                                        </div>
                                    </div>
                                </td>

                                {{-- Email --}}
                                <td class="px-5 py-3.5 text-ink-muted font-mono text-xs">
                                    {{ $kader->email }}
                                </td>

                                {{-- Nomor HP --}}
                                <td class="px-5 py-3.5 text-ink-muted text-xs">
                                    {{ $kader->phone ?? '—' }}
                                </td>

                                {{-- Posyandu --}}
                                <td class="px-5 py-3.5">
                                    @if($kader->posyandu)
                                        <span class="inline-flex items-center gap-1.5 text-xs font-medium text-ink bg-canvas px-2.5 py-1 rounded-md border border-hairline">
                                            <span class="h-1.5 w-1.5 rounded-full bg-primary-teal shrink-0"></span>
                                            {{ $kader->posyandu->name }}
                                        </span>
                                    @else
                                        <span class="text-xs text-ink-subtle italic">Belum ditugaskan</span>
                                    @endif
                                </td>

                                {{-- Status Badge --}}
                                <td class="px-5 py-3.5">
                                    @if($kader->is_active)
                                        <span class="inline-flex items-center gap-1.5 text-[11px] font-bold px-2.5 py-1 bg-risk-low-surface text-risk-low rounded-full">
                                            <span class="h-1.5 w-1.5 rounded-full bg-risk-low animate-pulse"></span>
                                            Aktif
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 text-[11px] font-bold px-2.5 py-1 bg-risk-high-surface text-risk-high rounded-full">
                                            <span class="h-1.5 w-1.5 rounded-full bg-risk-high"></span>
                                            Nonaktif
                                        </span>
                                    @endif
                                </td>

                                {{-- Tanggal Bergabung --}}
                                <td class="px-5 py-3.5 text-xs text-ink-muted font-mono">
                                    {{ $kader->created_at->format('d/m/Y') }}
                                </td>

                                {{-- Aksi --}}
                                <td class="px-5 py-3.5">
                                    <div class="flex items-center justify-center gap-1.5 opacity-0 group-hover:opacity-100 transition-opacity">

                                        {{-- Edit --}}
                                        <button
                                            type="button"
                                            title="Edit akun kader"
                                            data-edit-url="{{ route('users.edit', $kader) }}"
                                            data-update-url="{{ route('users.update', $kader) }}"
                                            data-kader-name="{{ $kader->name }}"
                                            onclick="openEditModal(this)"
                                            class="h-8 w-8 flex items-center justify-center rounded-lg border border-info/30 bg-info-surface text-info hover:bg-info/15 transition-all cursor-pointer">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </button>

                                        {{-- Toggle Status --}}
                                        <form method="POST" action="{{ route('users.toggle-active', $kader) }}" id="form-toggle-{{ $kader->id }}">
                                            @csrf
                                            @method('PATCH')
                                            <button
                                                type="submit"
                                                title="{{ $kader->is_active ? 'Nonaktifkan' : 'Aktifkan' }} akun"
                                                class="h-8 w-8 flex items-center justify-center rounded-lg border transition-all cursor-pointer
                                                    {{ $kader->is_active
                                                        ? 'border-risk-medium/40 bg-risk-medium-surface text-risk-medium hover:bg-risk-medium/20'
                                                        : 'border-risk-low/40 bg-risk-low-surface text-risk-low hover:bg-risk-low/20' }}">
                                                @if($kader->is_active)
                                                    {{-- Pause / block icon --}}
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                                    </svg>
                                                @else
                                                    {{-- Check / activate icon --}}
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    </svg>
                                                @endif
                                            </button>
                                        </form>

                                        {{-- Delete --}}
                                        <form method="POST" action="{{ route('users.destroy', $kader) }}" id="form-delete-{{ $kader->id }}"
                                            onsubmit="return confirm('Hapus akun {{ addslashes($kader->name) }}? Tindakan ini tidak dapat dibatalkan.')">
                                            @csrf
                                            @method('DELETE')
                                            <button
                                                type="submit"
                                                title="Hapus akun kader"
                                                class="h-8 w-8 flex items-center justify-center rounded-lg border border-risk-high/30 bg-risk-high-surface text-risk-high hover:bg-risk-high/20 transition-all cursor-pointer">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-5 py-16 text-center">
                                    <div class="flex flex-col items-center gap-3 text-ink-subtle">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 opacity-30" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        <p class="font-semibold text-ink">Tidak ada kader ditemukan</p>
                                        <p class="text-sm">Belum ada akun kader yang sesuai dengan filter saat ini.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($kaders->hasPages())
                <div class="px-5 py-4 border-t border-hairline flex items-center justify-between">
                    <p class="text-xs text-ink-muted">
                        Menampilkan {{ $kaders->firstItem() }}–{{ $kaders->lastItem() }} dari {{ $kaders->total() }} kader
                    </p>
                    <div class="flex items-center gap-1">
                        {{-- Prev --}}
                        @if($kaders->onFirstPage())
                            <span class="px-3 py-1.5 text-xs text-ink-subtle border border-hairline rounded-lg cursor-not-allowed opacity-50">← Sebelumnya</span>
                        @else
                            <a href="{{ $kaders->previousPageUrl() }}" class="px-3 py-1.5 text-xs text-ink border border-hairline rounded-lg hover:bg-canvas transition-colors">← Sebelumnya</a>
                        @endif

                        {{-- Page numbers --}}
                        @foreach($kaders->getUrlRange(1, $kaders->lastPage()) as $page => $url)
                            @if($page == $kaders->currentPage())
                                <span class="px-3 py-1.5 text-xs font-bold bg-primary-teal text-white rounded-lg">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}" class="px-3 py-1.5 text-xs text-ink border border-hairline rounded-lg hover:bg-canvas transition-colors">{{ $page }}</a>
                            @endif
                        @endforeach

                        {{-- Next --}}
                        @if($kaders->hasMorePages())
                            <a href="{{ $kaders->nextPageUrl() }}" class="px-3 py-1.5 text-xs text-ink border border-hairline rounded-lg hover:bg-canvas transition-colors">Selanjutnya →</a>
                        @else
                            <span class="px-3 py-1.5 text-xs text-ink-subtle border border-hairline rounded-lg cursor-not-allowed opacity-50">Selanjutnya →</span>
                        @endif
                    </div>
                </div>
            @endif
        </div>

    </div>

    {{-- ═══════════════════════════════════════════════════════════════════ --}}
    {{-- Modal: Tambah Akun Kader                                          --}}
    {{-- ═══════════════════════════════════════════════════════════════════ --}}

    {{-- Backdrop --}}
    <div id="modal-backdrop"
        onclick="closeModal()"
        class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm z-40 transition-opacity">
    </div>

    {{-- Modal Panel --}}
    <div id="modal-tambah-kader"
        class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="bg-surface-1 border border-hairline rounded-2xl shadow-2xl w-full max-w-lg flex flex-col max-h-[90vh] overflow-y-auto">

            {{-- Modal Header --}}
            <div class="flex items-center justify-between p-6 border-b border-hairline">
                <div>
                    <h2 class="text-headline font-bold text-ink">Tambah Akun Kader Baru</h2>
                    <p class="text-xs text-ink-subtle mt-0.5">Isi formulir di bawah untuk mendaftarkan kader baru ke sistem.</p>
                </div>
                <button onclick="closeModal()" class="h-8 w-8 rounded-lg text-ink-subtle hover:bg-canvas hover:text-ink flex items-center justify-center transition-colors cursor-pointer text-xl leading-none">✕</button>
            </div>

            {{-- Modal Body: Form --}}
            <form method="POST" action="{{ route('users.store') }}" class="p-6 flex flex-col gap-5" id="form-tambah-kader">
                @csrf

                {{-- Validation Errors --}}
                @if($errors->any())
                    <div class="bg-risk-high-surface border border-risk-high/30 rounded-lg p-3 text-risk-high text-sm" id="form-errors">
                        <p class="font-semibold mb-1">Terdapat kesalahan input:</p>
                        <ul class="list-disc list-inside space-y-0.5 text-xs">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Nama Lengkap --}}
                <div class="flex flex-col gap-1.5">
                    <label for="input-name" class="text-sm font-semibold text-ink">Nama Lengkap <span class="text-risk-high">*</span></label>
                    <input
                        type="text"
                        name="name"
                        id="input-name"
                        value="{{ old('name') }}"
                        placeholder="Contoh: Siti Rahayu"
                        required
                        class="text-sm bg-canvas border {{ $errors->has('name') ? 'border-risk-high' : 'border-hairline' }} rounded-lg text-ink px-3 py-2.5 placeholder:text-ink-subtle focus:outline-none focus:ring-2 focus:ring-primary-teal/40 w-full"
                    />
                    @error('name')<p class="text-xs text-risk-high mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- Email --}}
                <div class="flex flex-col gap-1.5">
                    <label for="input-email" class="text-sm font-semibold text-ink">Alamat Email <span class="text-risk-high">*</span></label>
                    <input
                        type="email"
                        name="email"
                        id="input-email"
                        value="{{ old('email') }}"
                        placeholder="kader@example.com"
                        required
                        class="text-sm bg-canvas border {{ $errors->has('email') ? 'border-risk-high' : 'border-hairline' }} rounded-lg text-ink px-3 py-2.5 placeholder:text-ink-subtle focus:outline-none focus:ring-2 focus:ring-primary-teal/40 w-full"
                    />
                    @error('email')<p class="text-xs text-risk-high mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- Nomor HP --}}
                <div class="flex flex-col gap-1.5">
                    <label for="input-phone" class="text-sm font-semibold text-ink">Nomor HP/WA <span class="text-ink-subtle font-normal">(opsional)</span></label>
                    <input
                        type="tel"
                        name="phone"
                        id="input-phone"
                        value="{{ old('phone') }}"
                        placeholder="08xxxxxxxxxx"
                        class="text-sm bg-canvas border {{ $errors->has('phone') ? 'border-risk-high' : 'border-hairline' }} rounded-lg text-ink px-3 py-2.5 placeholder:text-ink-subtle focus:outline-none focus:ring-2 focus:ring-primary-teal/40 w-full"
                    />
                    @error('phone')<p class="text-xs text-risk-high mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- Posyandu --}}
                <div class="flex flex-col gap-1.5">
                    <label for="input-posyandu" class="text-sm font-semibold text-ink">Posyandu <span class="text-risk-high">*</span></label>
                    <select
                        name="posyandu_id"
                        id="input-posyandu"
                        required
                        class="text-sm bg-canvas border {{ $errors->has('posyandu_id') ? 'border-risk-high' : 'border-hairline' }} rounded-lg text-ink px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-primary-teal/40 w-full cursor-pointer">
                        <option value="">— Pilih Posyandu —</option>
                        @foreach($posyandus as $p)
                            <option value="{{ $p->id }}" {{ old('posyandu_id') == $p->id ? 'selected' : '' }}>
                                {{ $p->name }} – {{ $p->village }}
                            </option>
                        @endforeach
                    </select>
                    @error('posyandu_id')<p class="text-xs text-risk-high mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- Password --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="flex flex-col gap-1.5">
                        <label for="input-password" class="text-sm font-semibold text-ink">Kata Sandi <span class="text-risk-high">*</span></label>
                        <input
                            type="password"
                            name="password"
                            id="input-password"
                            placeholder="Min. 8 karakter"
                            required
                            minlength="8"
                            class="text-sm bg-canvas border {{ $errors->has('password') ? 'border-risk-high' : 'border-hairline' }} rounded-lg text-ink px-3 py-2.5 placeholder:text-ink-subtle focus:outline-none focus:ring-2 focus:ring-primary-teal/40 w-full"
                        />
                        @error('password')<p class="text-xs text-risk-high mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div class="flex flex-col gap-1.5">
                        <label for="input-password-confirm" class="text-sm font-semibold text-ink">Konfirmasi Sandi <span class="text-risk-high">*</span></label>
                        <input
                            type="password"
                            name="password_confirmation"
                            id="input-password-confirm"
                            placeholder="Ulangi kata sandi"
                            required
                            class="text-sm bg-canvas border border-hairline rounded-lg text-ink px-3 py-2.5 placeholder:text-ink-subtle focus:outline-none focus:ring-2 focus:ring-primary-teal/40 w-full"
                        />
                    </div>
                </div>

                {{-- Info Note --}}
                <div class="bg-primary-light/40 border border-primary-teal/20 rounded-lg p-3 text-xs text-ink-muted leading-relaxed">
                    <strong class="text-primary-teal">Catatan:</strong>
                    Akun yang dibuat akan langsung aktif dan dapat digunakan kader untuk login. Role akan otomatis ditetapkan sebagai <strong>Kader Posyandu</strong>.
                </div>

                {{-- Actions --}}
                <div class="flex items-center justify-end gap-3 pt-2 border-t border-hairline">
                    <button
                        type="button"
                        onclick="closeModal()"
                        class="text-sm text-ink-muted hover:text-ink border border-hairline rounded-lg px-4 py-2 hover:bg-canvas transition-colors cursor-pointer">
                        Batal
                    </button>
                    <button
                        type="submit"
                        class="inline-flex items-center gap-2 text-sm font-semibold bg-primary-teal text-white px-5 py-2 rounded-lg hover:bg-primary-teal/90 transition-all cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                        </svg>
                        Buat Akun Kader
                    </button>
                </div>
            </form>

        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════════════════ --}}
    {{-- Modal: Edit Akun Kader                                            --}}
    {{-- ═══════════════════════════════════════════════════════════════════ --}}

    {{-- Backdrop Edit (shared with tambah backdrop logic) --}}
    <div id="modal-edit-backdrop"
        onclick="closeEditModal()"
        class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm z-40 transition-opacity">
    </div>

    {{-- Modal Edit Panel --}}
    <div id="modal-edit-kader"
        class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="bg-surface-1 border border-hairline rounded-2xl shadow-2xl w-full max-w-lg flex flex-col max-h-[90vh] overflow-y-auto">

            {{-- Modal Header --}}
            <div class="flex items-center justify-between p-6 border-b border-hairline">
                <div>
                    <h2 class="text-headline font-bold text-ink">Edit Akun Kader</h2>
                    <p class="text-xs text-ink-subtle mt-0.5" id="edit-modal-subtitle">Perbarui informasi akun kader.</p>
                </div>
                <button onclick="closeEditModal()" class="h-8 w-8 rounded-lg text-ink-subtle hover:bg-canvas hover:text-ink flex items-center justify-center transition-colors cursor-pointer text-xl leading-none">✕</button>
            </div>

            {{-- Loading State --}}
            <div id="edit-modal-loading" class="flex items-center justify-center py-16 hidden">
                <div class="flex flex-col items-center gap-3 text-ink-subtle">
                    <svg class="animate-spin h-8 w-8 text-primary-teal" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                    </svg>
                    <span class="text-sm">Memuat data kader...</span>
                </div>
            </div>

            {{-- Modal Body: Form Edit --}}
            <form method="POST" id="form-edit-kader" class="p-6 flex flex-col gap-5">
                @csrf
                @method('PUT')
                {{-- action diisi dinamis oleh JS --}}

                {{-- Validation Errors dari update --}}
                @if($errors->updateKader->any())
                    <div class="bg-risk-high-surface border border-risk-high/30 rounded-lg p-3 text-risk-high text-sm">
                        <p class="font-semibold mb-1">Terdapat kesalahan input:</p>
                        <ul class="list-disc list-inside space-y-0.5 text-xs">
                            @foreach($errors->updateKader->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Nama Lengkap --}}
                <div class="flex flex-col gap-1.5">
                    <label for="edit-name" class="text-sm font-semibold text-ink">Nama Lengkap <span class="text-risk-high">*</span></label>
                    <input
                        type="text"
                        name="name"
                        id="edit-name"
                        placeholder="Contoh: Siti Rahayu"
                        required
                        value="{{ old('name') }}"
                        class="text-sm bg-canvas border {{ $errors->updateKader->has('name') ? 'border-risk-high' : 'border-hairline' }} rounded-lg text-ink px-3 py-2.5 placeholder:text-ink-subtle focus:outline-none focus:ring-2 focus:ring-primary-teal/40 w-full"
                    />
                    @if($errors->updateKader->has('name'))
                        <p class="text-xs text-risk-high mt-1">{{ $errors->updateKader->first('name') }}</p>
                    @endif
                </div>

                {{-- Email --}}
                <div class="flex flex-col gap-1.5">
                    <label for="edit-email" class="text-sm font-semibold text-ink">Alamat Email <span class="text-risk-high">*</span></label>
                    <input
                        type="email"
                        name="email"
                        id="edit-email"
                        placeholder="kader@example.com"
                        required
                        value="{{ old('email') }}"
                        class="text-sm bg-canvas border {{ $errors->updateKader->has('email') ? 'border-risk-high' : 'border-hairline' }} rounded-lg text-ink px-3 py-2.5 placeholder:text-ink-subtle focus:outline-none focus:ring-2 focus:ring-primary-teal/40 w-full"
                    />
                    @if($errors->updateKader->has('email'))
                        <p class="text-xs text-risk-high mt-1">{{ $errors->updateKader->first('email') }}</p>
                    @endif
                </div>

                {{-- Nomor HP --}}
                <div class="flex flex-col gap-1.5">
                    <label for="edit-phone" class="text-sm font-semibold text-ink">Nomor HP/WA <span class="text-ink-subtle font-normal">(opsional)</span></label>
                    <input
                        type="tel"
                        name="phone"
                        id="edit-phone"
                        placeholder="08xxxxxxxxxx"
                        value="{{ old('phone') }}"
                        class="text-sm bg-canvas border {{ $errors->updateKader->has('phone') ? 'border-risk-high' : 'border-hairline' }} rounded-lg text-ink px-3 py-2.5 placeholder:text-ink-subtle focus:outline-none focus:ring-2 focus:ring-primary-teal/40 w-full"
                    />
                    @if($errors->updateKader->has('phone'))
                        <p class="text-xs text-risk-high mt-1">{{ $errors->updateKader->first('phone') }}</p>
                    @endif
                </div>

                {{-- Posyandu --}}
                <div class="flex flex-col gap-1.5">
                    <label for="edit-posyandu" class="text-sm font-semibold text-ink">Posyandu <span class="text-risk-high">*</span></label>
                    <select
                        name="posyandu_id"
                        id="edit-posyandu"
                        required
                        class="text-sm bg-canvas border {{ $errors->updateKader->has('posyandu_id') ? 'border-risk-high' : 'border-hairline' }} rounded-lg text-ink px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-primary-teal/40 w-full cursor-pointer">
                        <option value="">— Pilih Posyandu —</option>
                        @foreach($posyandus as $p)
                            <option value="{{ $p->id }}" {{ old('posyandu_id') == $p->id ? 'selected' : '' }}>
                                {{ $p->name }} – {{ $p->village }}
                            </option>
                        @endforeach
                    </select>
                    @if($errors->updateKader->has('posyandu_id'))
                        <p class="text-xs text-risk-high mt-1">{{ $errors->updateKader->first('posyandu_id') }}</p>
                    @endif
                </div>

                {{-- Password Baru (Opsional) --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="flex flex-col gap-1.5">
                        <label for="edit-password" class="text-sm font-semibold text-ink">
                            Kata Sandi Baru
                            <span class="text-ink-subtle font-normal">(opsional)</span>
                        </label>
                        <input
                            type="password"
                            name="password"
                            id="edit-password"
                            placeholder="Kosongkan jika tidak diubah"
                            minlength="8"
                            class="text-sm bg-canvas border {{ $errors->updateKader->has('password') ? 'border-risk-high' : 'border-hairline' }} rounded-lg text-ink px-3 py-2.5 placeholder:text-ink-subtle focus:outline-none focus:ring-2 focus:ring-primary-teal/40 w-full"
                        />
                        @if($errors->updateKader->has('password'))
                            <p class="text-xs text-risk-high mt-1">{{ $errors->updateKader->first('password') }}</p>
                        @endif
                    </div>
                    <div class="flex flex-col gap-1.5">
                        <label for="edit-password-confirm" class="text-sm font-semibold text-ink">Konfirmasi Sandi</label>
                        <input
                            type="password"
                            name="password_confirmation"
                            id="edit-password-confirm"
                            placeholder="Ulangi kata sandi baru"
                            class="text-sm bg-canvas border border-hairline rounded-lg text-ink px-3 py-2.5 placeholder:text-ink-subtle focus:outline-none focus:ring-2 focus:ring-primary-teal/40 w-full"
                        />
                    </div>
                </div>

                {{-- Info Note --}}
                <div class="bg-info-surface border border-info/20 rounded-lg p-3 text-xs text-ink-muted leading-relaxed">
                    <strong class="text-info">Catatan:</strong>
                    Biarkan kolom kata sandi kosong jika tidak ingin mengubah password kader ini.
                </div>

                {{-- Actions --}}
                <div class="flex items-center justify-end gap-3 pt-2 border-t border-hairline">
                    <button
                        type="button"
                        onclick="closeEditModal()"
                        class="text-sm text-ink-muted hover:text-ink border border-hairline rounded-lg px-4 py-2 hover:bg-canvas transition-colors cursor-pointer">
                        Batal
                    </button>
                    <button
                        type="submit"
                        class="inline-flex items-center gap-2 text-sm font-semibold bg-primary-teal text-white px-5 py-2 rounded-lg hover:bg-primary-teal/90 transition-all cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                        Simpan Perubahan
                    </button>
                </div>
            </form>

        </div>
    </div>

    {{-- Script: close modal & auto-open jika ada errors --}}
    <script>
        function closeModal() {
            document.getElementById('modal-tambah-kader').classList.add('hidden');
            document.getElementById('modal-backdrop').classList.add('hidden');
        }

        function closeEditModal() {
            document.getElementById('modal-edit-kader').classList.add('hidden');
            document.getElementById('modal-edit-backdrop').classList.add('hidden');
        }

        /**
         * Buka modal edit, fetch data kader via AJAX, lalu populate form.
         * @param {HTMLElement} btn — tombol edit yang diklik (mengandung data-* attributes)
         */
        async function openEditModal(btn) {
            const editUrl   = btn.dataset.editUrl;
            const updateUrl = btn.dataset.updateUrl;
            const kaderName = btn.dataset.kaderName;

            // Tampilkan modal dan backdrop
            document.getElementById('modal-edit-kader').classList.remove('hidden');
            document.getElementById('modal-edit-backdrop').classList.remove('hidden');

            // Tampilkan loading, sembunyikan form
            document.getElementById('edit-modal-loading').classList.remove('hidden');
            document.getElementById('form-edit-kader').classList.add('hidden');
            document.getElementById('edit-modal-subtitle').textContent = 'Memuat data ' + kaderName + '...';

            try {
                const response = await fetch(editUrl, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    }
                });

                if (!response.ok) throw new Error('Gagal memuat data kader.');

                const data = await response.json();

                // Set action form ke URL update kader
                document.getElementById('form-edit-kader').action = updateUrl;

                // Populate form fields
                document.getElementById('edit-name').value     = data.name    ?? '';
                document.getElementById('edit-email').value    = data.email   ?? '';
                document.getElementById('edit-phone').value    = data.phone   ?? '';
                document.getElementById('edit-posyandu').value = data.posyandu_id ?? '';

                // Reset password fields
                document.getElementById('edit-password').value         = '';
                document.getElementById('edit-password-confirm').value = '';

                // Update subtitle
                document.getElementById('edit-modal-subtitle').textContent =
                    'Perbarui informasi akun ' + data.name;

            } catch (err) {
                alert('Terjadi kesalahan: ' + err.message);
                closeEditModal();
                return;
            } finally {
                // Sembunyikan loading, tampilkan form
                document.getElementById('edit-modal-loading').classList.add('hidden');
                document.getElementById('form-edit-kader').classList.remove('hidden');
            }
        }

        // Auto-buka modal tambah kader jika ada validation error dari form store
        @if($errors->any() && !$errors->updateKader->any())
            document.addEventListener('DOMContentLoaded', function () {
                document.getElementById('modal-tambah-kader').classList.remove('hidden');
                document.getElementById('modal-backdrop').classList.remove('hidden');
            });
        @endif

        // Auto-buka modal edit jika ada validation error dari form update
        @if($errors->updateKader->any())
            document.addEventListener('DOMContentLoaded', function () {
                document.getElementById('modal-edit-kader').classList.remove('hidden');
                document.getElementById('modal-edit-backdrop').classList.remove('hidden');
                document.getElementById('edit-modal-loading').classList.add('hidden');
                document.getElementById('form-edit-kader').classList.remove('hidden');
                document.getElementById('edit-modal-subtitle').textContent = 'Perbaiki kesalahan input di bawah ini.';
            });
        @endif

        // Escape key menutup modal yang aktif
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                closeModal();
                closeEditModal();
            }
        });
    </script>

</x-layouts::app>
