<?php

use App\Models\Children;
use App\Models\Prediction;
use App\Models\Intervention;
use App\Services\StuntingPredictionService;
use Carbon\Carbon;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('Pencatatan Bulanan & Prediksi Stunting')] class extends Component {
    public array $children = [];
    public string $child_id = '';
    public string $examined_at = '';
    public string $weight = '';
    public string $height = '';
    public array $gejala = [];
    public string $notes = '';
    public bool $showResult = false;
    public ?array $predictionResult = null;

    public function mount(): void
    {
        $user = auth()->user();
        // Kader hanya boleh mencatat balita yang terdaftar di posyandu tempat ia bertugas
        $this->children = Children::where('posyandu_id', $user->posyandu_id)
            ->orderBy('name')
            ->get(['id', 'name', 'nik', 'gender', 'birth_date'])
            ->toArray();

        $this->examined_at = now()->toDateString();
        $this->gejala = [
            'R03' => '0.0', // Perlambatan Pertumbuhan Linear
            'R04' => '0.0', // Weight Faltering
            'R05' => '0.0', // Wasted
            'R06' => '0.0', // Edema Bilateral
            'R07' => '0.0', // Penyakit Infeksi Berulang
            'R08' => '0.0', // Riwayat BBLR / Prematur
            'R09' => '0.0', // Red-Flags Sistemik
        ];
    }

    public function rules(): array
    {
        return [
            'child_id' => 'required|exists:children,id',
            'examined_at' => 'required|date|before_or_equal:today',
            'weight' => 'required|numeric|min:0.5|max:150',
            'height' => 'required|numeric|min:20|max:200',
            'gejala' => 'required|array',
            'notes' => 'nullable|string|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'child_id.required' => 'Pilih balita terlebih dahulu.',
            'child_id.exists' => 'Balita yang dipilih tidak terdaftar.',
            'examined_at.required' => 'Tanggal pemeriksaan wajib diisi.',
            'examined_at.date' => 'Format tanggal pemeriksaan tidak valid.',
            'examined_at.before_or_equal' => 'Tanggal pemeriksaan tidak boleh di masa mendatang.',
            'weight.required' => 'Berat badan balita wajib diisi.',
            'weight.numeric' => 'Berat badan harus diisi berupa angka desimal.',
            'weight.min' => 'Berat badan tidak wajar (minimal 0.5 kg).',
            'weight.max' => 'Berat badan tidak wajar (maksimal 150 kg).',
            'height.required' => 'Tinggi badan balita wajib diisi.',
            'height.numeric' => 'Tinggi badan harus diisi berupa angka desimal.',
            'height.min' => 'Tinggi badan tidak wajar (minimal 20 cm).',
            'height.max' => 'Tinggi badan tidak wajar (maksimal 200 cm).',
        ];
    }

    public function save(StuntingPredictionService $service): void
    {
        $validated = $this->validate();

        // Validasi tambahan: Tanggal pemeriksaan tidak boleh sebelum tanggal lahir balita
        $child = Children::find($this->child_id);
        if ($child) {
            $birthDate = Carbon::parse($child->birth_date);
            $examinedAt = Carbon::parse($this->examined_at);
            if ($examinedAt->lt($birthDate)) {
                $this->addError('examined_at', 'Tanggal pemeriksaan tidak boleh sebelum tanggal lahir balita (' . $birthDate->format('d/m/Y') . ').');
                return;
            }
        }

        try {
            // Panggil service untuk hitung umur, hubungi FastAPI, simpan prediksi & intervensi
            $prediction = $service->createPrediction($validated, auth()->user());

            // Siapkan data hasil untuk ditampilkan langsung ke user
            $intervention = Intervention::where('prediction_id', $prediction->id)->first();
            
            $this->predictionResult = [
                'id' => $prediction->id,
                'child_name' => $child->name,
                'nik' => $child->nik ?? '-',
                'gender' => $child->gender === 'male' ? 'Laki-laki' : 'Perempuan',
                'birth_date' => $child->birth_date->format('d/m/Y'),
                'age_months' => $prediction->age_months,
                'weight' => floatval($prediction->weight),
                'height' => floatval($prediction->height),
                'result' => $prediction->result,
                'confidence' => floatval($prediction->confidence),
                'notes' => $prediction->notes,
                'recommendations' => $intervention ? explode("\n", $intervention->recommendation) : [
                    "Pertahankan pola makan dengan gizi seimbang.",
                    "Rutin melakukan kunjungan ke Posyandu setiap bulan untuk memantau tumbuh kembang."
                ],
                'follow_up_date' => $intervention ? Carbon::parse($intervention->follow_up_date)->format('d/m/Y') : null,
            ];
            
            $this->showResult = true;
            session()->flash('success', 'Data pengukuran berhasil disimpan dan dianalisis oleh sistem pakar AI.');
        } catch (\Exception $e) {
            $this->addError('general', 'Terjadi kesalahan sistem saat menghubungi server AI: ' . $e->getMessage());
        }
    }

    public function resetForm(): void
    {
        $this->child_id = '';
        $this->weight = '';
        $this->height = '';
        $this->notes = '';
        $this->gejala = [
            'R03' => '0.0',
            'R04' => '0.0',
            'R05' => '0.0',
            'R06' => '0.0',
            'R07' => '0.0',
            'R08' => '0.0',
            'R09' => '0.0',
        ];
        $this->examined_at = now()->toDateString();
        $this->showResult = false;
        $this->predictionResult = null;
    }
}; ?>

<div class="min-h-screen bg-[#EFF7F2] font-sans pb-16">
    {{-- Breadcrumb --}}
    <div class="px-4 pt-5 md:px-8 max-w-4xl mx-auto">
        <nav class="flex items-center gap-2 text-sm text-[#4A6B57]" aria-label="Breadcrumb">
            <a href="{{ route('prediksi.index') }}" class="hover:text-[#0B7A5C] transition-colors" wire:navigate>
                Riwayat Pengukuran
            </a>
            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-[#6B8C74]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
            </svg>
            <span class="text-[#1A2E22] font-semibold">Pencatatan Bulanan & Prediksi Stunting</span>
        </nav>
    </div>

    {{-- Main Container --}}
    <div class="max-w-4xl mx-auto px-4 pt-6 md:px-8">
        
        {{-- Section Header --}}
        <div class="mb-8">
            <p class="text-xs font-semibold text-[#0B7A5C] uppercase tracking-widest mb-2">
                Registrasi & Input Bulanan
            </p>
            <h1 class="text-3xl font-bold text-[#1A2E22] tracking-tight leading-tight">
                Pencatatan Bulanan & Prediksi
            </h1>
            <p class="mt-2 text-base text-[#4A6B57] leading-relaxed">
                Catat data antropometri fisik balita dan evaluasi gejala klinis secara berkala. Sistem Pakar Hybrid AI akan langsung mendeteksi risiko stunting dan memberikan rekomendasi intervensi medis secara instan.
            </p>
        </div>

        {{-- Toast Flash Success --}}
        @if (session()->has('success'))
            <div class="mb-6 flex gap-3 bg-[#DCFCE7] border border-[#86EFAC] rounded-xl p-4 text-[#16A34A] items-start">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div class="text-sm font-semibold">
                    {{ session('success') }}
                </div>
            </div>
        @endif

        {{-- Validation Error Box --}}
        @if ($errors->any())
            <div class="mb-6 flex gap-3 bg-[#FEE2E2] border border-[#FCA5A5] rounded-xl p-4 text-[#DC2626] items-start" style="border-left: 4px solid #DC2626;">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                <div>
                    <p class="font-bold text-sm">Terdapat beberapa kesalahan input data:</p>
                    <ul class="mt-1 list-disc list-inside text-xs space-y-0.5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        {{-- ========================================== --}}
        {{-- VIEW HARI INI: HASIL PREDIKSI (Jika Ada) --}}
        {{-- ========================================== --}}
        @if ($showResult && $predictionResult)
            <div class="flex flex-col gap-6">
                
                {{-- Detection Result Card --}}
                @php
                    $resultType = $predictionResult['result'];
                    $borderClass = 'border-l-[6px] border-l-[#16A34A]';
                    $bgClass = 'bg-[#DCFCE7]';
                    $badgeClass = 'bg-[#DCFCE7] text-[#16A34A] border border-[#86EFAC]';
                    $statusText = 'Normal / Sehat';
                    $icon = '✓';
                    
                    if ($resultType === 'stunting_risk') {
                        $borderClass = 'border-l-[6px] border-l-[#D97706]';
                        $bgClass = 'bg-[#FEF3C7]';
                        $badgeClass = 'bg-[#FEF3C7] text-[#D97706] border border-[#FCD34D]';
                        $statusText = 'Risiko Stunting (Perlu Atensi)';
                        $icon = '!';
                    } elseif ($resultType === 'stunted') {
                        $borderClass = 'border-l-[6px] border-l-[#DC2626]';
                        $bgClass = 'bg-[#FEE2E2]';
                        $badgeClass = 'bg-[#FEE2E2] text-[#DC2626] border border-[#FCA5A5]';
                        $statusText = 'Pendek (Stunted)';
                        $icon = '⚠';
                    } elseif ($resultType === 'severely_stunted') {
                        $borderClass = 'border-l-[6px] border-l-[#DC2626]';
                        $bgClass = 'bg-[#FEE2E2]';
                        $badgeClass = 'bg-[#FEE2E2] text-[#DC2626] border border-[#FCA5A5]';
                        $statusText = 'Sangat Pendek (Severely Stunted)';
                        $icon = '⚠';
                    }
                @endphp
                
                <div class="bg-white border border-[#C0D9CA] {{ $borderClass }} rounded-xl p-6 md:p-8 shadow-sm">
                    <div class="flex flex-wrap items-center justify-between gap-4 border-b border-[#C0D9CA] pb-4 mb-6">
                        <div>
                            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-bold {{ $badgeClass }}">
                                <span class="font-bold">{{ $icon }}</span> {{ $statusText }}
                            </span>
                            <span class="text-xs text-[#4A6B57] ml-2 block sm:inline mt-1 sm:mt-0">Tanggal Ukur: {{ now()->format('d/m/Y') }}</span>
                        </div>
                        <div class="text-right">
                            <span class="text-xs text-[#6B8C74] block">Nilai Kepastian Hybrid AI</span>
                            <span class="font-mono text-xl font-bold text-[#1A2E22]">{{ number_format($predictionResult['confidence'] * 100, 1) }}%</span>
                        </div>
                    </div>

                    {{-- Data Balita Grid --}}
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 bg-[#EFF7F2] p-4 rounded-lg mb-6 border border-[#C0D9CA]">
                        <div>
                            <span class="text-[11px] text-[#6B8C74] block uppercase font-semibold">Nama Anak</span>
                            <span class="text-sm font-bold text-[#1A2E22]">{{ $predictionResult['child_name'] }}</span>
                        </div>
                        <div>
                            <span class="text-[11px] text-[#6B8C74] block uppercase font-semibold">Usia Pemeriksaan</span>
                            <span class="text-sm font-bold text-[#1A2E22] font-mono">{{ $predictionResult['age_months'] }} Bulan</span>
                        </div>
                        <div>
                            <span class="text-[11px] text-[#6B8C74] block uppercase font-semibold">Berat Badan</span>
                            <span class="text-sm font-bold text-[#1A2E22] font-mono">{{ $predictionResult['weight'] }} kg</span>
                        </div>
                        <div>
                            <span class="text-[11px] text-[#6B8C74] block uppercase font-semibold">Tinggi Badan</span>
                            <span class="text-sm font-bold text-[#1A2E22] font-mono">{{ $predictionResult['height'] }} cm</span>
                        </div>
                    </div>

                    {{-- Interpretasi Bahasa Sederhana --}}
                    <div class="mb-6">
                        <h4 class="text-sm font-bold text-[#1A2E22] mb-1">Diagnosis Tumbuh Kembang</h4>
                        <p class="text-sm text-[#4A6B57] leading-relaxed">
                            @if ($resultType === 'normal')
                                Selamat! Pertumbuhan fisik si kecil tergolong normal dan sehat sesuai dengan standar WHO. Silakan pertahankan asupan gizi yang baik ini.
                            @elseif ($resultType === 'stunting_risk')
                                **Perhatian:** Tinggi badan si kecil berada di batas ambang toleransi pertumbuhan normal. Model mendeteksi adanya kecenderungan melambat (growth faltering). Penanganan dini sangat efektif dilakukan pada tahap ini.
                            @elseif ($resultType === 'stunted')
                                **Status Pendek:** Hasil antropometri menunjukkan tinggi badan anak kurang untuk usianya. Diperlukan peningkatan gizi dan pemeriksaan medis lebih lanjut untuk mencegah stunting yang lebih parah.
                            @elseif ($resultType === 'severely_stunted')
                                **Status Sangat Pendek:** Tinggi badan anak berada jauh di bawah garis rata-rata. Kondisi ini masuk ke dalam kategori darurat gizi buruk klinis, memerlukan rujukan medis ke rumah sakit atau dokter spesialis anak sesegera mungkin.
                            @endif
                        </p>
                    </div>

                    {{-- Rekomendasi Sistem Pakar --}}
                    <div class="border-t border-[#C0D9CA] pt-6 mb-6">
                        <h4 class="text-sm font-bold text-[#1A2E22] mb-3 flex items-center gap-1.5">
                            <span class="text-lg">📋</span> Rekomendasi Rencana Intervensi Gizi & Medis:
                        </h4>
                        <ul class="space-y-2 text-sm text-[#1A2E22]">
                            @foreach ($predictionResult['recommendations'] as $rec)
                                <li class="flex items-start gap-2 bg-[#EFF7F2] p-3 rounded-lg border border-[#C0D9CA]">
                                    <span class="text-[#0B7A5C] font-bold mt-0.5">✓</span>
                                    <span>{{ $rec }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    {{-- Target Follow Up --}}
                    @if ($predictionResult['follow_up_date'])
                        <div class="bg-[#FEF3C7] border border-[#FCD34D] rounded-lg p-3 text-xs text-[#D97706] mb-6 flex items-center gap-2">
                            <span class="text-base">📅</span>
                            <span><strong>Target Kunjungan Berikutnya:</strong> Pemeriksaan ulang dijadwalkan pada tanggal <strong>{{ $predictionResult['follow_up_date'] }}</strong>. Log rujukan/intervensi otomatis dibuat dengan status <strong>Pending</strong> menunggu verifikasi bidan.</span>
                        </div>
                    @endif

                    {{-- Catatan --}}
                    @if ($predictionResult['notes'])
                        <div class="bg-gray-50 border border-[#C0D9CA] rounded-lg p-3 text-xs text-[#4A6B57] mb-6">
                            <strong>Catatan Tambahan Kader:</strong> {{ $predictionResult['notes'] }}
                        </div>
                    @endif

                    {{-- Action Buttons --}}
                    <div class="flex flex-col sm:flex-row gap-3">
                        <button type="button" wire:click="resetForm" class="w-full sm:w-auto px-6 py-3 bg-[#0B7A5C] text-white text-sm font-semibold rounded-[10px] hover:bg-[#096B50] transition-colors focus:ring-2 focus:ring-offset-2 focus:ring-[#0B7A5C] min-h-[44px]">
                            Ukur Balita Lain
                        </button>
                        <a href="{{ route('prediksi.index') }}" class="w-full sm:w-auto px-6 py-3 border border-[#0B7A5C] text-[#0B7A5C] text-sm font-semibold rounded-[10px] bg-white hover:bg-[#E0F5EC] transition-colors text-center focus:ring-2 focus:ring-offset-2 focus:ring-[#0B7A5C] min-h-[44px]" wire:navigate>
                            Kembali ke Riwayat
                        </a>
                    </div>

                </div>
            </div>

        {{-- ========================================== --}}
        {{-- FORM INPUT PENCATATAN --}}
        {{-- ========================================== --}}
        @else
            <form wire:submit.prevent="save" id="form-pencatatan-bulanan" class="flex flex-col gap-6" novalidate>
                @csrf
                
                {{-- Card 1: Identitas & Pengukuran Fisik --}}
                <div class="bg-white border border-[#C0D9CA] rounded-xl p-6 md:p-8 shadow-sm">
                    
                    {{-- Card Header --}}
                    <div class="flex items-center gap-3 mb-6 pb-4 border-b border-[#C0D9CA]">
                        <div class="h-9 w-9 rounded-lg bg-[#E0F5EC] flex items-center justify-center shrink-0">
                            <span class="text-lg">⚖</span>
                        </div>
                        <div>
                            <h2 class="text-lg font-bold text-[#1A2E22] leading-none">Data Fisik Antropometri</h2>
                            <p class="text-xs text-[#6B8C74] mt-0.5">Identitas anak dan hasil timbang/ukur</p>
                        </div>
                    </div>

                    {{-- Grid 2-kolom --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        
                        {{-- Pilih Balita --}}
                        <div class="flex flex-col gap-1.5">
                            <label for="input-child-id" class="text-sm font-semibold text-[#1A2E22]">
                                Pilih Balita <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <select wire:model="child_id" id="input-child-id" required class="w-full px-3.5 py-3 rounded-[10px] border border-[#C0D9CA] text-sm text-[#1A2E22] bg-white transition-colors min-h-[48px] focus:outline-none focus:ring-2 focus:ring-[#0B7A5C] cursor-pointer appearance-none">
                                    <option value="">— Pilih Balita Posyandu —</option>
                                    @foreach ($children as $c)
                                        <option value="{{ $c['id'] }}">
                                            {{ $c['name'] }} (NIK: {{ $c['nik'] ?? 'Tidak ada NIK' }}) — {{ $c['gender'] === 'male' ? 'L' : 'P' }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="absolute right-3.5 top-1/2 -translate-y-1/2 pointer-events-none text-[#4A6B57]">
                                    ▼
                                </div>
                            </div>
                            @error('child_id')
                                <p class="text-xs text-[#DC2626] mt-0.5">{{ $message }}</p>
                            @else
                                <p class="text-xs text-[#6B8C74]">Hanya menampilkan anak yang aktif terdaftar di Posyandu Anda.</p>
                            @enderror
                        </div>

                        {{-- Tanggal Pemeriksaan --}}
                        <div class="flex flex-col gap-1.5">
                            <label for="input-examined-at" class="text-sm font-semibold text-[#1A2E22]">
                                Tanggal Pemeriksaan <span class="text-red-500">*</span>
                            </label>
                            <input type="date" wire:model="examined_at" id="input-examined-at" required max="{{ now()->toDateString() }}" class="w-full px-3.5 py-3 rounded-[10px] border border-[#C0D9CA] text-sm text-[#1A2E22] bg-white transition-colors min-h-[48px] focus:outline-none focus:ring-2 focus:ring-[#0B7A5C]"/>
                            @error('examined_at')
                                <p class="text-xs text-[#DC2626] mt-0.5">{{ $message }}</p>
                            @else
                                <p class="text-xs text-[#6B8C74]">Tanggal saat pengukuran fisik dilakukan.</p>
                            @enderror
                        </div>

                        {{-- Berat Badan (BB) --}}
                        <div class="flex flex-col gap-1.5">
                            <label for="input-weight" class="text-sm font-semibold text-[#1A2E22]">
                                Berat Badan <span class="text-red-500">*</span>
                            </label>
                            <div class="flex rounded-[10px] overflow-hidden border border-[#C0D9CA] focus-within:ring-2 focus-within:ring-[#0B7A5C]">
                                <input type="number" step="0.01" wire:model="weight" id="input-weight" placeholder="Contoh: 12.5" required class="flex-1 px-3.5 py-3 text-sm text-[#1A2E22] bg-white border-none focus:outline-none font-mono min-h-[48px]"/>
                                <span class="bg-[#E4F2EA] px-4 flex items-center justify-center text-xs font-semibold text-[#1A2E22] border-l border-[#C0D9CA]">
                                    kg
                                </span>
                            </div>
                            @error('weight')
                                <p class="text-xs text-[#DC2626] mt-0.5">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Tinggi Badan (TB) --}}
                        <div class="flex flex-col gap-1.5">
                            <label for="input-height" class="text-sm font-semibold text-[#1A2E22]">
                                Tinggi Badan <span class="text-red-500">*</span>
                            </label>
                            <div class="flex rounded-[10px] overflow-hidden border border-[#C0D9CA] focus-within:ring-2 focus-within:ring-[#0B7A5C]">
                                <input type="number" step="0.01" wire:model="height" id="input-height" placeholder="Contoh: 85.4" required class="flex-1 px-3.5 py-3 text-sm text-[#1A2E22] bg-white border-none focus:outline-none font-mono min-h-[48px]"/>
                                <span class="bg-[#E4F2EA] px-4 flex items-center justify-center text-xs font-semibold text-[#1A2E22] border-l border-[#C0D9CA]">
                                    cm
                                </span>
                            </div>
                            @error('height')
                                <p class="text-xs text-[#DC2626] mt-0.5">{{ $message }}</p>
                            @enderror
                        </div>

                    </div>
                </div>

                {{-- Card 2: Gejala Klinis & Faktor Risiko (Certainty Factor) --}}
                <div class="bg-white border border-[#C0D9CA] rounded-xl p-6 md:p-8 shadow-sm">
                    
                    {{-- Card Header --}}
                    <div class="flex items-center gap-3 mb-6 pb-4 border-b border-[#C0D9CA]">
                        <div class="h-9 w-9 rounded-lg bg-[#E0F5EC] flex items-center justify-center shrink-0">
                            <span class="text-lg">🩺</span>
                        </div>
                        <div>
                            <h2 class="text-lg font-bold text-[#1A2E22] leading-none">Penilaian Gejala & Riwayat Kesehatan</h2>
                            <p class="text-xs text-[#6B8C74] mt-0.5">Input tingkat keyakinan gejala klinis untuk analisis Certainty Factor</p>
                        </div>
                    </div>

                    {{-- Info Box --}}
                    <div class="flex gap-3 bg-[#E0F2FE] border-l-4 border-l-[#0369A1] rounded-xl p-4 mb-6">
                        <span class="text-base mt-0.5">ℹ</span>
                        <p class="text-xs text-[#1A2E22] leading-relaxed">
                            Pilihlah tingkat keyakinan dokter/petugas posyandu terhadap gejala yang saat ini sedang diamati atau berdasarkan riwayat kesehatan balita. Isian ini dikombinasikan dengan data fisik untuk menghitung tingkat risiko stunting secara presisi.
                        </p>
                    </div>

                    {{-- Gejala CF Form Grid --}}
                    <div class="flex flex-col gap-5">
                        
                        {{-- R03 --}}
                        <div class="grid grid-cols-1 md:grid-cols-12 gap-3 items-center pb-4 border-b border-[#D5EAD9]">
                            <div class="md:col-span-8">
                                <span class="text-xs font-mono font-bold text-[#0B7A5C] bg-[#E0F5EC] px-2 py-0.5 rounded">R03</span>
                                <label for="gejala-r03" class="text-sm font-bold text-[#1A2E22] ml-1">Perlambatan Pertumbuhan Linear (Linear Faltering)</label>
                                <p class="text-xs text-[#6B8C74] mt-0.5">Laju penambahan tinggi badan anak terindikasi melambat/mendatar selama 2 bulan berturut-turut.</p>
                            </div>
                            <div class="md:col-span-4">
                                <select wire:model="gejala.R03" id="gejala-r03" class="w-full px-3 py-2 rounded-lg border border-[#C0D9CA] text-xs text-[#1A2E22] bg-white cursor-pointer focus:ring-2 focus:ring-[#0B7A5C]">
                                    <option value="0.0">Tidak Ada / Normal (0.0)</option>
                                    <option value="0.2">Kurang Yakin (0.2)</option>
                                    <option value="0.6">Cukup Yakin (0.6)</option>
                                    <option value="0.8">Yakin (0.8)</option>
                                    <option value="1.0">Sangat Yakin / Pasti (1.0)</option>
                                </select>
                            </div>
                        </div>

                        {{-- R04 --}}
                        <div class="grid grid-cols-1 md:grid-cols-12 gap-3 items-center pb-4 border-b border-[#D5EAD9]">
                            <div class="md:col-span-8">
                                <span class="text-xs font-mono font-bold text-[#0B7A5C] bg-[#E0F5EC] px-2 py-0.5 rounded">R04</span>
                                <label for="gejala-r04" class="text-sm font-bold text-[#1A2E22] ml-1">Weight Faltering (Gagal Tumbuh Berat)</label>
                                <p class="text-xs text-[#6B8C74] mt-0.5">Laju penambahan berat badan tidak sesuai standar, tidak naik atau cenderung turun.</p>
                            </div>
                            <div class="md:col-span-4">
                                <select wire:model="gejala.R04" id="gejala-r04" class="w-full px-3 py-2 rounded-lg border border-[#C0D9CA] text-xs text-[#1A2E22] bg-white cursor-pointer focus:ring-2 focus:ring-[#0B7A5C]">
                                    <option value="0.0">Tidak Ada / Normal (0.0)</option>
                                    <option value="0.2">Kurang Yakin (0.2)</option>
                                    <option value="0.6">Cukup Yakin (0.6)</option>
                                    <option value="0.8">Yakin (0.8)</option>
                                    <option value="1.0">Sangat Yakin / Pasti (1.0)</option>
                                </select>
                            </div>
                        </div>

                        {{-- R05 --}}
                        <div class="grid grid-cols-1 md:grid-cols-12 gap-3 items-center pb-4 border-b border-[#D5EAD9]">
                            <div class="md:col-span-8">
                                <span class="text-xs font-mono font-bold text-[#0B7A5C] bg-[#E0F5EC] px-2 py-0.5 rounded">R05</span>
                                <label for="gejala-r05" class="text-sm font-bold text-[#1A2E22] ml-1">Wasted (Kurus berdasarkan BB/TB)</label>
                                <p class="text-xs text-[#6B8C74] mt-0.5">Kondisi balita terlihat kurus akibat penurunan berat badan secara akut.</p>
                            </div>
                            <div class="md:col-span-4">
                                <select wire:model="gejala.R05" id="gejala-r05" class="w-full px-3 py-2 rounded-lg border border-[#C0D9CA] text-xs text-[#1A2E22] bg-white cursor-pointer focus:ring-2 focus:ring-[#0B7A5C]">
                                    <option value="0.0">Tidak Ada / Normal (0.0)</option>
                                    <option value="0.2">Kurang Yakin (0.2)</option>
                                    <option value="0.6">Cukup Yakin (0.6)</option>
                                    <option value="0.8">Yakin (0.8)</option>
                                    <option value="1.0">Sangat Yakin / Pasti (1.0)</option>
                                </select>
                            </div>
                        </div>

                        {{-- R06 --}}
                        <div class="grid grid-cols-1 md:grid-cols-12 gap-3 items-center pb-4 border-b border-[#D5EAD9]">
                            <div class="md:col-span-8">
                                <span class="text-xs font-mono font-bold text-[#0B7A5C] bg-[#E0F5EC] px-2 py-0.5 rounded">R06</span>
                                <label for="gejala-r06" class="text-sm font-bold text-[#1A2E22] ml-1">Edema Bilateral (Pitting)</label>
                                <p class="text-xs text-[#6B8C74] mt-0.5">Adanya pembengkakan berisi cairan pada kedua kaki/punggung tangan (gejala klinis gizi buruk akut).</p>
                            </div>
                            <div class="md:col-span-4">
                                <select wire:model="gejala.R06" id="gejala-r06" class="w-full px-3 py-2 rounded-lg border border-[#C0D9CA] text-xs text-[#1A2E22] bg-white cursor-pointer focus:ring-2 focus:ring-[#0B7A5C]">
                                    <option value="0.0">Tidak Ada / Normal (0.0)</option>
                                    <option value="0.2">Kurang Yakin (0.2)</option>
                                    <option value="0.6">Cukup Yakin (0.6)</option>
                                    <option value="0.8">Yakin (0.8)</option>
                                    <option value="1.0">Sangat Yakin / Pasti (1.0)</option>
                                </select>
                            </div>
                        </div>

                        {{-- R07 --}}
                        <div class="grid grid-cols-1 md:grid-cols-12 gap-3 items-center pb-4 border-b border-[#D5EAD9]">
                            <div class="md:col-span-8">
                                <span class="text-xs font-mono font-bold text-[#0B7A5C] bg-[#E0F5EC] px-2 py-0.5 rounded">R07</span>
                                <label for="gejala-r07" class="text-sm font-bold text-[#1A2E22] ml-1">Penyakit Infeksi Berulang (Diare/ISPA)</label>
                                <p class="text-xs text-[#6B8C74] mt-0.5">Mengalami infeksi pernapasan akut atau diare kronis berulang kali dalam beberapa bulan terakhir.</p>
                            </div>
                            <div class="md:col-span-4">
                                <select wire:model="gejala.R07" id="gejala-r07" class="w-full px-3 py-2 rounded-lg border border-[#C0D9CA] text-xs text-[#1A2E22] bg-white cursor-pointer focus:ring-2 focus:ring-[#0B7A5C]">
                                    <option value="0.0">Tidak Ada / Normal (0.0)</option>
                                    <option value="0.2">Kurang Yakin (0.2)</option>
                                    <option value="0.6">Cukup Yakin (0.6)</option>
                                    <option value="0.8">Yakin (0.8)</option>
                                    <option value="1.0">Sangat Yakin / Pasti (1.0)</option>
                                </select>
                            </div>
                        </div>

                        {{-- R08 --}}
                        <div class="grid grid-cols-1 md:grid-cols-12 gap-3 items-center pb-4 border-b border-[#D5EAD9]">
                            <div class="md:col-span-8">
                                <span class="text-xs font-mono font-bold text-[#0B7A5C] bg-[#E0F5EC] px-2 py-0.5 rounded">R08</span>
                                <label for="gejala-r08" class="text-sm font-bold text-[#1A2E22] ml-1">Riwayat BBLR / Prematur</label>
                                <p class="text-xs text-[#6B8C74] mt-0.5">Anak lahir kurang bulan (prematur) ATAU memiliki Berat Badan Lahir Rendah di bawah 2.5 kg.</p>
                            </div>
                            <div class="md:col-span-4">
                                <select wire:model="gejala.R08" id="gejala-r08" class="w-full px-3 py-2 rounded-lg border border-[#C0D9CA] text-xs text-[#1A2E22] bg-white cursor-pointer focus:ring-2 focus:ring-[#0B7A5C]">
                                    <option value="0.0">Tidak Ada / Normal (0.0)</option>
                                    <option value="0.2">Kurang Yakin (0.2)</option>
                                    <option value="0.6">Cukup Yakin (0.6)</option>
                                    <option value="0.8">Yakin (0.8)</option>
                                    <option value="1.0">Sangat Yakin / Pasti (1.0)</option>
                                </select>
                            </div>
                        </div>

                        {{-- R09 --}}
                        <div class="grid grid-cols-1 md:grid-cols-12 gap-3 items-center">
                            <div class="md:col-span-8">
                                <span class="text-xs font-mono font-bold text-[#0B7A5C] bg-[#E0F5EC] px-2 py-0.5 rounded">R09</span>
                                <label for="gejala-r09" class="text-sm font-bold text-[#1A2E22] ml-1">Red-Flags Sistemik (Muntah/Demam)</label>
                                <p class="text-xs text-[#6B8C74] mt-0.5">Gejala muntah terus-menerus, demam tinggi, atau lesu parah yang butuh penanganan darurat.</p>
                            </div>
                            <div class="md:col-span-4">
                                <select wire:model="gejala.R09" id="gejala-r09" class="w-full px-3 py-2 rounded-lg border border-[#C0D9CA] text-xs text-[#1A2E22] bg-white cursor-pointer focus:ring-2 focus:ring-[#0B7A5C]">
                                    <option value="0.0">Tidak Ada / Normal (0.0)</option>
                                    <option value="0.2">Kurang Yakin (0.2)</option>
                                    <option value="0.6">Cukup Yakin (0.6)</option>
                                    <option value="0.8">Yakin (0.8)</option>
                                    <option value="1.0">Sangat Yakin / Pasti (1.0)</option>
                                </select>
                            </div>
                        </div>

                    </div>
                </div>

                {{-- Card 3: Catatan Tambahan --}}
                <div class="bg-white border border-[#C0D9CA] rounded-xl p-6 md:p-8 shadow-sm">
                    <div class="flex items-center gap-3 mb-6 pb-4 border-b border-[#C0D9CA]">
                        <div class="h-9 w-9 rounded-lg bg-[#E0F5EC] flex items-center justify-center shrink-0">
                            <span class="text-lg">📝</span>
                        </div>
                        <div>
                            <h2 class="text-lg font-bold text-[#1A2E22] leading-none">Catatan Petugas</h2>
                            <p class="text-xs text-[#6B8C74] mt-0.5">Informasi kualitatif tambahan yang relevan</p>
                        </div>
                    </div>

                    <div class="flex flex-col gap-1.5">
                        <label for="input-notes" class="text-sm font-semibold text-[#1A2E22]">Catatan Tambahan (Opsional)</label>
                        <textarea wire:model="notes" id="input-notes" rows="3" placeholder="Contoh: Balita menolak ditimbang pada awalnya. Kondisi lemas." class="w-full px-3.5 py-3 rounded-[10px] border border-[#C0D9CA] text-sm text-[#1A2E22] placeholder:text-[#9EB3A4] bg-white resize-vertical transition-colors focus:outline-none focus:ring-2 focus:ring-[#0B7A5C]"></textarea>
                        @error('notes')
                            <p class="text-xs text-[#DC2626] mt-0.5">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Disclaimer --}}
                <div class="flex gap-3 bg-[#FEF3C7] border-l-4 border-l-[#D97706] rounded-xl p-4 text-xs text-[#1A2E22]">
                    <span class="text-base text-[#D97706] shrink-0 mt-0.5">🔒</span>
                    <p class="leading-relaxed">
                        <strong>Pernyataan Privasi:</strong> Data medis dan hasil deteksi stunting balita bersifat rahasia dan dilindungi undang-undang. Data ini hanya digunakan untuk kepentingan verifikasi bidan puskesmas dan rujukan intervensi posyandu.
                    </p>
                </div>

                {{-- Action Buttons --}}
                <div class="flex flex-col sm:flex-row items-center gap-3 sm:gap-4">
                    <button type="submit" class="w-full sm:w-auto px-6 py-3 bg-[#0B7A5C] text-white text-sm font-semibold rounded-[10px] hover:bg-[#096B50] transition-colors flex items-center justify-center gap-2 min-h-[48px] focus:ring-2 focus:ring-offset-2 focus:ring-[#0B7A5C]">
                        <svg wire:loading.class="hidden" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <svg wire:loading class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Simpan & Deteksi AI
                    </button>
                    <a href="{{ route('prediksi.index') }}" class="w-full sm:w-auto px-6 py-3 border border-[#0B7A5C] text-[#0B7A5C] text-sm font-semibold rounded-[10px] bg-white hover:bg-[#E0F5EC] transition-colors text-center focus:ring-2 focus:ring-offset-2 focus:ring-[#0B7A5C] min-h-[48px]" wire:navigate>
                        Batal
                    </a>
                </div>

            </form>
        @endif

    </div>
</div>