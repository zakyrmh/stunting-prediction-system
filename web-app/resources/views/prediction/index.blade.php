<x-layouts::app :title="__('Riwayat Pengukuran Kolektif')">
    <div class="flex flex-col gap-6 bg-canvas text-ink font-sans min-h-screen p-4 md:p-6">
        
        <!-- Header Title -->
        <div class="flex items-center justify-between pb-4 border-b border-hairline">
            <div>
                <flux:heading size="xl" class="font-bold text-ink">Riwayat Pengukuran & Log AI</flux:heading>
                <flux:text class="mt-1 text-ink-muted">Pantau data historis antropometri, tingkat akurasi diagnosis klasifikasi model AI, dan status verifikasi klinis secara kolektif.</flux:text>
            </div>
            <flux:button icon="plus" variant="primary" :href="route('prediksi.form')" class="cursor-pointer">
                Mulai Pengukuran Baru
            </flux:button>
        </div>

        <!-- ================== 1. PANEL ATAS: FILTER KOLEKTIF & UTILITAS EKSPOR ================== -->
        <div class="bg-surface-1 border border-hairline rounded-xl p-5 shadow-sm flex flex-col gap-4">
            <span class="text-caption font-bold text-ink-subtle uppercase tracking-wider block">Panel Filter & Utilitas Ekspor</span>
            <form id="filter-form" method="GET" action="{{ route('prediksi.index') }}" class="grid grid-cols-1 md:grid-cols-12 gap-3 items-end">
                <!-- Pencarian & Dropdowns (Col-span 9) -->
                <div class="md:col-span-9 grid grid-cols-1 md:grid-cols-4 gap-3">
                    <flux:input
                        name="search"
                        value="{{ $search }}"
                        icon="magnifying-glass"
                        placeholder="Cari nama / NIK..."
                        clearable
                        onkeydown="if(event.key === 'Enter') this.form.submit()"
                        onchange="this.form.submit()" />

                    <flux:select name="filterPosyandu" onchange="this.form.submit()">
                        <flux:select.option value="">Semua Posyandu</flux:select.option>
                        @foreach($posyandus as $posyandu)
                            <flux:select.option value="{{ $posyandu->name }}" :selected="$filterPosyandu === $posyandu->name">{{ $posyandu->name }}</flux:select.option>
                        @endforeach
                    </flux:select>

                    <flux:select name="filterStatus" onchange="this.form.submit()">
                        <flux:select.option value="">Semua Status Gizi</flux:select.option>
                        <flux:select.option value="normal" :selected="$filterStatus === 'normal'">Normal</flux:select.option>
                        <flux:select.option value="stunting_risk" :selected="$filterStatus === 'stunting_risk'">Risiko Stunting</flux:select.option>
                        <flux:select.option value="stunted" :selected="$filterStatus === 'stunted'">Pendek (Stunted)</flux:select.option>
                        <flux:select.option value="severely_stunted" :selected="$filterStatus === 'severely_stunted'">Sangat Pendek</flux:select.option>
                    </flux:select>

                    <div class="grid grid-cols-2 gap-2">
                        <flux:input type="date" name="startDate" value="{{ $startDate }}" onchange="this.form.submit()" />
                        <flux:input type="date" name="endDate" value="{{ $endDate }}" onchange="this.form.submit()" />
                    </div>
                </div>

                <!-- Export Buttons (Col-span 3) -->
                <div class="md:col-span-3 flex gap-2">
                    <flux:button icon="document" variant="filled" class="w-full cursor-pointer justify-center">
                        Ekspor PDF
                    </flux:button>
                    <flux:button icon="arrow-down-tray" variant="filled" class="w-full cursor-pointer justify-center">
                        Ekspor Excel
                    </flux:button>
                </div>
            </form>
        </div>

        <!-- ================== 2. PANEL TENGAH: RINGKASAN AKURASI LAPANGAN (MINI WIDGET) ================== -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            
            <!-- Total Pengukuran -->
            <div class="bg-surface-1 border border-hairline rounded-xl p-4 shadow-sm flex flex-col gap-1">
                <span class="text-caption font-semibold text-ink-muted">Total Pengukuran</span>
                <div class="flex items-baseline gap-2 mt-1">
                    <span class="text-2xl font-bold text-ink">{{ $stats['totalCount'] }}</span>
                    <span class="text-caption text-risk-low font-bold">100% Data Log</span>
                </div>
                <flux:text size="sm" class="text-ink-subtle mt-1">Total pencatatan antropometri kolektif</flux:text>
            </div>

            <!-- Akurasi AI (Rata-rata Confidence) -->
            <div class="bg-surface-1 border border-hairline rounded-xl p-4 shadow-sm flex flex-col gap-1">
                <span class="text-caption font-semibold text-ink-muted">Tingkat Keyakinan AI (ML)</span>
                <div class="flex items-baseline gap-2 mt-1">
                    <span class="text-2xl font-bold text-ink">{{ $stats['avgConfidence'] }}%</span>
                    <span class="text-caption text-risk-low font-bold">Sangat Tinggi</span>
                </div>
                <flux:text size="sm" class="text-ink-subtle mt-1">Rerata tingkat konfidensi klasifikasi model</flux:text>
            </div>

            <!-- Validasi Medis (Bidan) -->
            <div class="bg-surface-1 border border-hairline rounded-xl p-4 shadow-sm flex flex-col gap-1">
                <span class="text-caption font-semibold text-ink-muted">Tingkat Validasi Klinis</span>
                <div class="flex items-baseline gap-2 mt-1">
                    <span class="text-2xl font-bold text-ink">{{ $stats['verifiedPercent'] }}%</span>
                    <span class="text-caption text-risk-medium font-bold">{{ $stats['pendingCount'] }} Pending</span>
                </div>
                <flux:text size="sm" class="text-ink-subtle mt-1">Persentase status diagnosis terverifikasi</flux:text>
            </div>

            <!-- Deteksi Kasus Stunting -->
            <div class="bg-surface-1 border border-hairline rounded-xl p-4 shadow-sm flex flex-col gap-1">
                <span class="text-caption font-semibold text-ink-muted">Deteksi Prevalensi Stunting</span>
                <div class="flex items-baseline gap-2 mt-1">
                    <span class="text-2xl font-bold text-ink">{{ $stats['stuntingRate'] }}%</span>
                    <span class="text-caption text-risk-high font-bold">{{ $stats['stuntedCount'] }} Kasus</span>
                </div>
                <flux:text size="sm" class="text-ink-subtle mt-1">Porsi balita didiagnosis berisiko & stunted</flux:text>
            </div>

        </div>

        <!-- ================== 3. KOMPONEN UTAMA: TABEL LOG PENGUKURAN KOLEKTIF ================== -->
        <div class="bg-surface-1 border border-hairline rounded-xl p-5 shadow-sm flex flex-col gap-4">
            <div class="flex items-center justify-between">
                <span class="text-caption font-bold text-ink-subtle uppercase tracking-wider block">Log Logaritma Pengukuran & Diagnosis AI</span>
                <flux:text size="sm" class="text-ink-muted">Menampilkan entri antropometri terurut dari tanggal terbaru</flux:text>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-body-sm min-w-[800px]">
                    <thead>
                        <tr class="bg-surface-2 text-ink font-semibold border-b border-hairline">
                            <th class="p-3">Balita & NIK</th>
                            <th class="p-3">Posyandu & Desa</th>
                            <th class="p-3">Tanggal Ukur</th>
                            <th class="p-3">Pengukuran Fisik</th>
                            <th class="p-3">Diagnosis AI (ML)</th>
                            <th class="p-3">Petugas Catat</th>
                            <th class="p-3">Validasi Medis</th>
                            <th class="p-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $log)
                            <tr class="border-b border-hairline-soft bg-surface-1 hover:bg-canvas/50 transition-colors">
                                <td class="p-3">
                                    <div class="flex flex-col">
                                        <span class="font-semibold text-ink">{{ $log['name'] }}</span>
                                        <span class="font-mono text-ink-subtle text-xs">NIK: {{ $log['nik'] }}</span>
                                    </div>
                                </td>
                                <td class="p-3">
                                    <div class="flex flex-col text-xs text-ink-muted">
                                        <span class="font-semibold">{{ $log['posyandu'] }}</span>
                                        <span>Desa: {{ $log['village'] }}</span>
                                    </div>
                                </td>
                                <td class="p-3 font-mono text-xs text-ink-muted">
                                    {{ $log['examined_at'] }}
                                </td>
                                <td class="p-3 font-mono text-xs text-ink-muted">
                                    <div class="flex flex-col">
                                        <span>TB: <strong class="text-ink">{{ $log['height'] }} cm</strong></span>
                                        <span>BB: <strong class="text-ink">{{ $log['weight'] }} kg</strong></span>
                                        <span>Usia: <strong>{{ $log['age_months'] }} Bulan</strong></span>
                                    </div>
                                </td>
                                <td class="p-3">
                                    <div class="flex flex-col gap-1">
                                        @if($log['result'] === 'severely_stunted')
                                            <span class="inline-block w-fit px-2 py-0.5 bg-risk-high-surface text-risk-high rounded text-[10px] font-bold">Sangat Pendek</span>
                                        @elseif($log['result'] === 'stunted')
                                            <span class="inline-block w-fit px-2 py-0.5 bg-risk-high-surface text-risk-high rounded text-[10px] font-bold">Pendek (Stunted)</span>
                                        @elseif($log['result'] === 'stunting_risk')
                                            <span class="inline-block w-fit px-2 py-0.5 bg-risk-medium-surface text-risk-medium rounded text-[10px] font-bold">Risiko Stunting</span>
                                        @else
                                            <span class="inline-block w-fit px-2 py-0.5 bg-risk-low-surface text-risk-low rounded text-[10px] font-bold">Normal</span>
                                        @endif
                                        <span class="text-[10px] text-ink-subtle font-mono">Conf: {{ number_format($log['confidence'] * 100, 2) }}%</span>
                                    </div>
                                </td>
                                <td class="p-3 text-ink-muted text-xs">
                                    {{ $log['recorder'] }}
                                </td>
                                <td class="p-3">
                                    @if($log['status'] === 'pending')
                                        <span class="inline-block px-2 py-0.5 bg-risk-medium-surface/70 text-risk-medium border border-risk-medium-border rounded text-[10px] font-bold">⏳ Pending</span>
                                    @else
                                        <span class="inline-block px-2 py-0.5 bg-risk-low-surface/70 text-risk-low border border-risk-low-border rounded text-[10px] font-bold">✓ Verified</span>
                                    @endif
                                </td>
                                <td class="p-3 text-right">
                                    <flux:button size="sm" icon="eye" :href="route('prediksi.show', $log['id'])" class="cursor-pointer" />
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="p-8 text-center text-ink-subtle">Belum ada riwayat log pengukuran yang tersimpan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</x-layouts::app>
