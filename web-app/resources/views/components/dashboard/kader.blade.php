@props(['data'])

<div class="flex flex-col gap-6">
    
    <!-- 1. Widget Operasional Posyandu Hari Ini -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Stat Card 1: Balita Ditimbang -->
        <div class="bg-surface-1 border border-hairline rounded-xl p-6 shadow-sm flex flex-col justify-between min-h-[160px]">
            <div>
                <span class="text-caption font-bold text-ink-subtle uppercase tracking-wider block">Balita Ditimbang Hari Ini</span>
                <span class="text-display-md text-ink font-bold block mt-2 font-mono">{{ $data['weighedToday'] }} <span class="text-body-default font-normal text-ink-muted font-sans">anak</span></span>
            </div>
            <p class="text-caption text-risk-low font-bold mt-4 flex items-center gap-1.5">
                <span class="shrink-0 text-sm">✓</span> 
                <span class="font-normal text-ink-muted font-sans">Pencatatan posyandu berjalan aktif.</span>
            </p>
        </div>

        <!-- Stat Card 2: Target Belum Datang -->
        <div class="bg-surface-1 border border-hairline rounded-xl p-6 shadow-sm flex flex-col justify-between min-h-[160px]">
            <div>
                <span class="text-caption font-bold text-ink-subtle uppercase tracking-wider block">Belum Hadir Bulan Ini</span>
                <span class="text-display-md text-ink-muted font-bold block mt-2 font-mono">{{ $data['notWeighedThisMonth'] }} <span class="text-body-default font-normal text-ink-muted font-sans">anak</span></span>
            </div>
            <p class="text-caption text-ink-subtle mt-4 font-sans">
                Harap ingatkan orang tua untuk hadir menimbang.
            </p>
        </div>

        <!-- Notifikasi Cepat (Warning Box) -->
        <div class="bg-risk-medium-surface border-l-4 border-risk-medium rounded-xl p-5 flex flex-col justify-center gap-2 shadow-sm min-h-[160px]">
            <span class="text-caption font-bold text-risk-medium uppercase tracking-wider flex items-center gap-1.5">
                ⚠️ Peringatan Dini
            </span>
            <p class="text-body-sm text-ink leading-relaxed font-sans">
                Ada <strong class="font-bold text-risk-high">{{ $data['growthFalteringCount'] }} balita</strong> di Posyandu Anda mengalami Gagal Tumbuh (2T) bulan ini. Segera picu kuesioner gejala luar!
            </p>
        </div>
    </div>

    <!-- 2. Aksi Cepat Operasional -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <a href="{{ route('balita.form') }}" class="bg-surface-1 hover:bg-primary-light border border-hairline rounded-xl p-6 md:p-8 shadow-sm flex items-center justify-between transition-all hover:-translate-y-1 hover:shadow-md group">
            <div class="flex items-center gap-4">
                <div class="h-14 w-14 bg-primary-light text-primary-teal rounded-full flex items-center justify-center text-headline shrink-0">
                    ➕
                </div>
                <div class="flex flex-col text-left">
                    <h3 class="text-headline font-bold text-ink group-hover:text-primary-teal transition-colors">Tambah Balita Baru</h3>
                    <p class="text-body-sm text-ink-muted mt-1">Registrasi identitas awal anak dan orang tua ke dalam sistem.</p>
                </div>
            </div>
            <span class="text-headline text-primary-teal opacity-0 group-hover:opacity-100 transition-all transform translate-x-2 group-hover:translate-x-0">➔</span>
        </a>

        <a href="{{ route('prediksi.form') }}" class="bg-surface-1 hover:bg-primary-light border border-hairline rounded-xl p-6 md:p-8 shadow-sm flex items-center justify-between transition-all hover:-translate-y-1 hover:shadow-md group">
            <div class="flex items-center gap-4">
                <div class="h-14 w-14 bg-primary-light text-primary-teal rounded-full flex items-center justify-center text-headline shrink-0">
                    ⚖️
                </div>
                <div class="flex flex-col text-left">
                    <h3 class="text-headline font-bold text-ink group-hover:text-primary-teal transition-colors">Input Catatan Bulanan</h3>
                    <p class="text-body-sm text-ink-muted mt-1">Catat berat/tinggi bulanan & hitung status gizi (stunting).</p>
                </div>
            </div>
            <span class="text-headline text-primary-teal opacity-0 group-hover:opacity-100 transition-all transform translate-x-2 group-hover:translate-x-0">➔</span>
        </a>
    </div>

    <!-- 3. Tabel Aktivitas Kunjungan Terakhir -->
    <div class="bg-surface-1 border border-hairline rounded-xl p-6 shadow-sm">
        <div class="flex items-center justify-between mb-4 border-b border-hairline-soft pb-3">
            <div>
                <h3 class="text-card-title text-ink font-bold">Aktivitas Pengukuran Hari Ini</h3>
                <p class="text-body-sm text-ink-muted">Daftar entri data pengukuran balita terbaru untuk meminimalisasi salah ketik angka (human error).</p>
            </div>
            <span class="text-caption text-ink-subtle font-mono bg-canvas border border-hairline px-2.5 py-1 rounded-md">
                Terverifikasi: {{ $data['todayEntriesCount'] }} Entri
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-body-sm min-w-[600px]">
                <thead>
                    <tr class="bg-surface-2 text-ink font-semibold border-b border-hairline">
                        <th class="p-3.5 text-caption font-bold uppercase tracking-wider text-ink-muted">Balita</th>
                        <th class="p-3.5 text-caption font-bold uppercase tracking-wider text-ink-muted">Berat Badan (BB)</th>
                        <th class="p-3.5 text-caption font-bold uppercase tracking-wider text-ink-muted">Tinggi Badan (TB)</th>
                        <th class="p-3.5 text-caption font-bold uppercase tracking-wider text-ink-muted">Waktu Input</th>
                        <th class="p-3.5 text-caption font-bold uppercase tracking-wider text-ink-muted text-right">Status Entri</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data['todayEntries'] as $entry)
                        <tr class="border-b border-hairline-soft bg-surface-1 hover:bg-primary-light/20 transition-colors">
                            <td class="p-3.5 font-semibold text-ink">{{ $entry->child->name }}</td>
                            <td class="p-3.5 text-data text-ink font-mono">{{ number_format($entry->weight, 1) }} kg</td>
                            <td class="p-3.5 text-data text-ink font-mono">{{ number_format($entry->height, 1) }} cm</td>
                            <td class="p-3.5 text-ink-muted font-mono">{{ $entry->created_at->timezone('Asia/Jakarta')->format('H:i') }} WIB</td>
                            <td class="p-3.5 text-right">
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 bg-risk-low-surface text-risk-low rounded-full text-caption font-bold shadow-xs">
                                    ✓ Tersimpan
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-4 text-center text-ink-subtle">Belum ada aktivitas pengukuran hari ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
</div>
