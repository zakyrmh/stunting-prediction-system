<x-layouts::app :title="__('Master Posyandu & Analisis Wilayah')">
    <div class="flex flex-col gap-6 bg-canvas text-ink font-sans min-h-screen p-4 md:p-6">
        
        <!-- Header Title & Action Buttons -->
        <div class="flex items-center justify-between pb-4 border-b border-hairline">
            <div>
                <flux:heading size="xl" class="font-bold text-ink">Manajemen & Pemantauan Wilayah Posyandu</flux:heading>
                <flux:text class="mt-1 text-ink-muted">Kelola data induk posyandu, daftar kader aktif, serta pantau indeks prevalensi stunting tingkat kelurahan/desa secara real-time.</flux:text>
            </div>
            
            <!-- Tombol Manajemen Wilayah -->
            <div class="flex gap-2">
                <flux:button icon="plus" variant="primary" :href="route('posyandu.form')" class="cursor-pointer">
                    Daftarkan Posyandu Baru
                </flux:button>
                <flux:button icon="map" variant="filled" class="cursor-pointer">
                    Manajemen Wilayah & RT
                </flux:button>
            </div>
        </div>

        <!-- Split Grid Layout -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
            
            <!-- Komponen Utama: Tabel / Grid Master Posyandu -->
            <div class="lg:col-span-7 flex flex-col gap-5 w-full bg-surface-1 border border-hairline rounded-xl p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <h3 class="text-headline font-bold text-ink">Master Posyandu Wilayah</h3>
                    <flux:text size="sm" class="text-ink-subtle">Klik posyandu untuk membuka Analisis Wilayah</flux:text>
                </div>
                
                <div class="grid grid-cols-1 gap-4 mt-2">
                    @forelse($posyandus as $p)
                        <div onclick="window.location.href='{{ route('posyandu.index', ['selectedPosyanduId' => $p['id']]) }}'"
                            class="p-4 border rounded-xl cursor-pointer transition-all flex flex-col md:flex-row md:items-center justify-between gap-4 {{ $selectedPosyanduId == $p['id'] ? 'bg-primary-light/40 border-primary-teal border-2 shadow-sm' : 'bg-surface-1 border-hairline hover:bg-canvas/50' }}">
                            
                            <div class="flex items-start gap-3">
                                <div class="h-10 w-10 rounded-lg bg-primary-teal/10 text-primary-teal flex items-center justify-center shrink-0 border border-primary-teal/20 text-lg">
                                    🏢
                                </div>
                                <div class="flex flex-col">
                                    <span class="font-bold text-ink text-body-sm">{{ $p['name'] }}</span>
                                    <span class="text-xs text-ink-muted">{{ $p['address'] }}</span>
                                    <span class="text-[10px] text-ink-subtle font-semibold mt-1">Desa: {{ $p['village'] }} · Kec: {{ $p['district'] }}</span>
                                </div>
                            </div>

                            <!-- Statistics status on the card -->
                            <div class="flex items-center gap-4 shrink-0 justify-between md:justify-end border-t md:border-t-0 pt-3 md:pt-0 border-hairline-soft">
                                <div class="flex flex-col items-start md:items-end text-xs">
                                    <span class="text-ink-subtle">Kader/Balita</span>
                                    <span class="font-semibold text-ink">{{ $p['total_kader'] }} Kader / {{ $p['total_children'] }} Balita</span>
                                </div>
                                
                                <!-- Stunting Prevalence Badge -->
                                <div class="flex flex-col items-end">
                                    @if($p['stunting_rate'] >= 20.0)
                                        <span class="px-2.5 py-0.5 bg-risk-high-surface text-risk-high rounded text-[11px] font-bold">Prevalensi Tinggi ({{ $p['stunting_rate'] }}%)</span>
                                    @elseif($p['stunting_rate'] >= 10.0)
                                        <span class="px-2.5 py-0.5 bg-risk-medium-surface text-risk-medium rounded text-[11px] font-bold">Prevalensi Sedang ({{ $p['stunting_rate'] }}%)</span>
                                    @else
                                        <span class="px-2.5 py-0.5 bg-risk-low-surface text-risk-low rounded text-[11px] font-bold">Prevalensi Rendah ({{ $p['stunting_rate'] }}%)</span>
                                    @endif
                                    <span class="text-[9px] text-ink-subtle font-mono mt-0.5">{{ $p['stunted_cases'] }} Kasus Stunting</span>
                                </div>
                            </div>

                        </div>
                    @empty
                        <div class="p-8 text-center text-ink-subtle border border-hairline rounded-xl bg-surface-1">
                            Belum ada posyandu terdaftar di database.
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Fitur Detail Posyandu View (Analisis Wilayah) -->
            <div class="lg:col-span-5 flex flex-col gap-5 w-full">
                @if($selectedPosyandu)
                    
                    <!-- Card Analisis Wilayah -->
                    <div class="bg-surface-1 border border-hairline rounded-xl p-5 shadow-sm flex flex-col gap-4">
                        <div class="flex items-center justify-between border-b border-hairline-soft pb-3">
                            <div class="flex flex-col text-left">
                                <span class="text-caption font-bold text-primary-teal uppercase tracking-wider block">Analisis Wilayah & Rekomendasi</span>
                                <h4 class="text-headline font-bold text-ink mt-1">{{ $selectedPosyandu['name'] }}</h4>
                            </div>
                            <span class="text-xs font-semibold px-2 py-0.5 bg-canvas rounded border border-hairline font-mono">{{ $selectedPosyandu['city'] }}</span>
                        </div>

                        <!-- Grid Analisis Pengukuran Posyandu -->
                        <div class="grid grid-cols-3 gap-3">
                            <div class="bg-canvas/50 p-2.5 rounded border border-hairline-soft text-center">
                                <span class="text-[10px] text-ink-subtle block">Rasio Kasus</span>
                                <span class="text-headline font-bold text-risk-high font-mono">{{ $selectedPosyandu['stunting_rate'] }}%</span>
                                <span class="text-[9px] text-ink-muted block mt-0.5">{{ $selectedPosyandu['stunted_cases'] }} dari {{ $selectedPosyandu['total_children'] }} anak</span>
                            </div>
                            <div class="bg-canvas/50 p-2.5 rounded border border-hairline-soft text-center">
                                <span class="text-[10px] text-ink-subtle block">Sesi Timbang</span>
                                <span class="text-headline font-bold text-ink font-mono">{{ $selectedPosyandu['sessions_count'] }}</span>
                                <span class="text-[9px] text-ink-muted block mt-0.5">Sesi Terdaftar</span>
                            </div>
                            <div class="bg-canvas/50 p-2.5 rounded border border-hairline-soft text-center">
                                <span class="text-[10px] text-ink-subtle block">Konfidensi AI</span>
                                <span class="text-headline font-bold text-primary-teal font-mono">{{ $selectedPosyandu['avg_confidence'] }}%</span>
                                <span class="text-[9px] text-ink-muted block mt-0.5">Rerata Log AI</span>
                            </div>
                        </div>

                        <!-- Petunjuk Sesi & Lokasi -->
                        <div class="flex flex-col gap-2 bg-surface-2 p-3.5 rounded-lg border border-hairline text-xs">
                            <div class="flex justify-between">
                                <span class="text-ink-subtle">Kunjungan Terakhir:</span>
                                <span class="font-bold text-ink font-mono">{{ $selectedPosyandu['last_session'] }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-ink-subtle">Kecamatan:</span>
                                <span class="font-semibold text-ink">{{ $selectedPosyandu['district'] }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-ink-subtle">Desa / Kelurahan:</span>
                                <span class="font-semibold text-ink">{{ $selectedPosyandu['village'] }}</span>
                            </div>
                        </div>

                        <!-- Daftar Kader Aktif -->
                        <div class="flex flex-col gap-2">
                            <span class="text-caption font-bold text-ink-subtle uppercase tracking-wider block">Kader Posyandu Aktif ({{ count($selectedPosyandu['kader_list']) }} Orang)</span>
                            <div class="flex flex-wrap gap-1.5 mt-1">
                                @foreach($selectedPosyandu['kader_list'] as $kader)
                                    <span class="px-2.5 py-1 bg-canvas text-ink text-xs font-semibold rounded-md border border-hairline flex items-center gap-1.5">
                                        <span class="h-1.5 w-1.5 rounded-full bg-primary-teal"></span>
                                        {{ $kader }}
                                    </span>
                                @endforeach
                            </div>
                        </div>

                        <!-- Rencana Intervensi Khusus (Rekomendasi Rencana Aksi) -->
                        <div class="bg-primary-light/50 border border-primary-teal/30 p-4 rounded-lg">
                            <span class="text-caption font-bold text-primary-teal uppercase tracking-wider block">Rencana Aksi Intervensi Wilayah</span>
                            <p class="text-body-sm text-ink-muted mt-1.5 leading-relaxed">{{ $selectedPosyandu['top_recommendations'] }}</p>
                        </div>

                    </div>

                @else
                    <div class="bg-surface-1 border border-hairline rounded-xl p-8 shadow-sm text-center text-ink-subtle">
                        Pilih salah satu posyandu dari daftar master untuk menampilkan analisis wilayah dan status intervensi.
                    </div>
                @endif
            </div>

        </div>

    </div>
</x-layouts::app>
