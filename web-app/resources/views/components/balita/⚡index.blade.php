<?php

use Livewire\Component;
use Livewire\Attributes\Title;
use App\Models\User;

new #[Title('Data & Pengukuran Balita')]
class extends Component {
    public int $selectedChildId = 1;
    public string $search = '';
    public string $filterPosyandu = '';
    public string $filterStatus = '';

    public function selectChild(int $id): void
    {
        $this->selectedChildId = $id;
    }

    public function overrideStatus(string $status): void
    {
        session()->flash('success', "Status gizi balita berhasil diverifikasi & diperbarui menjadi: " . strtoupper($status));
    }

    public function getChildrenData(): array
    {
        return [
            1 => [
                'id' => 1,
                'name' => 'Rania Putri',
                'nik' => '1601024305220001',
                'gender' => 'female',
                'age_months' => 15,
                'parent_name' => 'Ibu Rahma',
                'posyandu' => 'Posyandu Melati I',
                'village' => 'Sukarami',
                'latest_bb' => 9.2,
                'latest_tb' => 74.2,
                'ml_screening' => 'Risiko Stunting (KNN: 96.24%)',
                'ml_result' => 'stunting_risk',
                'cf_risk' => 'Amber / Risiko Sedang (CF: 68.0%)',
                'cf_result' => 'stunting_risk',
                'validation_status' => 'pending',
                'recommendations' => 'Fokus protein hewani (telur & hati ayam), penerapan feeding rules IDAI secara ketat, dan pemberian suplemen mikro nutrisi zat besi harian.',
                'history' => [
                    ['age' => 11, 'bb' => 8.2, 'tb' => 71.0, 'status' => 'normal', 'date' => '22/02/2026', 'x' => 40, 'y' => 193],
                    ['age' => 12, 'bb' => 8.5, 'tb' => 72.2, 'status' => 'normal', 'date' => '22/03/2026', 'x' => 125, 'y' => 183],
                    ['age' => 13, 'bb' => 8.8, 'tb' => 73.5, 'status' => 'normal', 'date' => '22/04/2026', 'x' => 210, 'y' => 173],
                    ['age' => 14, 'bb' => 8.9, 'tb' => 74.0, 'status' => 'stunting_risk', 'date' => '22/05/2026', 'x' => 295, 'y' => 168],
                    ['age' => 15, 'bb' => 9.0, 'tb' => 74.2, 'status' => 'stunted', 'date' => '22/06/2026', 'x' => 380, 'y' => 166],
                ],
                'pathD' => 'M 40 193 L 125 183 L 210 173 L 295 168 L 380 166',
            ],
            2 => [
                'id' => 2,
                'name' => 'Khansa Inara',
                'nik' => '1601025008210002',
                'gender' => 'female',
                'age_months' => 22,
                'parent_name' => 'Ibu Siti',
                'posyandu' => 'Posyandu Melati I',
                'village' => 'Sukarami',
                'latest_bb' => 10.6,
                'latest_tb' => 82.5,
                'ml_screening' => 'Normal (Random Forest: 98.2%)',
                'ml_result' => 'normal',
                'cf_risk' => 'Hijau / Normal (CF: 10.0%)',
                'cf_result' => 'normal',
                'validation_status' => 'verified',
                'recommendations' => 'Pertahankan pola makan gizi seimbang harian, berikan variasi PMT lokal, dan pastikan imunisasi dasar lengkap tetap terpenuhi.',
                'history' => [
                    ['age' => 20, 'bb' => 10.2, 'tb' => 81.0, 'status' => 'normal', 'date' => '15/04/2026', 'x' => 40, 'y' => 73],
                    ['age' => 21, 'bb' => 10.5, 'tb' => 82.0, 'status' => 'normal', 'date' => '15/05/2026', 'x' => 210, 'y' => 61],
                    ['age' => 22, 'bb' => 10.6, 'tb' => 82.5, 'status' => 'normal', 'date' => '15/06/2026', 'x' => 380, 'y' => 55],
                ],
                'pathD' => 'M 40 73 L 210 61 L 380 55',
            ],
            3 => [
                'id' => 3,
                'name' => 'Zayan Ghaisan',
                'nik' => '1601021201220003',
                'gender' => 'male',
                'age_months' => 18,
                'parent_name' => 'Ibu Dwi',
                'posyandu' => 'Posyandu Mawar II',
                'village' => 'Kenten',
                'latest_bb' => 9.5,
                'latest_tb' => 77.2,
                'ml_screening' => 'Sangat Pendek (Random Forest: 97.4%)',
                'ml_result' => 'severely_stunted',
                'cf_risk' => 'Merah / Risiko Tinggi (CF: 92.5%)',
                'cf_result' => 'severely_stunted',
                'validation_status' => 'pending',
                'recommendations' => 'Rujuk segera ke Dokter Spesialis Anak Puskesmas, berikan PMT pemulihan gizi buruk, suplemen multivitamin mineral, dan audit sanitasi rumah.',
                'history' => [
                    ['age' => 16, 'bb' => 10.0, 'tb' => 76.5, 'status' => 'normal', 'date' => '10/04/2026', 'x' => 40, 'y' => 134],
                    ['age' => 17, 'bb' => 9.8, 'tb' => 77.0, 'status' => 'stunting_risk', 'date' => '10/05/2026', 'x' => 210, 'y' => 128],
                    ['age' => 18, 'bb' => 9.5, 'tb' => 77.2, 'status' => 'severely_stunted', 'date' => '10/06/2026', 'x' => 380, 'y' => 125],
                ],
                'pathD' => 'M 40 134 L 210 128 L 380 125',
            ]
        ];
    }

    public function with(): array
    {
        $allChildren = $this->getChildrenData();
        $filtered = collect($allChildren);

        if ($this->search) {
            $filtered = $filtered->filter(function($c) {
                return str_contains(strtolower($c['name']), strtolower($this->search))
                    || str_contains($c['nik'], $this->search)
                    || str_contains(strtolower($c['parent_name']), strtolower($this->search));
            });
        }

        if ($this->filterPosyandu) {
            $filtered = $filtered->filter(function($c) {
                return $c['posyandu'] === $this->filterPosyandu;
            });
        }

        if ($this->filterStatus) {
            $filtered = $filtered->filter(function($c) {
                return $c['cf_result'] === $this->filterStatus;
            });
        }

        $selectedChild = $allChildren[$this->selectedChildId] ?? null;

        return [
            'children' => $filtered,
            'selectedChild' => $selectedChild,
        ];
    }
};
?>

<div class="flex flex-col gap-6 bg-canvas text-ink font-sans min-h-screen">
    
    @if(auth()->user()->isBidan())
        <!-- ================== BIDAN VIEW: DATA & PENGUKURAN COCKPIT ================== -->
        <div class="flex items-center justify-between pb-4 border-b border-hairline">
            <div>
                <flux:heading size="xl" class="font-bold text-ink">Cockpit Data & Pengukuran Balita</flux:heading>
                <flux:text class="mt-1 text-ink-muted">Kelola data induk balita, monitoring perkembangan runtun waktu (time-series), dan verifikasi diagnosis hybrid AI.</flux:text>
            </div>
            <div class="flex gap-2">
                <flux:button icon="plus" variant="primary" :href="route('balita.form')" wire:navigate class="cursor-pointer">
                    Tambah Balita
                </flux:button>
                <flux:button icon="arrow-down-tray" variant="filled" class="cursor-pointer">
                    Ekspor Excel / PDF
                </flux:button>
            </div>
        </div>

        {{-- Flash message --}}
        @if(session('success'))
            <flux:callout variant="success" icon="check-circle" class="mb-4">
                {{ session('success') }}
            </flux:callout>
        @endif

        <!-- Split Layout Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
            
            <!-- Left Side: Filter & Master Table (7 Columns) -->
            <div class="lg:col-span-7 flex flex-col gap-5 w-full bg-surface-1 border border-hairline rounded-xl p-5 shadow-sm">
                
                <h3 class="text-headline font-bold text-ink mb-1">Tabel Master Balita</h3>
                
                <!-- Filter & Pencarian Cepat -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                    <flux:input
                        wire:model.live.debounce.300ms="search"
                        icon="magnifying-glass"
                        placeholder="Cari nama / NIK..."
                        clearable />

                    <flux:select wire:model.live="filterPosyandu" class="w-full">
                        <flux:select.option value="">Semua Posyandu</flux:select.option>
                        <flux:select.option value="Posyandu Melati I">Posyandu Melati I</flux:select.option>
                        <flux:select.option value="Posyandu Mawar II">Posyandu Mawar II</flux:select.option>
                    </flux:select>

                    <flux:select wire:model.live="filterStatus" class="w-full">
                        <flux:select.option value="">Semua Status</flux:select.option>
                        <flux:select.option value="normal">Normal</flux:select.option>
                        <flux:select.option value="stunting_risk">Risiko Stunting</flux:select.option>
                        <flux:select.option value="severely_stunted">Sangat Pendek</flux:select.option>
                    </flux:select>
                </div>

                <!-- Master Table -->
                <div class="overflow-x-auto mt-2">
                    <table class="w-full text-left text-body-sm min-w-[500px]">
                        <thead>
                            <tr class="bg-surface-2 text-ink font-semibold border-b border-hairline">
                                <th class="p-3">Nama Balita</th>
                                <th class="p-3">NIK</th>
                                <th class="p-3">Usia</th>
                                <th class="p-3">Posyandu</th>
                                <th class="p-3 text-right">Status Gizi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($children as $child)
                                <tr wire:click="selectChild({{ $child['id'] }})"
                                    class="border-b border-hairline-soft hover:bg-primary-light/20 transition-colors cursor-pointer {{ $selectedChildId === $child['id'] ? 'bg-primary-light/40 border-l-4 border-l-primary-teal' : 'bg-surface-1' }}">
                                    <td class="p-3 font-semibold text-ink">
                                        <div class="flex items-center gap-2">
                                            <span class="text-xs">👶</span>
                                            <span>{{ $child['name'] }}</span>
                                        </div>
                                    </td>
                                    <td class="p-3 font-mono text-ink-subtle text-xs">{{ $child['nik'] }}</td>
                                    <td class="p-3 font-mono text-ink-muted text-xs">{{ $child['age_months'] }} Bln</td>
                                    <td class="p-3 text-ink-muted">{{ $child['posyandu'] }}</td>
                                    <td class="p-3 text-right">
                                        @if($child['cf_result'] === 'severely_stunted')
                                            <span class="inline-block px-2.5 py-0.5 bg-risk-high-surface text-risk-high rounded text-[11px] font-bold">Sangat Pendek</span>
                                        @elseif($child['cf_result'] === 'stunting_risk')
                                            <span class="inline-block px-2.5 py-0.5 bg-risk-medium-surface text-risk-medium rounded text-[11px] font-bold">Risiko</span>
                                        @else
                                            <span class="inline-block px-2.5 py-0.5 bg-risk-low-surface text-risk-low rounded text-[11px] font-bold">Normal</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="p-8 text-center text-ink-subtle">Tidak ada data balita ditemukan.</td>
                                endforelse
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Right Side: Selected Child Detailed Medical Record Cockpit (5 Columns) -->
            <div class="lg:col-span-5 flex flex-col gap-5 w-full">
                @if($selectedChild)
                    <!-- Component 4: Ringkasan Pengukuran Terakhir -->
                    <div class="bg-surface-1 border border-hairline rounded-xl p-5 shadow-sm">
                        <span class="text-caption font-bold text-ink-subtle uppercase tracking-wider block mb-3">Ringkasan Pengukuran Terakhir</span>
                        
                        <div class="flex items-center gap-3 mb-4 border-b border-hairline-soft pb-3">
                            <div class="h-10 w-10 rounded-full bg-primary-light text-primary-teal flex items-center justify-center font-bold text-headline border border-primary-teal shrink-0">
                                {{ collect(explode(' ', $selectedChild['name']))->take(2)->map(fn($w) => strtoupper(substr($w, 0, 1)))->implode('') }}
                            </div>
                            <div class="flex flex-col text-left">
                                <h4 class="text-headline font-bold text-ink leading-tight">{{ $selectedChild['name'] }}</h4>
                                <span class="text-caption text-ink-muted">NIK: <span class="font-mono">{{ $selectedChild['nik'] }}</span> · {{ $selectedChild['parent_name'] }}</span>
                            </div>
                        </div>

                        <!-- Grid BB/TB & Status Gizi -->
                        <div class="grid grid-cols-2 gap-3 mb-4">
                            <div class="bg-canvas/50 p-2.5 rounded border border-hairline-soft">
                                <span class="text-caption text-ink-subtle block">Tinggi Badan</span>
                                <span class="text-headline font-bold text-ink font-mono">{{ $selectedChild['latest_tb'] }} cm</span>
                            </div>
                            <div class="bg-canvas/50 p-2.5 rounded border border-hairline-soft">
                                <span class="text-caption text-ink-subtle block">Berat Badan</span>
                                <span class="text-headline font-bold text-ink font-mono">{{ $selectedChild['latest_bb'] }} kg</span>
                            </div>
                            <div class="col-span-2 bg-canvas/50 p-2.5 rounded border border-hairline-soft flex items-center justify-between">
                                <div>
                                    <span class="text-caption text-ink-subtle block">Skrining ML (AI)</span>
                                    <span class="text-body-sm font-semibold text-ink">{{ $selectedChild['ml_screening'] }}</span>
                                </div>
                                @if($selectedChild['ml_result'] === 'severely_stunted' || $selectedChild['ml_result'] === 'stunting_risk')
                                    <span class="px-2 py-0.5 bg-risk-high-surface text-risk-high rounded text-[10px] font-bold">Risiko</span>
                                @else
                                    <span class="px-2 py-0.5 bg-risk-low-surface text-risk-low rounded text-[10px] font-bold">Normal</span>
                                @endif
                            </div>
                            <div class="col-span-2 bg-canvas/50 p-2.5 rounded border border-hairline-soft flex items-center justify-between">
                                <div>
                                    <span class="text-caption text-ink-subtle block">Risiko Sistem Pakar (CF)</span>
                                    <span class="text-body-sm font-semibold text-ink">{{ $selectedChild['cf_risk'] }}</span>
                                </div>
                                @if($selectedChild['cf_result'] === 'severely_stunted')
                                    <span class="px-2 py-0.5 bg-risk-high-surface text-risk-high rounded text-[10px] font-bold">Merah</span>
                                @elseif($selectedChild['cf_result'] === 'stunting_risk')
                                    <span class="px-2 py-0.5 bg-risk-medium-surface text-risk-medium rounded text-[10px] font-bold">Amber</span>
                                @else
                                    <span class="px-2 py-0.5 bg-risk-low-surface text-risk-low rounded text-[10px] font-bold">Hijau</span>
                                @endif
                            </div>
                        </div>

                        <!-- Status Validasi & Actions -->
                        <div class="flex items-center justify-between bg-surface-2 p-3 rounded-lg border border-hairline">
                            <div class="flex flex-col">
                                <span class="text-caption text-ink-subtle">Status Validasi Medis:</span>
                                <span class="text-body-sm font-bold {{ $selectedChild['validation_status'] === 'pending' ? 'text-risk-medium' : 'text-risk-low' }}">
                                    {{ $selectedChild['validation_status'] === 'pending' ? '⏳ Menunggu Verifikasi' : '✓ Terverifikasi Bidan' }}
                                </span>
                            </div>
                            @if($selectedChild['validation_status'] === 'pending')
                                <flux:button size="sm" variant="primary" wire:click="overrideStatus('verified')" class="cursor-pointer">
                                    Validasi Sekarang
                                </flux:button>
                            @endif
                        </div>
                    </div>

                    <!-- Component 5: View Detail Rekam Medis Runtun Waktu Anak -->
                    <div class="bg-surface-1 border border-hairline rounded-xl p-5 shadow-sm flex flex-col gap-4">
                        <span class="text-caption font-bold text-ink-subtle uppercase tracking-wider block">Kurva Pertumbuhan Runtun Waktu (TB)</span>
                        
                        <!-- Mini SVG Chart -->
                        <div class="bg-canvas border border-hairline-soft rounded-lg p-2 flex flex-col items-center">
                            <svg class="w-full h-44 text-primary-teal" viewBox="0 0 400 220">
                                <rect x="40" y="20" width="340" height="90" class="fill-risk-low-surface" opacity="0.45"></rect>
                                <rect x="40" y="110" width="340" height="50" class="fill-risk-medium-surface" opacity="0.45"></rect>
                                <rect x="40" y="160" width="340" height="40" class="fill-risk-high-surface" opacity="0.45"></rect>
                                <line x1="40" y1="20" x2="40" y2="200" stroke="#C0D9CA" stroke-width="1.5"></line>
                                <line x1="40" y1="200" x2="380" y2="200" stroke="#C0D9CA" stroke-width="1.5"></line>
                                <line x1="40" y1="110" x2="380" y2="110" stroke="#C0D9CA" stroke-width="1" stroke-dasharray="3 3"></line>
                                <line x1="40" y1="160" x2="380" y2="160" stroke="#C0D9CA" stroke-width="1" stroke-dasharray="3 3"></line>
                                <path d="M 40 100 Q 150 70, 260 45 T 380 25" fill="none" stroke="#6B8C74" stroke-width="1.5" stroke-dasharray="5 5"></path>
                                
                                <!-- Growth Path -->
                                <path d="{{ $selectedChild['pathD'] }}" fill="none" stroke="#D97706" stroke-width="3" stroke-linecap="round"></path>
                                
                                <!-- Points -->
                                @foreach($selectedChild['history'] as $p)
                                    <circle cx="{{ $p['x'] }}" cy="{{ $p['y'] }}" r="4" fill="{{ $p['status'] === 'normal' ? '#16A34A' : ($p['status'] === 'stunting_risk' ? '#D97706' : '#DC2626') }}" stroke="#FFFFFF" stroke-width="1"></circle>
                                @endforeach

                                <!-- Axis Labels -->
                                @foreach($selectedChild['history'] as $p)
                                    <text x="{{ $p['x'] }}" y="212" fill="#6B8C74" font-size="7" text-anchor="middle" font-family="Inter, sans-serif">{{ $p['age'] }} bln</text>
                                @endforeach
                            </svg>
                        </div>

                        <!-- Tabel Histori Bulanan Lengkap -->
                        <span class="text-caption font-bold text-ink-subtle uppercase tracking-wider block">Histori Lengkap Bulanan</span>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-body-sm">
                                <thead>
                                    <tr class="bg-surface-2 text-ink-subtle border-b border-hairline font-mono text-[10px]">
                                        <th class="p-2">Usia</th>
                                        <th class="p-2">TB (cm)</th>
                                        <th class="p-2">BB (kg)</th>
                                        <th class="p-2">Tanggal Ukur</th>
                                        <th class="p-2 text-right">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($selectedChild['history'] as $h)
                                        <tr class="border-b border-hairline-soft text-xs">
                                            <td class="p-2 font-mono">{{ $h['age'] }} Bulan</td>
                                            <td class="p-2 font-mono">{{ $h['tb'] }} cm</td>
                                            <td class="p-2 font-mono">{{ $h['bb'] }} kg</td>
                                            <td class="p-2 text-ink-muted">{{ $h['date'] }}</td>
                                            <td class="p-2 text-right font-semibold {{ $h['status'] === 'normal' ? 'text-risk-low' : ($h['status'] === 'stunting_risk' ? 'text-risk-medium' : 'text-risk-high') }}">
                                                {{ strtoupper($h['status']) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Konsultasi Hybrid AI & Override Panel -->
                        <div class="bg-primary-light/50 border border-primary-teal/30 p-4 rounded-lg flex flex-col gap-3">
                            <div>
                                <span class="text-caption font-bold text-primary-teal uppercase tracking-wider block">Rekomendasi Intervensi Gizi AI</span>
                                <p class="text-body-sm text-ink-muted mt-1 leading-relaxed">{{ $selectedChild['recommendations'] }}</p>
                            </div>
                            
                            <!-- Override Panel -->
                            <div class="border-t border-hairline-soft pt-3 flex flex-col gap-2">
                                <span class="text-caption font-bold text-ink-subtle uppercase tracking-wider block">Panel Koreksi Diagnosis Bidan (Override)</span>
                                <div class="flex gap-2 mt-1">
                                    <button wire:click="overrideStatus('normal')" class="px-2.5 py-1.5 bg-risk-low-surface text-risk-low hover:bg-risk-low hover:text-white rounded text-xs font-semibold cursor-pointer transition-all border border-risk-low-border">
                                        Koreksi Normal
                                    </button>
                                    <button wire:click="overrideStatus('stunting_risk')" class="px-2.5 py-1.5 bg-risk-medium-surface text-risk-medium hover:bg-risk-medium hover:text-white rounded text-xs font-semibold cursor-pointer transition-all border border-risk-medium-border">
                                        Koreksi Risiko
                                    </button>
                                    <button wire:click="overrideStatus('stunted')" class="px-2.5 py-1.5 bg-risk-high-surface text-risk-high hover:bg-risk-high hover:text-white rounded text-xs font-semibold cursor-pointer transition-all border border-risk-high-border">
                                        Koreksi Stunting
                                    </button>
                                </div>
                            </div>
                        </div>

                    </div>
                @else
                    <div class="bg-surface-1 border border-hairline rounded-xl p-8 shadow-sm text-center text-ink-subtle">
                        Pilih salah satu balita dari tabel untuk menampilkan rekam medis runtun waktu & panel validasi AI.
                    </div>
                @endif
            </div>

        </div>

    @else
        <!-- ================== KADER / OTHER USER VIEW: SIMPLE DATA LIST ================== -->
        <div class="flex items-center justify-between pb-4 border-b border-hairline">
            <div>
                <flux:heading size="xl" class="font-bold text-ink">Daftar & Riwayat Anak</flux:heading>
                <flux:text class="mt-1 text-ink-muted">Melihat daftar balita terdaftar dan memantau status gizi di Posyandu Anda.</flux:text>
            </div>
            <flux:button icon="plus" variant="primary" :href="route('balita.form')" wire:navigate class="cursor-pointer">
                Pendaftaran Balita
            </flux:button>
        </div>

        <div class="bg-surface-1 border border-hairline rounded-xl p-5 shadow-sm">
            <flux:input
                wire:model.live.debounce.300ms="search"
                icon="magnifying-glass"
                placeholder="Cari nama balita atau NIK..."
                clearable
                class="mb-4" />

            <div class="overflow-x-auto">
                <table class="w-full text-left text-body-sm">
                    <thead>
                        <tr class="bg-surface-2 text-ink font-semibold border-b border-hairline">
                            <th class="p-3.5">Nama Balita</th>
                            <th class="p-3.5">NIK</th>
                            <th class="p-3.5">Usia</th>
                            <th class="p-3.5">Orang Tua</th>
                            <th class="p-3.5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($children as $child)
                            <tr class="border-b border-hairline-soft bg-surface-1 hover:bg-canvas/50 transition-colors">
                                <td class="p-3.5 font-semibold text-ink">{{ $child['name'] }}</td>
                                <td class="p-3.5 font-mono text-ink-muted">{{ $child['nik'] }}</td>
                                <td class="p-3.5 font-mono text-ink-muted">{{ $child['age_months'] }} Bulan</td>
                                <td class="p-3.5 text-ink-muted">{{ $child['parent_name'] }}</td>
                                <td class="p-3.5 text-right">
                                    <flux:button size="sm" icon="eye" :href="route('balita.show', $child['id'])" wire:navigate class="cursor-pointer" />
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="p-8 text-center text-ink-subtle">Tidak ada data anak ditemukan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif

</div>
