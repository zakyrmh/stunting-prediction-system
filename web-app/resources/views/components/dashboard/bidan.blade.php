@props(['data'])

<!-- Widget Statistik Global -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <!-- Stat Card 1: Total Balita -->
    <div class="bg-surface-1 border-l-4 border-primary-teal border-y border-r rounded-lg p-6 shadow-sm flex flex-col justify-between">
        <div>
            <span class="text-caption font-bold text-ink-subtle uppercase tracking-wider block">Total Balita Terdaftar</span>
            <span class="text-display-md text-ink font-bold block mt-2 font-mono">{{ $data['totalChildren'] }}</span>
        </div>
        <p class="text-caption text-ink-muted mt-4">
            <span class="text-risk-low font-bold">↑ {{ $data['newChildrenCount'] }} Balita baru</span> bulan ini di wilayah Puskesmas.
        </p>
    </div>

    <!-- Stat Card 2: Balita Stunting -->
    <div class="bg-surface-1 border-l-4 border-risk-high border-y border-r rounded-lg p-6 shadow-sm flex flex-col justify-between">
        <div>
            <span class="text-caption font-bold text-ink-subtle uppercase tracking-wider block">Balita Stunting (Lampu Merah)</span>
            <div class="flex items-baseline gap-2 mt-2">
                <span class="text-display-md text-risk-high font-bold font-mono">{{ $data['stuntedCount'] }}</span>
                <span class="text-body-sm font-semibold text-risk-high font-mono">({{ $data['stuntedPercentage'] }}%)</span>
            </div>
        </div>
        <p class="text-caption text-ink-muted mt-4 font-sans">
            Tinggi badan di bawah <strong class="text-risk-high font-semibold">-2 SD WHO</strong> (Butuh Intervensi Gizi).
        </p>
    </div>

    <!-- Stat Card 3: Growth Faltering -->
    <div class="bg-surface-1 border-l-4 border-risk-medium border-y border-r rounded-lg p-6 shadow-sm flex flex-col justify-between">
        <div>
            <span class="text-caption font-bold text-ink-subtle uppercase tracking-wider block">Gagal Tumbuh (Lampu Kuning)</span>
            <div class="flex items-baseline gap-2 mt-2">
                <span class="text-display-md text-risk-medium font-bold font-mono">{{ $data['growthFalteringCount'] }}</span>
                <span class="text-body-sm font-semibold text-risk-medium font-mono">({{ $data['growthFalteringPercentage'] }}%)</span>
            </div>
        </div>
        <p class="text-caption text-ink-muted mt-4 font-sans">
            Balita terdeteksi <strong class="text-risk-medium font-semibold">2T (2 Kali Tidak Naik)</strong> pada berat badannya.
        </p>
    </div>
</div>

<!-- Grid: ML Model Comparison Chart & Shortcuts -->
<div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
    <!-- Bar Chart Model ML (8 Columns) -->
    <div class="lg:col-span-8 bg-surface-1 border border-hairline rounded-lg p-6 shadow-sm flex flex-col gap-4">
        <div>
            <h3 class="text-headline text-ink font-bold">Komparasi Performa Model Prediksi (ML)</h3>
            <p class="text-body-sm text-ink-muted">Akurasi klasifikasi fisik awal balita stunting berdasarkan data riset notebook.</p>
        </div>

        <!-- CSS Bar Chart Representation -->
        <div class="flex flex-col gap-4 my-2">
            <!-- Model 1: Random Forest -->
            <div class="flex flex-col gap-1.5">
                <div class="flex justify-between items-center text-caption font-bold">
                    <span class="text-ink">Random Forest (Utama)</span>
                    <span class="text-primary-teal font-mono">97.31%</span>
                </div>
                <div class="w-full bg-canvas h-6 rounded-md overflow-hidden border border-hairline">
                    <div class="bg-primary-teal h-full rounded-md transition-all" style="width: 97.31%"></div>
                </div>
            </div>

            <!-- Model 2: KNN -->
            <div class="flex flex-col gap-1.5">
                <div class="flex justify-between items-center text-caption font-bold">
                    <span class="text-ink">K-Nearest Neighbors (KNN)</span>
                    <span class="text-risk-medium font-mono">96.24%</span>
                </div>
                <div class="w-full bg-canvas h-6 rounded-md overflow-hidden border border-hairline">
                    <div class="bg-risk-medium-border h-full rounded-md transition-all" style="width: 96.24%"></div>
                </div>
            </div>

            <!-- Model 3: Naive Bayes -->
            <div class="flex flex-col gap-1.5">
                <div class="flex justify-between items-center text-caption font-bold">
                    <span class="text-ink">Naïve Bayes</span>
                    <span class="text-ink-subtle font-mono">42.55%</span>
                </div>
                <div class="w-full bg-canvas h-6 rounded-md overflow-hidden border border-hairline">
                    <div class="bg-ink-tertiary/40 h-full rounded-md transition-all" style="width: 42.55%"></div>
                </div>
            </div>
        </div>

        <div class="bg-primary-light/40 border-l-4 border-primary-teal p-3.5 rounded-r-md">
            <p class="text-body-sm text-ink-muted leading-relaxed">
                💡 <strong class="text-ink font-semibold">Analisis Teknis:</strong> Model <strong class="text-primary-teal font-semibold">Random Forest</strong> diadopsi sebagai model inferensi utama stunting karena kestabilannya saat pengujian <em class="italic">10-Fold Cross-Validation</em> dengan tingkat akurasi rata-rata <strong class="text-primary-teal font-semibold">97.42% &plusmn; 0.30%</strong>.
            </p>
        </div>
    </div>

    <!-- Shortcut Menus (4 Columns) -->
    <div class="lg:col-span-4 bg-surface-1 border border-hairline rounded-lg p-6 shadow-sm flex flex-col gap-4">
        <h3 class="text-card-title text-ink font-bold">Aksi Cepat Admin</h3>
        <div class="flex flex-col gap-3">
            <a href="{{ route('users.index') }}" class="flex items-center gap-3 p-3 bg-canvas hover:bg-primary-light border border-hairline rounded-md transition-colors group">
                <span class="text-headline">👥</span>
                <div class="flex flex-col text-left">
                    <span class="text-body-sm font-bold text-ink group-hover:text-primary-teal transition-colors">Manajemen Kader</span>
                    <span class="text-[10px] text-ink-subtle">Atur akses data entry</span>
                </div>
            </a>
            <a href="#" class="flex items-center gap-3 p-3 bg-canvas hover:bg-primary-light border border-hairline rounded-md transition-colors group">
                <span class="text-headline">📄</span>
                <div class="flex flex-col text-left">
                    <span class="text-body-sm font-bold text-ink group-hover:text-primary-teal transition-colors">Cetak Laporan Bulanan</span>
                    <span class="text-[10px] text-ink-subtle">Unduh berkas PDF Puskesmas</span>
                </div>
            </a>
            <a href="#" class="flex items-center gap-3 p-3 bg-canvas hover:bg-primary-light border border-hairline rounded-md transition-colors group">
                <span class="text-headline">⚙️</span>
                <div class="flex flex-col text-left">
                    <span class="text-body-sm font-bold text-ink group-hover:text-primary-teal transition-colors">Aturan Sistem Pakar</span>
                    <span class="text-[10px] text-ink-subtle">Kelola Knowledge Base CF</span>
                </div>
            </a>
        </div>
    </div>
</div>

<!-- Antrean Verifikasi Hasil Certainty Factor -->
<div class="bg-surface-1 border border-hairline rounded-lg p-6 shadow-sm">
    <div class="flex items-center justify-between mb-4 border-b border-hairline-soft pb-3">
        <div>
            <h3 class="text-headline text-ink font-bold">Antrean Verifikasi Sistem Pakar</h3>
            <p class="text-body-sm text-ink-muted">Hasil kuesioner gejala klinis Certainty Factor tinggi yang membutuhkan verifikasi dokter/bidan.</p>
        </div>
        <span class="px-2.5 py-1 bg-risk-high-surface text-risk-high font-bold text-xs rounded font-mono">{{ $data['pendingVerificationsCount'] }} Menunggu</span>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left text-body-sm min-w-[600px]">
            <thead>
                <tr class="bg-surface-2 text-ink font-semibold border-b border-hairline">
                    <th class="p-3">Balita</th>
                    <th class="p-3">Usia</th>
                    <th class="p-3">Certainty Factor</th>
                    <th class="p-3">Status Sistem</th>
                    <th class="p-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data['pendingVerifications'] as $verification)
                    <tr class="border-b border-hairline-soft hover:bg-canvas/40 transition-colors">
                        <td class="p-3 font-semibold">{{ $verification->prediction->child->name }}</td>
                        <td class="p-3 font-mono">{{ $verification->prediction->child->birth_date->diffInMonths(now()) }} Bulan</td>
                        <td class="p-3 font-mono font-bold text-risk-high">{{ number_format($verification->prediction->confidence * 100, 1) }}%</td>
                        <td class="p-3">
                            @if($verification->prediction->result === 'severely_stunted')
                                <span class="px-2.5 py-0.5 bg-risk-high-surface text-risk-high rounded text-xs font-semibold">Sangat Pendek</span>
                            @elseif($verification->prediction->result === 'stunted')
                                <span class="px-2.5 py-0.5 bg-risk-high-surface text-risk-high rounded text-xs font-semibold">Pendek (Stunting)</span>
                            @elseif($verification->prediction->result === 'stunting_risk')
                                <span class="px-2.5 py-0.5 bg-risk-medium-surface text-risk-medium rounded text-xs font-semibold">Risiko Stunting</span>
                            @else
                                <span class="px-2.5 py-0.5 bg-risk-low-surface text-risk-low rounded text-xs font-semibold">Normal</span>
                            @endif
                        </td>
                        <td class="p-3 text-right">
                            <button class="px-3.5 py-1.5 bg-primary-teal hover:bg-[#096B50] text-white text-xs font-semibold rounded transition-colors cursor-pointer">
                                Tinjau Rekomendasi Gizi
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="p-4 text-center text-ink-subtle">Tidak ada antrean verifikasi Certainty Factor.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
