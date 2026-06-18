@props(['data'])

@if(empty($data['selectedChild']))
    <div class="bg-surface-1 border border-hairline rounded-xl p-8 shadow-sm text-center">
        <span class="text-display-md block mb-4">👶</span>
        <h3 class="text-headline font-bold text-ink font-sans">Belum Ada Data Balita</h3>
        <p class="text-body-sm text-ink-muted mt-2 font-sans">Data balita Anda belum terdaftar di sistem Posyandu. Silakan hubungi kader atau bidan setempat untuk meregistrasikan balita Anda.</p>
    </div>
@else
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        
        <!-- Children Tabs Selector if more than 1 child -->
        @if(count($data['children']) > 1)
            <div class="lg:col-span-12 bg-surface-1 border border-hairline rounded-xl p-4 shadow-sm flex items-center justify-between gap-4">
                <span class="text-body-sm font-semibold text-ink font-sans">Pilih Balita:</span>
                <div class="flex gap-2 overflow-x-auto">
                    @foreach($data['children'] as $child)
                        <a href="?child_id={{ $child->id }}" class="px-4 py-2 border rounded-lg text-xs font-semibold transition-all shrink-0 font-sans {{ $data['selectedChild']->id === $child->id ? 'bg-primary-teal text-white border-primary-teal' : 'bg-canvas hover:bg-primary-light text-ink border-hairline' }}">
                            {{ $child->name }}
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
        
        <!-- Left Details: Profil & Diagnosis (5 columns) -->
        <div class="lg:col-span-5 flex flex-col gap-6 w-full">
            
            <!-- 1. Ringkasan Status Anak Terakhir -->
            @php
                $latestPred = $data['latestPrediction'];
                $statusClass = 'bg-surface-2 border-hairline';
                $badgeClass = 'bg-ink-muted text-white';
                $badgeText = 'Belum Diukur';

                if ($latestPred) {
                    if ($latestPred->result === 'severely_stunted') {
                        $statusClass = 'bg-risk-high-surface border-risk-high-border';
                        $badgeClass = 'bg-risk-high text-white';
                        $badgeText = '🔴 Sangat Pendek';
                    } elseif ($latestPred->result === 'stunted') {
                        $statusClass = 'bg-risk-high-surface border-risk-high-border';
                        $badgeClass = 'bg-risk-high text-white';
                        $badgeText = '🔴 Pendek (Stunting)';
                    } elseif ($latestPred->result === 'stunting_risk') {
                        $statusClass = 'bg-risk-medium-surface border-risk-medium-border';
                        $badgeClass = 'bg-risk-medium text-white';
                        $badgeText = '⚠️ Risiko Stunting';
                    } else {
                        $statusClass = 'bg-risk-low-surface border-risk-low-border';
                        $badgeClass = 'bg-[#16A34A] text-white';
                        $badgeText = '🟢 Normal';
                    }
                }
            @endphp
            <div class="bg-surface-1 border-l-4 {{ $latestPred && in_array($latestPred->result, ['stunted', 'severely_stunted']) ? 'border-risk-high' : ($latestPred && $latestPred->result === 'stunting_risk' ? 'border-risk-medium' : 'border-primary-teal') }} border-y border-r rounded-xl p-6 shadow-sm">
                <span class="text-caption font-bold text-ink-subtle uppercase tracking-wider block mb-4 font-sans">Profil Tumbuh Kembang</span>
                
                <div class="flex items-center gap-3.5 mb-5 border-b border-hairline-soft pb-4">
                    <div class="h-14 w-14 rounded-full bg-primary-light text-primary-teal flex items-center justify-center font-bold text-headline border border-primary-teal shrink-0">
                        {{ collect(explode(' ', $data['selectedChild']->name))->take(2)->map(fn($w) => strtoupper(substr($w, 0, 1)))->implode('') }}
                    </div>
                    <div class="flex flex-col text-left">
                        <h3 class="text-headline font-bold text-ink leading-tight font-sans">{{ $data['selectedChild']->name }}</h3>
                        <span class="text-body-sm text-ink-muted font-sans">Anak dari Ibu {{ auth()->user()->name }}</span>
                    </div>
                </div>

                <!-- Data Grid in JetBrains Mono -->
                <div class="grid grid-cols-2 gap-4 mb-5">
                    <div>
                        <span class="text-caption text-ink-subtle font-bold uppercase block font-sans">Usia Tumbuh</span>
                        <span class="text-body-default font-bold text-ink font-mono">
                            {{ $latestPred ? $latestPred->age_months : $data['selectedChild']->birth_date->diffInMonths(now()) }} <span class="text-body-sm font-normal text-ink-muted font-sans">Bulan</span>
                        </span>
                    </div>
                    <div>
                        <span class="text-caption text-ink-subtle font-bold uppercase block font-sans">Jenis Kelamin</span>
                        <span class="text-body-default font-bold text-ink font-sans">
                            {{ $data['selectedChild']->gender === 'male' ? 'Laki-laki' : 'Perempuan' }}
                        </span>
                    </div>
                    <div>
                        <span class="text-caption text-ink-subtle font-bold uppercase block font-sans">Berat Badan (BB)</span>
                        <span class="text-subhead font-bold text-ink font-mono">
                            {{ $latestPred ? number_format($latestPred->weight, 1) . ' kg' : '-' }}
                        </span>
                    </div>
                    <div>
                        <span class="text-caption text-ink-subtle font-bold uppercase block font-sans">Tinggi Badan (TB)</span>
                        <span class="text-subhead font-bold text-ink font-mono">
                            {{ $latestPred ? number_format($latestPred->height, 1) . ' cm' : '-' }}
                        </span>
                    </div>
                </div>

                <!-- Status Badge -->
                <div class="flex items-center justify-between {{ $statusClass }} border rounded-lg p-3">
                    <span class="text-body-sm font-semibold text-ink font-sans">Status Gizi (TB/U):</span>
                    <span class="px-3 py-1 {{ $badgeClass }} rounded-full text-caption font-bold flex items-center gap-1.5 shadow-sm font-sans">
                        {{ $badgeText }}
                    </span>
                </div>
            </div>

            <!-- 3. Kotak Hasil Diagnosis & Rekomendasi Makanan (FastAPI/Sistem Pakar) -->
            <div class="bg-surface-1 border border-hairline rounded-xl p-6 shadow-sm flex flex-col gap-4">
                <h3 class="text-card-title font-bold text-ink flex items-center gap-2 font-sans">
                    <span class="text-xl">🩺</span> Hasil Diagnosis & Rekomendasi
                </h3>
                
                @if($latestPred)
                    <!-- Diagnosa Box -->
                    <div class="bg-risk-medium-surface/30 border-l-4 {{ in_array($latestPred->result, ['stunted', 'severely_stunted']) ? 'border-risk-high bg-risk-high-surface/30' : 'border-risk-medium' }} p-4 rounded-r-md">
                        <p class="text-body-sm text-ink leading-relaxed font-semibold font-sans">
                            "{{ $data['latestIntervention']->recommendation ?? ($latestPred->notes ?: 'Hasil diagnosis menunjukkan tinggi badan anak dalam kondisi ' . ($latestPred->result === 'normal' ? 'normal dan baik.' : 'butuh perhatian tambahan.')) }}"
                        </p>
                    </div>
                @else
                    <div class="bg-surface-2 border-l-4 border-ink-subtle p-4 rounded-r-md">
                        <p class="text-body-sm text-ink-muted leading-relaxed font-sans">
                            Belum ada riwayat pemeriksaan dan rekomendasi tercatat.
                        </p>
                    </div>
                @endif

                <!-- Poin-Poin Solusi Nyata (Default Guidelines) -->
                <div class="flex flex-col gap-3.5 mt-2">
                    <span class="text-caption font-bold text-ink-subtle uppercase tracking-wider font-sans">Langkah Intervensi Umum:</span>
                    
                    <div class="flex items-start gap-3 text-body-sm text-ink-muted">
                        <span class="text-primary-teal font-bold shrink-0 text-md mt-0.5">🥚</span>
                        <span class="font-sans">
                            <strong class="text-ink font-semibold">Fokus Protein Hewani:</strong> Berikan telur 1-2 butir harian dan hati ayam secara bergantian (sumber zat besi optimal untuk mengejar ketertinggalan).
                        </span>
                    </div>
                    
                    <div class="flex items-start gap-3 text-body-sm text-ink-muted">
                        <span class="text-primary-teal font-bold shrink-0 text-md mt-0.5">⏱️</span>
                        <span class="font-sans">
                            <strong class="text-ink font-semibold">Feeding Rules (IDAI):</strong> Terapkan jadwal makan yang tertib maksimal 30 menit, dan hindari penggunaan gadget/distraksi selama proses makan.
                        </span>
                    </div>
                    
                    <div class="flex items-start gap-3 text-body-sm text-ink-muted">
                        <span class="text-primary-teal font-bold shrink-0 text-md mt-0.5">🧼</span>
                        <span class="font-sans">
                            <strong class="text-ink font-semibold">Pencegahan Infeksi:</strong> Pastikan peralatan makan steril dan air minum dimasak hingga mendidih sempurna guna mencegah peradangan usus halus.
                        </span>
                    </div>
                </div>
            </div>

        </div>

        <!-- Right Column: Grafik Pemantauan (7 columns) -->
        <div class="lg:col-span-7 bg-surface-1 border border-hairline rounded-xl p-6 shadow-sm flex flex-col gap-4 w-full">
            <div>
                <h3 class="text-card-title text-ink font-bold font-sans">Grafik Pemantauan Runtun Waktu</h3>
                <p class="text-body-sm text-ink-muted font-sans">Riwayat tinggi badan {{ $data['selectedChild']->name }} dibanding ambang batas WHO.</p>
            </div>

            <!-- Scroll warning for mobile accessibility -->
            <div class="block lg:hidden text-center text-caption text-ink-subtle bg-canvas border border-hairline-soft py-1.5 rounded-lg font-sans">
                ← Geser ke kanan untuk melihat grafik lengkap →
            </div>

            <!-- Custom SVG Line Chart representation matching WHO standard curves -->
            <div class="overflow-x-auto w-full">
                <div class="bg-canvas border border-hairline rounded-lg p-4 flex flex-col items-center min-w-[500px]">
                    <svg class="w-full h-64 text-primary-teal" viewBox="0 0 400 220">
                        <!-- Background color zones (WHO Standards) -->
                        <!-- Normal Area -->
                        <rect x="40" y="20" width="340" height="90" class="fill-risk-low-surface" opacity="0.45"></rect>
                        <!-- Borderline / Risk-Medium Area -->
                        <rect x="40" y="110" width="340" height="50" class="fill-risk-medium-surface" opacity="0.45"></rect>
                        <!-- Stunting / Risk-High Area -->
                        <rect x="40" y="160" width="340" height="40" class="fill-risk-high-surface" opacity="0.45"></rect>

                        <!-- Axis lines -->
                        <line x1="40" y1="20" x2="40" y2="200" stroke="#C0D9CA" stroke-width="1.5"></line>
                        <line x1="40" y1="200" x2="380" y2="200" stroke="#C0D9CA" stroke-width="1.5"></line>

                        <!-- Grid lines -->
                        <line x1="40" y1="110" x2="380" y2="110" stroke="#C0D9CA" stroke-width="1" stroke-dasharray="3 3"></line>
                        <line x1="40" y1="160" x2="380" y2="160" stroke="#C0D9CA" stroke-width="1" stroke-dasharray="3 3"></line>

                        <!-- Curve limits labels -->
                        <text x="375" y="105" fill="#16A34A" font-size="7" font-weight="bold" text-anchor="end" font-family="Inter, sans-serif">WHO Median (-2 SD limit)</text>
                        <text x="375" y="155" fill="#D97706" font-size="7" font-weight="bold" text-anchor="end" font-family="Inter, sans-serif">Batas Pendek (-3 SD limit)</text>

                        <!-- WHO standard growth line (Reference ideal curve) -->
                        <path d="M 40 100 Q 150 70, 260 45 T 380 25" fill="none" stroke="#6B8C74" stroke-width="1.5" stroke-dasharray="5 5"></path>

                        <!-- Child growth curve path (Dynamic) -->
                        @if(!empty($data['pathD']))
                            <path d="{{ $data['pathD'] }}" fill="none" stroke="#D97706" stroke-width="3" stroke-linecap="round"></path>
                        @endif

                        <!-- Data points -->
                        @foreach($data['growthPoints'] as $point)
                            @php
                                $dotColor = '#16A34A'; // green
                                if (in_array($point['result'], ['stunted', 'severely_stunted'])) {
                                    $dotColor = '#DC2626'; // red
                                } elseif ($point['result'] === 'stunting_risk') {
                                    $dotColor = '#D97706'; // orange
                                }
                            @endphp
                            <circle cx="{{ $point['x'] }}" cy="{{ $point['y'] }}" r="4" fill="{{ $dotColor }}" stroke="#FFFFFF" stroke-width="1"></circle>
                        @endforeach

                        <!-- Point label (Alert for latest state) -->
                        @if($latestPred && in_array($latestPred->result, ['stunted', 'severely_stunted']))
                            @php $lastPoint = $data['growthPoints']->last(); @endphp
                            @if($lastPoint)
                                <text x="{{ $lastPoint['x'] }}" y="{{ $lastPoint['y'] - 10 }}" fill="#DC2626" font-size="7" font-weight="bold" text-anchor="middle" font-family="Inter, sans-serif">Stunting</text>
                            @endif
                        @endif

                        <!-- Axis labels (X Axis Months) -->
                        @foreach($data['growthPoints'] as $point)
                            <text x="{{ $point['x'] }}" y="212" fill="#6B8C74" font-size="7" text-anchor="middle" font-family="Inter, sans-serif" class="{{ $loop->last ? 'font-bold' : '' }}">
                                {{ $point['age'] }} bln{{ $loop->last ? ' (Kini)' : '' }}
                            </text>
                        @endforeach

                        <!-- Y Axis TB (cm) -->
                        <text x="34" y="25" fill="#6B8C74" font-size="7" text-anchor="end" font-family="JetBrains Mono, monospace">85 cm</text>
                        <text x="34" y="110" fill="#6B8C74" font-size="7" text-anchor="end" font-family="JetBrains Mono, monospace">78 cm</text>
                        <text x="34" y="160" fill="#6B8C74" font-size="7" text-anchor="end" font-family="JetBrains Mono, monospace">75 cm</text>
                        <text x="34" y="200" fill="#6B8C74" font-size="7" text-anchor="end" font-family="JetBrains Mono, monospace">70 cm</text>
                    </svg>

                    <!-- Legend -->
                    <div class="flex flex-wrap justify-center gap-4 text-[10px] text-ink-muted mt-2 border-t border-hairline-soft pt-3 w-full font-sans">
                        <div class="flex items-center gap-1.5">
                            <span class="h-2 w-2 rounded-full bg-risk-low"></span>
                            <span>Kurva Ideal Median WHO</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <span class="h-2.5 w-2.5 rounded-full bg-risk-medium"></span>
                            <span>Tumbuh Kembang {{ $data['selectedChild']->name }}</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <span class="h-2 w-2 rounded bg-risk-high-surface border border-risk-high-border"></span>
                            <span>Zona Stunting (Risiko Tinggi)</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endif
