<?php

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Computed;
use App\Models\Children;
use App\Models\Prediction;
use App\Models\Intervention;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

new class extends Component {
    use WithPagination;

    public Children $balita;
    public string $filterYear = '';

    // State for viewing details modal
    public ?Prediction $selectedPrediction = null;

    // State for editing prediction modal
    public ?int $editingPredictionId = null;
    public string $edit_examined_at = '';
    public string $edit_weight = '';
    public string $edit_height = '';
    public string $edit_age_months = '';
    public string $edit_notes = '';

    // State for active intervention modal
    public ?Intervention $activeIntervention = null;

    // State for deletion confirmation modal
    public ?int $deletingPredictionId = null;

    public function mount(Children $balita): void
    {
        $this->balita = $balita->load(['posyandu', 'user']);
    }

    #[Computed]
    public function latestPrediction()
    {
        return $this->balita->predictions()->latest('examined_at')->latest('id')->first();
    }

    #[Computed]
    public function predictions()
    {
        $query = $this->balita->predictions()
            ->with(['recorder', 'intervention'])
            ->latest('examined_at')
            ->latest('id');

        if ($this->filterYear) {
            $query->whereYear('examined_at', $this->filterYear);
        }

        return $query->paginate(5);
    }

    #[Computed]
    public function availableYears()
    {
        return $this->balita->predictions()
            ->pluck('examined_at')
            ->map(fn($date) => $date ? $date->format('Y') : null)
            ->filter()
            ->unique()
            ->values()
            ->toArray();
    }

    // Load prediction for viewing details
    public function viewPrediction(int $id): void
    {
        $this->selectedPrediction = Prediction::with(['recorder', 'intervention'])->findOrFail($id);
    }

    // Load prediction for editing
    public function editPrediction(int $id): void
    {
        $prediction = Prediction::findOrFail($id);
        $this->editingPredictionId = $prediction->id;
        $this->edit_examined_at = $prediction->examined_at->toDateString();
        $this->edit_weight = $prediction->weight;
        $this->edit_height = $prediction->height;
        $this->edit_age_months = $prediction->age_months;
        $this->edit_notes = $prediction->notes ?? '';
    }

    // Save edited prediction
    public function updatePrediction(): void
    {
        $this->validate([
            'edit_examined_at' => 'required|date|before_or_equal:today',
            'edit_weight' => 'required|numeric|min:0.5|max:150',
            'edit_height' => 'required|numeric|min:20|max:200',
            'edit_age_months' => 'required|integer|min:0|max:255',
            'edit_notes' => 'nullable|string|max:1000',
        ]);

        $prediction = Prediction::findOrFail($this->editingPredictionId);

        // Recalculate AI classification (using development fallback if FastAPI offline)
        $gender = $this->balita->gender === 'male' ? 0 : 1;
        $gejalaCf = [
            'R03' => '0.0',
            'R04' => '0.0',
            'R05' => '0.0',
            'R06' => '0.0',
            'R07' => '0.0',
            'R08' => '0.0',
            'R09' => '0.0',
        ];

        $apiUrl = config('services.prediction_service.url', 'http://127.0.0.1:8001') . '/predict';
        $result = 'normal';
        $confidence = 0.5000;
        $recommendations = [];

        try {
            $response = Http::timeout(5)->post($apiUrl, [
                'gender' => $gender,
                'age_months' => floatval($this->edit_age_months),
                'weight' => floatval($this->edit_weight),
                'height' => floatval($this->edit_height),
                'gejala_cf' => $gejalaCf,
            ]);

            if ($response->successful()) {
                $resData = $response->json();
                $cfTotal = $resData['kesimpulan_sistem_pakar']['tingkat_risiko_total_persen'] ?? 0;
                $recommendations = $resData['kesimpulan_sistem_pakar']['rekomendasi_intervensi'] ?? [];
                
                if ($cfTotal < 40) {
                    $result = 'normal';
                } elseif ($cfTotal < 70) {
                    $result = 'stunting_risk';
                } elseif ($cfTotal < 85) {
                    $result = 'stunted';
                } else {
                    $result = 'severely_stunted';
                }
                
                $confidence = $cfTotal / 100;
            }
        } catch (\Exception $e) {
            // Basic fallback rule of thumb calculation if API offline
            $height = floatval($this->edit_height);
            $age = intval($this->edit_age_months);
            if ($height < ($age * 2 + 50) * 0.85) {
                $result = 'stunted';
                $confidence = 0.7500;
                $recommendations = ["Saran preventif: Pastikan asupan nutrisi protein hewani terpenuhi dan lakukan penimbangan rutin."];
            } else {
                $result = 'normal';
                $confidence = 0.5000;
            }
        }

        $prediction->update([
            'examined_at' => $this->edit_examined_at,
            'weight' => $this->edit_weight,
            'height' => $this->edit_height,
            'age_months' => $this->edit_age_months,
            'notes' => $this->edit_notes,
            'result' => $result,
            'confidence' => $confidence,
        ]);

        // Manage interventions
        if ($prediction->intervention) {
            if ($result !== 'normal' && !empty($recommendations)) {
                $prediction->intervention->update([
                    'recommendation' => implode("\n", $recommendations),
                ]);
            } else {
                $prediction->intervention->delete();
            }
        } elseif ($result !== 'normal' && !empty($recommendations)) {
            Intervention::create([
                'prediction_id' => $prediction->id,
                'recommendation' => implode("\n", $recommendations),
                'status' => 'pending',
                'follow_up_date' => Carbon::parse($this->edit_examined_at)->addMonth()->toDateString(),
            ]);
        }

        // Reset editing state
        $this->reset(['editingPredictionId', 'edit_examined_at', 'edit_weight', 'edit_height', 'edit_age_months', 'edit_notes']);
        $this->dispatch('close-modal', name: 'edit-prediction-modal');
        session()->flash('success', 'Data pengukuran berhasil diperbarui.');
    }

    // Load active intervention
    public function loadActiveIntervention(): void
    {
        $this->activeIntervention = Intervention::whereIn('prediction_id', $this->balita->predictions->pluck('id'))
            ->latest('id')
            ->first();
    }

    // Select prediction for deletion
    public function selectPredictionForDeletion(int $id): void
    {
        $this->deletingPredictionId = $id;
    }

    // Delete prediction record
    public function deletePrediction(): void
    {
        if ($this->deletingPredictionId) {
            $prediction = Prediction::findOrFail($this->deletingPredictionId);
            $prediction->delete();
            
            $this->deletingPredictionId = null;
            $this->dispatch('close-modal', name: 'delete-prediction-modal');
            session()->flash('success', 'Data pengukuran berhasil dihapus.');
        }
    }
};
?>

<div :title="'Detail Balita - ' . $balita->name" class="min-h-screen bg-[#EFF7F2] font-sans pb-16">
    <div class="max-w-4xl mx-auto px-4 pt-6 md:px-8">
        
        {{-- Flash message success --}}
        @if (session()->has('success'))
            <div class="mb-6 flex gap-3 bg-[#DCFCE7] border border-[#86EFAC] rounded-xl p-4 text-[#16A34A] items-start shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div class="text-sm font-semibold">
                    {{ session('success') }}
                </div>
            </div>
        @endif

        {{-- 1. Header & Navigasi Kembali --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
            <flux:button icon="arrow-left" variant="filled" :href="route('balita.index')" wire:navigate class="w-fit cursor-pointer">
                Kembali
            </flux:button>
            <div class="flex items-center gap-3">
                <h1 class="text-2xl md:text-3xl font-bold text-[#1A2E22] tracking-tight">{{ $balita->name }}</h1>
                @if($this->latestPrediction)
                    @if($this->latestPrediction->result === 'normal')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-[#DCFCE7] text-[#16A34A] border border-[#86EFAC]">
                            🟢 Normal
                        </span>
                    @elseif($this->latestPrediction->result === 'stunting_risk')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-[#FEF3C7] text-[#D97706] border border-[#FCD34D]">
                            🟡 Risiko Stunting
                        </span>
                    @elseif($this->latestPrediction->result === 'stunted')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-[#FEE2E2] text-[#DC2626] border border-[#FCA5A5]">
                            🔴 Pendek (Stunted)
                        </span>
                    @else
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-[#FEE2E2] text-[#991B1B] border border-[#FCA5A5] font-extrabold">
                            🔴 Sangat Pendek
                        </span>
                    @endif
                @else
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-gray-100 text-gray-500 border border-gray-200">
                        Belum Diukur
                    </span>
                @endif
            </div>
        </div>

        <div class="flex flex-col gap-6">

            {{-- 2. Kartu Identitas Balita --}}
            <div class="bg-white border border-[#C0D9CA] rounded-xl p-6 md:p-8 shadow-sm">
                <div class="flex items-center gap-3 mb-6 pb-4 border-b border-[#C0D9CA]">
                    <div class="h-9 w-9 rounded-lg bg-[#E0F5EC] flex items-center justify-center shrink-0 text-lg">
                        👶
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-[#1A2E22] leading-none">Identitas Balita</h2>
                        <p class="text-xs text-[#6B8C74] mt-0.5">Data registrasi dan administrasi anak</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4 text-sm text-[#1A2E22]">
                    <div>
                        <span class="text-xs text-[#6B8C74] block uppercase font-semibold">Nama Lengkap</span>
                        <span class="font-bold">{{ $balita->name }}</span>
                    </div>
                    <div>
                        <span class="text-xs text-[#6B8C74] block uppercase font-semibold">NIK / No. KMS</span>
                        <span class="font-mono font-bold">{{ $balita->nik ?? 'Tidak ada NIK' }}</span>
                    </div>
                    <div>
                        <span class="text-xs text-[#6B8C74] block uppercase font-semibold">Jenis Kelamin</span>
                        <span class="font-medium">
                            @if($balita->gender === 'male')
                                <span class="text-blue-600 bg-blue-50 px-2.5 py-0.5 rounded-full border border-blue-200 text-xs font-bold">Laki-laki</span>
                            @else
                                <span class="text-pink-600 bg-pink-50 px-2.5 py-0.5 rounded-full border border-pink-200 text-xs font-bold">Perempuan</span>
                            @endif
                        </span>
                    </div>
                    <div>
                        <span class="text-xs text-[#6B8C74] block uppercase font-semibold">Tempat, Tanggal Lahir (Umur)</span>
                        <span class="font-medium">
                            {{ $balita->birth_place }}, {{ $balita->birth_date->format('d M Y') }}
                            <strong class="text-[#0B7A5C] font-semibold">({{ $balita->birth_date->diffInMonths(now()) }} bulan)</strong>
                        </span>
                    </div>
                    <div>
                        <span class="text-xs text-[#6B8C74] block uppercase font-semibold">Nama Orang Tua</span>
                        <span class="font-bold">{{ $balita->user->name ?? '-' }}</span>
                    </div>
                    <div>
                        <span class="text-xs text-[#6B8C74] block uppercase font-semibold">No. Telepon / WA</span>
                        <span class="font-mono font-medium">{{ $balita->user->phone ?? '-' }}</span>
                    </div>
                    <div>
                        <span class="text-xs text-[#6B8C74] block uppercase font-semibold">Posyandu Terdaftar</span>
                        <span class="font-semibold text-[#0B7A5C]">{{ $balita->posyandu->name ?? '-' }}</span>
                    </div>
                    <div class="col-span-1 md:col-span-2">
                        <span class="text-xs text-[#6B8C74] block uppercase font-semibold">Alamat Lengkap</span>
                        <span class="font-medium">{{ $balita->address }}</span>
                    </div>
                </div>
            </div>

            {{-- 3. Kartu Pengukuran Terakhir (Prediksi Terbaru) --}}
            <div class="bg-white border border-[#C0D9CA] rounded-xl p-6 md:p-8 shadow-sm">
                <div class="flex items-center gap-3 mb-6 pb-4 border-b border-[#C0D9CA]">
                    <div class="h-9 w-9 rounded-lg bg-[#E0F5EC] flex items-center justify-center shrink-0 text-lg">
                        ⚖
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-[#1A2E22] leading-none">Pengukuran & Prediksi Terakhir</h2>
                        <p class="text-xs text-[#6B8C74] mt-0.5">Hasil antropometri dan deteksi stunting AI terbaru</p>
                    </div>
                </div>

                @if($this->latestPrediction)
                    <div class="space-y-4">
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 bg-[#EFF7F2] p-4 rounded-lg border border-[#C0D9CA] text-sm">
                            <div>
                                <span class="text-[11px] text-[#6B8C74] block uppercase font-semibold">Tanggal Ukur</span>
                                <span class="font-bold text-[#1A2E22]">{{ $this->latestPrediction->examined_at->format('d M Y') }}</span>
                            </div>
                            <div>
                                <span class="text-[11px] text-[#6B8C74] block uppercase font-semibold">Usia Pemeriksaan</span>
                                <span class="font-mono font-bold text-[#1A2E22]">{{ $this->latestPrediction->age_months }} Bulan</span>
                            </div>
                            <div>
                                <span class="text-[11px] text-[#6B8C74] block uppercase font-semibold">Berat Badan</span>
                                <span class="font-mono font-bold text-[#1A2E22]">{{ $this->latestPrediction->weight }} kg</span>
                            </div>
                            <div>
                                <span class="text-[11px] text-[#6B8C74] block uppercase font-semibold">Tinggi Badan</span>
                                <span class="font-mono font-bold text-[#1A2E22]">{{ $this->latestPrediction->height }} cm</span>
                            </div>
                        </div>

                        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 border-t border-[#C0D9CA] pt-4">
                            <div>
                                <span class="text-xs text-[#6B8C74] block">Diagnosis AI (ML)</span>
                                <div class="flex items-center gap-2 mt-0.5">
                                    @if($this->latestPrediction->result === 'normal')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-bold bg-[#DCFCE7] text-[#16A34A] border border-[#86EFAC]">Normal</span>
                                    @elseif($this->latestPrediction->result === 'stunting_risk')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-bold bg-[#FEF3C7] text-[#D97706] border border-[#FCD34D]">Risiko Stunting</span>
                                    @elseif($this->latestPrediction->result === 'stunted')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-bold bg-[#FEE2E2] text-[#DC2626] border border-[#FCA5A5]">Pendek (Stunted)</span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-extrabold bg-[#FEE2E2] text-[#991B1B] border border-[#FCA5A5]">Sangat Pendek</span>
                                    @endif
                                    <span class="text-xs font-mono text-[#6B8C74]">Akurasi AI: {{ number_format($this->latestPrediction->confidence * 100, 0) }}%</span>
                                </div>
                            </div>
                            @if($this->latestPrediction->notes)
                                <div class="flex-1 sm:max-w-md">
                                    <span class="text-xs text-[#6B8C74] block">Catatan Pemeriksaan</span>
                                    <p class="text-xs text-[#1A2E22] italic mt-0.5">"{{ $this->latestPrediction->notes }}"</p>
                                </div>
                            @endif
                        </div>
                    </div>
                @else
                    <p class="text-sm text-center py-6 text-gray-500 bg-[#EFF7F2] rounded-lg border border-dashed border-[#C0D9CA]">
                        Belum ada riwayat pengukuran yang tercatat untuk anak ini.
                    </p>
                @endif
            </div>

            {{-- 4. Tombol Aksi Utama --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                <flux:button icon="plus" variant="primary" :href="route('prediksi.form', ['balita_id' => $balita->id])" wire:navigate class="w-full cursor-pointer justify-center">
                    Catat Baru
                </flux:button>
                
                <flux:button icon="pencil" variant="filled" :href="route('balita.edit', $balita->id)" wire:navigate class="w-full cursor-pointer justify-center">
                    Edit Data Balita
                </flux:button>

                <flux:modal.trigger name="active-intervention-modal">
                    <flux:button icon="document" variant="filled" wire:click="loadActiveIntervention" class="w-full cursor-pointer justify-center">
                        Lihat Intervensi
                    </flux:button>
                </flux:modal.trigger>
            </div>

            {{-- 5. Tabel Riwayat Prediksi --}}
            <div class="bg-white border border-[#C0D9CA] rounded-xl p-6 md:p-8 shadow-sm">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6 pb-4 border-b border-[#C0D9CA]">
                    <div>
                        <h2 class="text-lg font-bold text-[#1A2E22] leading-none">Riwayat Prediksi</h2>
                        <p class="text-xs text-[#6B8C74] mt-0.5 font-medium">Log rekam antropometri fisik & diagnosis berkala</p>
                    </div>

                    {{-- Filter Dropdown Tahun --}}
                    @if(!empty($this->availableYears))
                        <div class="flex items-center gap-2">
                            <label for="filterYear" class="text-xs font-semibold text-[#4A6B57]">Tahun:</label>
                            <select id="filterYear" wire:model.live="filterYear" class="px-3 py-1.5 rounded-lg border border-[#C0D9CA] text-xs text-[#1A2E22] bg-white cursor-pointer focus:ring-2 focus:ring-[#0B7A5C] outline-none">
                                <option value="">Semua</option>
                                @foreach($this->availableYears as $year)
                                    <option value="{{ $year }}">{{ $year }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                </div>

                @if($this->predictions->isEmpty())
                    <p class="text-sm text-center py-8 text-gray-500">
                        Tidak ada data pengukuran yang sesuai dengan filter.
                    </p>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-body-sm min-w-[700px]">
                            <thead>
                                <tr class="bg-[#EFF7F2] text-[#1A2E22] font-semibold border-b border-[#C0D9CA]">
                                    <th class="p-3 text-xs uppercase font-bold tracking-wider text-[#4A6B57]">Tanggal</th>
                                    <th class="p-3 text-xs uppercase font-bold tracking-wider text-[#4A6B57]">Usia (bln)</th>
                                    <th class="p-3 text-xs uppercase font-bold tracking-wider text-[#4A6B57]">BB (kg)</th>
                                    <th class="p-3 text-xs uppercase font-bold tracking-wider text-[#4A6B57]">TB (cm)</th>
                                    <th class="p-3 text-xs uppercase font-bold tracking-wider text-[#4A6B57]">Hasil AI (ML)</th>
                                    <th class="p-3 text-xs uppercase font-bold tracking-wider text-[#4A6B57]">Confidence</th>
                                    <th class="p-3 text-xs uppercase font-bold tracking-wider text-[#4A6B57]">Catatan</th>
                                    <th class="p-3 text-xs uppercase font-bold tracking-wider text-[#4A6B57] text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($this->predictions as $prediction)
                                    <tr class="border-b border-[#D5EAD9] bg-white hover:bg-[#F4FAF6] transition-colors">
                                        <td class="p-3 font-mono text-xs font-semibold text-[#1A2E22]">
                                            {{ $prediction->examined_at->format('d M Y') }}
                                        </td>
                                        <td class="p-3 font-mono text-xs text-[#1A2E22]">
                                            {{ $prediction->age_months }}
                                        </td>
                                        <td class="p-3 font-mono text-xs text-[#1A2E22]">
                                            {{ number_format($prediction->weight, 2) }}
                                        </td>
                                        <td class="p-3 font-mono text-xs text-[#1A2E22]">
                                            {{ number_format($prediction->height, 2) }}
                                        </td>
                                        <td class="p-3">
                                            @if($prediction->result === 'normal')
                                                <span class="inline-block px-2 py-0.5 bg-[#DCFCE7] text-[#16A34A] rounded text-[10px] font-bold border border-[#86EFAC]">🟢 Normal</span>
                                            @elseif($prediction->result === 'stunting_risk')
                                                <span class="inline-block px-2 py-0.5 bg-[#FEF3C7] text-[#D97706] rounded text-[10px] font-bold border border-[#FCD34D]">🟡 Risiko</span>
                                            @elseif($prediction->result === 'stunted')
                                                <span class="inline-block px-2 py-0.5 bg-[#FEE2E2] text-[#DC2626] rounded text-[10px] font-bold border border-[#FCA5A5]">🔴 Pendek</span>
                                            @else
                                                <span class="inline-block px-2 py-0.5 bg-[#FEE2E2] text-[#991B1B] rounded text-[10px] font-extrabold border border-[#FCA5A5]">🔴 Sangat Pendek</span>
                                            @endif
                                        </td>
                                        <td class="p-3 font-mono text-xs text-[#4A6B57]">
                                            {{ number_format($prediction->confidence * 100, 0) }}%
                                        </td>
                                        <td class="p-3 text-xs text-[#4A6B57] max-w-[120px] truncate" title="{{ $prediction->notes }}">
                                            {{ $prediction->notes ?: '-' }}
                                        </td>
                                        <td class="p-3 text-right">
                                            <div class="flex justify-end gap-1.5">
                                                {{-- Lihat Detail Action --}}
                                                <flux:modal.trigger name="view-prediction-modal">
                                                    <flux:button size="xs" icon="eye" wire:click="viewPrediction({{ $prediction->id }})" title="Lihat detail" class="cursor-pointer" />
                                                </flux:modal.trigger>

                                                {{-- Edit Action --}}
                                                <flux:modal.trigger name="edit-prediction-modal">
                                                    <flux:button size="xs" icon="pencil" wire:click="editPrediction({{ $prediction->id }})" title="Edit" class="cursor-pointer" />
                                                </flux:modal.trigger>

                                                {{-- Hapus Action --}}
                                                <flux:modal.trigger name="delete-prediction-modal">
                                                    <flux:button size="xs" variant="danger" icon="trash" wire:click="selectPredictionForDeletion({{ $prediction->id }})" title="Hapus" class="cursor-pointer" />
                                                </flux:modal.trigger>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- 6. Footer / Paginasi --}}
                    <div class="flex items-center justify-between border-t border-[#C0D9CA] pt-4 mt-6">
                        <flux:text size="sm" class="text-[#6B8C74] font-medium">
                            Menampilkan {{ $this->predictions->firstItem() ?? 0 }}–{{ $this->predictions->lastItem() ?? 0 }} dari {{ $this->predictions->total() }} riwayat
                        </flux:text>
                        <div class="flex gap-2">
                            <flux:button size="sm" wire:click="previousPage" :disabled="$this->predictions->onFirstPage()" class="cursor-pointer">
                                Previous
                            </flux:button>
                            <flux:button size="sm" wire:click="nextPage" :disabled="!$this->predictions->hasMorePages()" class="cursor-pointer">
                                Next
                            </flux:button>
                        </div>
                    </div>
                @endif
            </div>

        </div>
    </div>

    {{-- ================================================================= --}}
    {{-- MODAL POPUPS --}}
    {{-- ================================================================= --}}

    {{-- Modal 1: Lihat Detail Prediksi --}}
    <flux:modal name="view-prediction-modal" class="max-w-lg">
        <div class="space-y-6">
            @if($selectedPrediction)
                <div>
                    <flux:heading size="lg">Detail Pengukuran & AI</flux:heading>
                    <flux:text>Pemeriksaan pada {{ $selectedPrediction->examined_at->format('d M Y') }}</flux:text>
                </div>

                <div class="grid grid-cols-2 gap-4 bg-[#EFF7F2] p-4 rounded-lg border border-[#C0D9CA] text-sm text-[#1A2E22]">
                    <div>
                        <span class="text-xs text-[#6B8C74] block uppercase font-semibold">Usia Balita</span>
                        <span class="font-bold">{{ $selectedPrediction->age_months }} Bulan</span>
                    </div>
                    <div>
                        <span class="text-xs text-[#6B8C74] block uppercase font-semibold">Petugas Catat</span>
                        <span class="font-bold">{{ $selectedPrediction->recorder->name ?? '-' }}</span>
                    </div>
                    <div>
                        <span class="text-xs text-[#6B8C74] block uppercase font-semibold">Tinggi Badan</span>
                        <span class="font-bold font-mono">{{ $selectedPrediction->height }} cm</span>
                    </div>
                    <div>
                        <span class="text-xs text-[#6B8C74] block uppercase font-semibold">Berat Badan</span>
                        <span class="font-bold font-mono">{{ $selectedPrediction->weight }} kg</span>
                    </div>
                </div>

                <div class="space-y-1">
                    <span class="text-xs text-[#6B8C74] block font-semibold">Hasil Diagnosis AI (ML)</span>
                    <div class="flex items-center gap-2">
                        @if($selectedPrediction->result === 'normal')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-bold bg-[#DCFCE7] text-[#16A34A] border border-[#86EFAC]">Normal</span>
                        @elseif($selectedPrediction->result === 'stunting_risk')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-bold bg-[#FEF3C7] text-[#D97706] border border-[#FCD34D]">Risiko Stunting</span>
                        @elseif($selectedPrediction->result === 'stunted')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-bold bg-[#FEE2E2] text-[#DC2626] border border-[#FCA5A5]">Pendek (Stunted)</span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-extrabold bg-[#FEE2E2] text-[#991B1B] border border-[#FCA5A5]">Sangat Pendek</span>
                        @endif
                        <span class="text-xs font-mono text-[#6B8C74]">Conf Score: {{ number_format($selectedPrediction->confidence * 100, 2) }}%</span>
                    </div>
                </div>

                @if($selectedPrediction->notes)
                    <div class="space-y-1 bg-gray-50 border border-[#C0D9CA] rounded-lg p-3">
                        <span class="text-xs text-[#6B8C74] block font-semibold">Catatan Kader</span>
                        <p class="text-xs text-[#1A2E22] italic">"{{ $selectedPrediction->notes }}"</p>
                    </div>
                @endif

                <div class="border-t border-[#C0D9CA] pt-4">
                    <flux:heading size="md" class="mb-2">Intervensi Medis & Gizi</flux:heading>
                    @if($selectedPrediction->intervention)
                        <div class="space-y-3 bg-[#EFF7F2] p-4 rounded-lg border border-[#C0D9CA]">
                            <div>
                                <span class="text-xs text-[#4A6B57] uppercase font-bold tracking-wide block">Rekomendasi Rencana Tindak Lanjut</span>
                                <p class="text-xs text-[#1A2E22] leading-relaxed whitespace-pre-line mt-1">{{ $selectedPrediction->intervention->recommendation }}</p>
                            </div>
                            <div class="flex items-center justify-between text-xs text-[#4A6B57] pt-2 border-t border-[#C0D9CA]">
                                <span>Status: <strong class="uppercase">{{ $selectedPrediction->intervention->status }}</strong></span>
                                @if($selectedPrediction->intervention->follow_up_date)
                                    <span>Tindak Lanjut: <strong>{{ \Carbon\Carbon::parse($selectedPrediction->intervention->follow_up_date)->format('d M Y') }}</strong></span>
                                @endif
                            </div>
                        </div>
                    @else
                        <p class="text-xs text-[#6B8C74]">Belum ada intervensi (Hasil pemeriksaan normal / tidak ada risiko stunting).</p>
                    @endif
                </div>
            @else
                <div class="flex items-center justify-center py-8">
                    <flux:icon.loading />
                </div>
            @endif

            <div class="flex justify-end pt-4">
                <flux:modal.close>
                    <flux:button variant="ghost" class="cursor-pointer">Tutup</flux:button>
                </flux:modal.close>
            </div>
        </div>
    </flux:modal>

    {{-- Modal 2: Edit Prediksi --}}
    <flux:modal name="edit-prediction-modal" class="max-w-lg" x-on:close-modal.window="if ($event.detail.name === 'edit-prediction-modal') $flux.modal('edit-prediction-modal').close()">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Edit Data Pengukuran</flux:heading>
                <flux:text>Perbarui data antropometri dan tanggal pemeriksaan terpilih.</flux:text>
            </div>

            <form wire:submit.prevent="updatePrediction" class="space-y-4">
                <flux:input label="Tanggal Pemeriksaan" type="date" wire:model="edit_examined_at" required />
                
                <div class="grid grid-cols-2 gap-4">
                    <flux:input label="Tinggi Badan (cm)" type="number" step="0.01" wire:model="edit_height" required />
                    <flux:input label="Berat Badan (kg)" type="number" step="0.01" wire:model="edit_weight" required />
                </div>

                <flux:input label="Usia Saat Diperiksa (Bulan)" type="number" wire:model="edit_age_months" required />
                
                <flux:textarea label="Catatan" wire:model="edit_notes" placeholder="Tulis catatan jika ada..." />

                <div class="flex gap-2 justify-end pt-4">
                    <flux:modal.close>
                        <flux:button variant="ghost" class="cursor-pointer">Batal</flux:button>
                    </flux:modal.close>
                    <flux:button type="submit" variant="primary" class="cursor-pointer">
                        Simpan Perubahan
                    </flux:button>
                </div>
            </form>
        </div>
    </flux:modal>

    {{-- Modal 3: Hapus Prediksi --}}
    <flux:modal name="delete-prediction-modal" class="max-w-md" x-on:close-modal.window="if ($event.detail.name === 'delete-prediction-modal') $flux.modal('delete-prediction-modal').close()">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Hapus Riwayat Pengukuran</flux:heading>
                <flux:text>Apakah Anda yakin ingin menghapus data pengukuran ini? Semua data terkait (termasuk rekomendasi intervensi) akan dihapus secara permanen.</flux:text>
            </div>

            <div class="flex gap-2 justify-end pt-4">
                <flux:modal.close>
                    <flux:button variant="ghost" class="cursor-pointer">Batal</flux:button>
                </flux:modal.close>
                <flux:button variant="danger" wire:click="deletePrediction" class="cursor-pointer">
                    Hapus Permanen
                </flux:button>
            </div>
        </div>
    </flux:modal>

    {{-- Modal 4: Status Intervensi Aktif --}}
    <flux:modal name="active-intervention-modal" class="max-w-lg">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Rencana Intervensi Aktif</flux:heading>
                <flux:text>Rekomendasi medis & log pemantauan gizi anak.</flux:text>
            </div>

            @if($activeIntervention)
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-semibold text-[#4A6B57]">Status Penanganan</span>
                        @if($activeIntervention->status === 'pending')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-[#FEF3C7] text-[#D97706] border border-[#FCD34D]">⏳ Pending (Menunggu Validasi)</span>
                        @elseif($activeIntervention->status === 'in_progress')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-blue-50 text-blue-600 border border-blue-200">🔄 Sedang Ditangani</span>
                        @elseif($activeIntervention->status === 'done')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-[#DCFCE7] text-[#16A34A] border border-[#86EFAC]">✓ Selesai Ditangani</span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-gray-50 text-gray-500 border border-gray-200">❌ Dibatalkan</span>
                        @endif
                    </div>

                    <div class="space-y-1">
                        <span class="text-xs text-[#6B8C74] block uppercase font-bold tracking-wide">Rencana Rekomendasi Gizi</span>
                        <div class="bg-[#EFF7F2] p-4 rounded-lg border border-[#C0D9CA] text-[#1A2E22] leading-relaxed whitespace-pre-line text-xs font-medium">
                            {{ $activeIntervention->recommendation }}
                        </div>
                    </div>

                    @if($activeIntervention->follow_up_date)
                        <div>
                            <span class="text-xs text-[#6B8C74] block font-semibold">Target Jadwal Kunjungan Berikutnya</span>
                            <span class="font-medium font-mono text-sm text-[#1A2E22]">{{ \Carbon\Carbon::parse($activeIntervention->follow_up_date)->format('d M Y') }}</span>
                        </div>
                    @endif

                    @if($activeIntervention->follow_up_notes)
                        <div class="space-y-1">
                            <span class="text-xs text-[#6B8C74] block font-semibold">Catatan Evaluasi / Tindak Lanjut</span>
                            <div class="bg-gray-50 border border-[#C0D9CA] rounded-lg p-3 text-xs text-[#4A6B57] leading-relaxed">
                                {{ $activeIntervention->follow_up_notes }}
                            </div>
                        </div>
                    @endif
                </div>
            @else
                <p class="text-sm text-center py-6 text-gray-500 bg-[#EFF7F2] rounded-lg border border-dashed border-[#C0D9CA]">
                    Belum ada rencana intervensi medis/gizi aktif untuk anak ini.
                </p>
            @endif

            <div class="flex justify-end pt-4">
                <flux:modal.close>
                    <flux:button variant="ghost" class="cursor-pointer">Tutup</flux:button>
                </flux:modal.close>
            </div>
        </div>
    </flux:modal>

</div>
