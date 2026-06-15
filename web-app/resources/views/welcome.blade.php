<x-layouts::public>
    <x-slot:title>Deteksi Dini & Intervensi Risiko Stunting</x-slot:title>

    <!-- SECTION 1: HERO SECTION -->
    <section class="relative overflow-hidden py-16 md:py-24 px-6 md:px-12 max-w-7xl mx-auto">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
            
            <!-- Left: Headline, Sub-headline, CTAs -->
            <div class="lg:col-span-7 flex flex-col gap-6 text-left">
                <!-- Badge -->
                <div class="inline-flex items-center gap-2 px-3 py-1.5 bg-primary-light text-primary-teal rounded-pill w-fit text-caption font-semibold">
                    <span class="flex h-2 w-2 relative">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary-teal opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-primary-teal"></span>
                    </span>
                    <span>Teknologi Hybrid AI: Machine Learning & Certainty Factor</span>
                </div>

                <!-- Headline -->
                <h1 class="text-display-md md:text-display-xl text-ink font-bold leading-tight">
                    Sistem Pakar Hybrid: Deteksi Dini & Intervensi Risiko Stunting Balita
                </h1>

                <!-- Sub-headline -->
                <p class="text-subhead text-ink-muted leading-relaxed max-w-2xl">
                    Menggabungkan akurasi <strong class="text-primary-teal font-semibold">Machine Learning (Random Forest 97.29%)</strong> untuk prediksi fisik, dan kecerdasan <strong class="text-primary-teal font-semibold">Sistem Pakar (Certainty Factor)</strong> untuk rekomendasi gizi yang valid dan personal.
                </p>

                <!-- CTA Buttons -->
                <div class="flex flex-wrap items-center gap-4 mt-2">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ route('dashboard') }}" 
                               class="inline-flex items-center justify-center gap-2 px-6 py-3.5 bg-primary-teal hover:bg-[#096B50] text-white text-button-label rounded-md shadow-md hover:scale-[1.02] active:scale-95 transition-all duration-150">
                                <span>Mulai Konsultasi (Dashboard)</span>
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                            </a>
                        @else
                            <a href="{{ route('login') }}" 
                               class="inline-flex items-center justify-center gap-2 px-6 py-3.5 bg-primary-teal hover:bg-[#096B50] text-white text-button-label rounded-md shadow-md hover:scale-[1.02] active:scale-95 transition-all duration-150">
                                <span>Mulai Konsultasi (Dashboard)</span>
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                            </a>
                        @endauth
                    @endif
                    
                    <a href="#urgensi" 
                       class="inline-flex items-center justify-center gap-2 px-6 py-3.5 bg-surface-1 border-2 border-primary-teal text-primary-teal hover:bg-primary-light text-button-label rounded-md shadow-sm transition-all duration-150">
                        <span>Pelajari Stunting</span>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 13l-7 7-7-7m14-6l-7 7-7-7"/>
                        </svg>
                    </a>
                </div>

                <!-- Mini Trust Badges -->
                <div class="grid grid-cols-3 gap-4 border-t border-hairline pt-6 mt-4">
                    <div>
                        <span class="text-data-display text-primary-teal leading-none">97.29%</span>
                        <p class="text-caption text-ink-subtle mt-1">Akurasi Machine Learning</p>
                    </div>
                    <div>
                        <span class="text-data-display text-accent-amber leading-none">CF</span>
                        <p class="text-caption text-ink-subtle mt-1">Certainty Factor Pakar</p>
                    </div>
                    <div>
                        <span class="text-data-display text-risk-low leading-none">WHO</span>
                        <p class="text-caption text-ink-subtle mt-1">Sesuai Standar Medis</p>
                    </div>
                </div>
            </div>

            <!-- Right: Beautiful SVG Hybrid AI System Representation -->
            <div class="lg:col-span-5 relative flex justify-center">
                <!-- Decorative background elements -->
                <div class="absolute -inset-4 bg-primary-light/45 rounded-full blur-3xl -z-10"></div>
                
                <!-- SVG illustration of Dashboard/System mockup -->
                <div class="w-full max-w-[420px] bg-surface-1 border border-hairline rounded-xl p-6 shadow-lg relative overflow-hidden">
                    
                    <!-- Mockup Header -->
                    <div class="flex items-center justify-between border-b border-hairline pb-4 mb-4">
                        <div class="flex items-center gap-2">
                            <div class="w-3 h-3 rounded-full bg-red-400"></div>
                            <div class="w-3 h-3 rounded-full bg-yellow-400"></div>
                            <div class="w-3 h-3 rounded-full bg-green-400"></div>
                        </div>
                        <span class="text-caption text-ink-subtle font-semibold uppercase tracking-wider">Hasil Diagnosis AI</span>
                    </div>

                    <!-- Mockup Child Identity -->
                    <div class="flex items-center gap-3 mb-5">
                        <div class="h-11 w-11 rounded-full bg-primary-light flex items-center justify-center text-primary-teal font-bold text-subhead">
                            AZ
                        </div>
                        <div class="flex flex-col">
                            <span class="text-body-default font-bold text-ink leading-tight">Azkadina Zanza</span>
                            <span class="text-caption text-ink-muted">Usia: 18 Bulan &middot; Perempuan</span>
                        </div>
                    </div>

                    <!-- Mockup Graph Curve (Time-Series) -->
                    <div class="bg-canvas border border-hairline rounded-lg p-3 mb-4">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-caption font-bold text-ink-muted">Kurva Pertumbuhan (TB/U)</span>
                            <span class="text-[10px] text-risk-medium font-bold px-1.5 py-0.5 bg-risk-medium-surface rounded">Gagal Tumbuh?</span>
                        </div>
                        <!-- Curve drawing with SVG path -->
                        <svg class="w-full h-24 text-primary-teal" viewBox="0 0 240 80">
                            <!-- Background bands (WHO standards) -->
                            <rect x="0" y="0" width="240" height="20" class="fill-risk-high-surface/30"></rect>
                            <rect x="0" y="20" width="240" height="25" class="fill-risk-medium-surface/30"></rect>
                            <rect x="0" y="45" width="240" height="35" class="fill-risk-low-surface/30"></rect>
                            
                            <!-- Grid lines -->
                            <line x1="0" y1="20" x2="240" y2="20" stroke="currentColor" stroke-dasharray="2 2" stroke-opacity="0.15"></line>
                            <line x1="0" y1="45" x2="240" y2="45" stroke="currentColor" stroke-dasharray="2 2" stroke-opacity="0.15"></line>
                            
                            <!-- Growth paths -->
                            <!-- WHO median -->
                            <path d="M 0 50 Q 80 40, 160 30 T 240 22" fill="none" stroke="#6B8C74" stroke-width="1.5" stroke-dasharray="4 4"></path>
                            <!-- Child growth curve -->
                            <path d="M 0 55 Q 60 52, 120 48 T 180 50" fill="none" stroke="#DC2626" stroke-width="2.5" stroke-linecap="round"></path>
                            
                            <!-- Point markers -->
                            <circle cx="120" cy="48" r="4.5" fill="#DC2626" stroke="#FFFFFF" stroke-width="1.5"></circle>
                            <circle cx="180" cy="50" r="4.5" fill="#DC2626" stroke="#FFFFFF" stroke-width="1.5"></circle>
                            <text x="180" y="42" fill="#DC2626" font-size="7" font-weight="bold" text-anchor="middle">TB Gagal Naik (2T)</text>
                        </svg>
                        <div class="flex justify-between text-[9px] text-ink-subtle mt-1">
                            <span>15 bln</span>
                            <span>16 bln</span>
                            <span>17 bln</span>
                            <span class="font-bold text-risk-high">18 bln (Kini)</span>
                        </div>
                    </div>

                    <!-- Mockup Inference Cards -->
                    <div class="grid grid-cols-2 gap-3 mb-4">
                        <!-- Machine Learning Card -->
                        <div class="bg-surface-1 border border-hairline rounded-lg p-2.5 flex flex-col justify-between">
                            <span class="text-[10px] font-bold text-ink-muted uppercase tracking-wider">1. Antropometri</span>
                            <div class="flex items-center gap-1.5 mt-1">
                                <span class="h-2 w-2 rounded-full bg-risk-medium"></span>
                                <span class="text-caption font-semibold text-ink">Risiko Sedang</span>
                            </div>
                            <span class="text-[9px] text-ink-subtle mt-1">Random Forest: 78.4%</span>
                        </div>
                        <!-- Expert System Card -->
                        <div class="bg-surface-1 border border-hairline rounded-lg p-2.5 flex flex-col justify-between">
                            <span class="text-[10px] font-bold text-ink-muted uppercase tracking-wider">2. Gejala Luar</span>
                            <div class="flex items-center gap-1.5 mt-1">
                                <span class="h-2 w-2 rounded-full bg-risk-high"></span>
                                <span class="text-caption font-semibold text-ink">Indikasi Infeksi</span>
                            </div>
                            <span class="text-[9px] text-ink-subtle mt-1">GTM & Sanitasi Kurang</span>
                        </div>
                    </div>

                    <!-- Final Hybrid Certainty Score -->
                    <div class="bg-risk-high-surface border-l-4 border-risk-high rounded-r-lg p-3">
                        <div class="flex justify-between items-start">
                            <div>
                                <h5 class="text-caption font-bold text-risk-high">Hasil Konsolidasi Hybrid AI:</h5>
                                <p class="text-[11px] text-ink mt-0.5 leading-normal">
                                    Balita terindikasi memiliki <strong class="font-semibold">Risiko Tinggi Stunting</strong>. Diperlukan tindakan intervensi gizi.
                                </p>
                            </div>
                            <div class="text-right">
                                <span class="text-headline font-bold text-risk-high block leading-none">89.4%</span>
                                <span class="text-[8px] font-semibold text-ink-muted uppercase block mt-1">Kepastian CF</span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </section>

    <!-- SECTION 2: URGENSI MASALAH -->
    <section id="urgensi" class="bg-surface-2/40 py-20 px-6 md:px-12 border-y border-hairline scroll-mt-16">
        <div class="max-w-7xl mx-auto">
            <!-- Section Header -->
            <div class="text-center max-w-3xl mx-auto mb-16 flex flex-col gap-3">
                <span class="text-eyebrow text-primary-teal font-bold uppercase tracking-wider">Analisis Urgensi</span>
                <h2 class="text-display-lg text-ink font-bold leading-tight">
                    Urgensi Penurunan & Pencegahan Stunting
                </h2>
                <p class="text-body-lg text-ink-muted leading-relaxed">
                    Stunting bukan sekadar tinggi badan yang pendek. Ini adalah pertaruhan masa depan kognitif dan ketahanan fisik generasi penerus bangsa.
                </p>
            </div>

            <!-- Problem Cards Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                
                <!-- Card 1: Target Nasional -->
                <div class="bg-surface-1 border border-hairline rounded-lg p-8 flex flex-col justify-between shadow-sm">
                    <div class="flex flex-col gap-4">
                        <div class="p-3 bg-primary-light text-primary-teal rounded-md w-fit">
                            <!-- Target Icon -->
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                        </div>
                        <h3 class="text-headline text-ink font-bold">Target Penurunan 14%</h3>
                        <p class="text-body-default text-ink-muted leading-relaxed">
                            Berdasarkan data SSGI, angka stunting Indonesia harus ditekan drastis demi menyongsong Indonesia Emas. Target akselerasi nasional menetapkan prevalensi stunting turun hingga angka <strong class="text-ink font-bold">14%</strong>.
                        </p>
                    </div>

                    <!-- Progress chart mockup -->
                    <div class="mt-6 border-t border-hairline pt-6">
                        <div class="flex justify-between text-caption font-semibold mb-1">
                            <span class="text-ink-muted">Kondisi Riil</span>
                            <span class="text-primary-teal">Target Nasional</span>
                        </div>
                        <div class="w-full bg-canvas rounded-full h-3.5 border border-hairline overflow-hidden relative">
                            <div class="bg-accent-amber h-full rounded-full" style="width: 70%"></div>
                            <div class="bg-primary-teal h-full absolute top-0 left-0 rounded-full" style="width: 45%"></div>
                        </div>
                        <div class="flex justify-between text-[11px] font-semibold mt-1">
                            <span class="text-accent-amber">21.6% (SSGI)</span>
                            <span class="text-primary-teal">14% (Kemenkes)</span>
                        </div>
                    </div>
                </div>

                <!-- Card 2: Dampak Buruk -->
                <div class="bg-surface-1 border border-hairline rounded-lg p-8 flex flex-col justify-between shadow-sm">
                    <div class="flex flex-col gap-4">
                        <div class="p-3 bg-risk-high-surface text-risk-high rounded-md w-fit">
                            <!-- Alarm Icon -->
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                        </div>
                        <h3 class="text-headline text-ink font-bold">Dampak Linearitas Hidup</h3>
                        <p class="text-body-default text-ink-muted leading-relaxed">
                            Stunting mengakibatkan <strong class="text-ink font-bold">penurunan kapasitas kognitif</strong> (tingkat IQ yang lebih rendah), hambatan pertumbuhan fisik linear yang permanen, serta rentan terhadap penyakit metabolik saat dewasa.
                        </p>
                    </div>

                    <div class="mt-6 border-t border-hairline pt-6 flex flex-col gap-2">
                        <div class="flex items-center gap-2 text-body-sm font-semibold text-risk-high">
                            <span>✕</span>
                            <span>Hambatan Perkembangan Sel Otak</span>
                        </div>
                        <div class="flex items-center gap-2 text-body-sm font-semibold text-risk-high">
                            <span>✕</span>
                            <span>Tinggi Badan di Bawah Standar WHO</span>
                        </div>
                        <div class="flex items-center gap-2 text-body-sm font-semibold text-risk-high">
                            <span>✕</span>
                            <span>Imunitas Rendah & Rentan Infeksi</span>
                        </div>
                    </div>
                </div>

                <!-- Card 3: Growth Faltering -->
                <div class="bg-surface-1 border border-hairline rounded-lg p-8 flex flex-col justify-between shadow-sm">
                    <div class="flex flex-col gap-4">
                        <div class="p-3 bg-risk-medium-surface text-risk-medium rounded-md w-fit">
                            <!-- Sparkles / Shield -->
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </div>
                        <h3 class="text-headline text-ink font-bold">Konsep Growth Faltering (2T)</h3>
                        <p class="text-body-default text-ink-muted leading-relaxed">
                            Pencegahan terbaik adalah melakukan intervensi <strong class="text-ink font-bold">sebelum balita stunting</strong>. Melalui pemantauan runtun waktu, tanda gagal tumbuh (<em class="italic">Growth Faltering</em>) terdeteksi jika berat badan tidak naik 2 bulan berturut-turut (2T).
                        </p>
                    </div>

                    <div class="mt-6 border-t border-hairline pt-6 bg-risk-medium-surface/50 border-l-4 border-risk-medium p-3 rounded-r-md">
                        <p class="text-body-sm font-medium text-risk-medium leading-normal">
                            "Menimbang balita secara berkala setiap bulan adalah kewajiban kader demi memutus rantai stunting sebelum terlambat."
                        </p>
                        <span class="text-[10px] text-ink-subtle font-semibold block mt-1">— Protokol Kemenkes RI</span>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- SECTION 3: CARA KERJA SISTEM (HYBRID AI ARCHITECTURE) -->
    <section id="cara-kerja" class="py-20 px-6 md:px-12 max-w-7xl mx-auto scroll-mt-16">
        <!-- Section Header -->
        <div class="text-center max-w-3xl mx-auto mb-16 flex flex-col gap-3">
            <span class="text-eyebrow text-primary-teal font-bold uppercase tracking-wider">Alur Inferensi Medis</span>
            <h2 class="text-display-lg text-ink font-bold leading-tight">
                Arsitektur Cerdas Hybrid AI System
            </h2>
            <p class="text-body-lg text-ink-muted leading-relaxed">
                Bagaimana integrasi algoritma kecerdasan buatan dan basis aturan medis mendiagnosis risiko stunting?
            </p>
        </div>

        <!-- Alur 3 Tahap (Step-by-Step Cards) -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 relative">
            
            <!-- Connector Line (Desktop Only) -->
            <div class="hidden lg:block absolute top-1/2 left-[15%] right-[15%] h-0.5 bg-hairline -translate-y-12 -z-10"></div>

            <!-- Tahap 1 -->
            <div class="bg-surface-1 border border-hairline rounded-lg p-6 flex flex-col gap-4 shadow-sm hover:scale-[1.01] transition-transform duration-200">
                <div class="flex items-center justify-between">
                    <span class="h-10 w-10 bg-primary-teal text-white rounded-full flex items-center justify-center font-bold text-headline shadow-sm">
                        1
                    </span>
                    <span class="text-caption font-bold text-primary-teal px-2.5 py-1 bg-primary-light rounded">Machine Learning</span>
                </div>
                <h3 class="text-card-title text-ink font-bold mt-2">Skrining Antropometri</h3>
                <p class="text-body text-ink-muted leading-relaxed">
                    Sistem menganalisis data fisik statis balita seperti <strong class="text-ink font-bold">Umur, Berat Badan, Tinggi Badan,</strong> dan <strong class="text-ink font-bold">BMI</strong>. Model algoritma <em class="italic">Random Forest (Akurasi 97.29%)</em> memprediksi probabilitas risiko fisik awal.
                </p>
            </div>

            <!-- Tahap 2 -->
            <div class="bg-surface-1 border border-hairline rounded-lg p-6 flex flex-col gap-4 shadow-sm hover:scale-[1.01] transition-transform duration-200">
                <div class="flex items-center justify-between">
                    <span class="h-10 w-10 bg-primary-teal text-white rounded-full flex items-center justify-center font-bold text-headline shadow-sm">
                        2
                    </span>
                    <span class="text-caption font-bold text-accent-amber px-2.5 py-1 bg-amber-50 rounded">Time-Series & Forward Chaining</span>
                </div>
                <h3 class="text-card-title text-ink font-bold mt-2">Evaluasi Runtun Waktu</h3>
                <p class="text-body text-ink-muted leading-relaxed">
                    Sistem secara otomatis melacak riwayat kunjungan bulanan balita dari database. Dengan metode runtun waktu, sistem memantau grafik kenaikan berat badan untuk langsung mendeteksi ada/tidaknya gejala <strong class="text-ink font-bold">Growth Faltering</strong> (gagal tumbuh).
                </p>
            </div>

            <!-- Tahap 3 -->
            <div class="bg-surface-1 border border-hairline rounded-lg p-6 flex flex-col gap-4 shadow-sm hover:scale-[1.01] transition-transform duration-200">
                <div class="flex items-center justify-between">
                    <span class="h-10 w-10 bg-primary-teal text-white rounded-full flex items-center justify-center font-bold text-headline shadow-sm">
                        3
                    </span>
                    <span class="text-caption font-bold text-risk-high px-2.5 py-1 bg-risk-high-surface rounded">Certainty Factor</span>
                </div>
                <h3 class="text-card-title text-ink font-bold mt-2">Keputusan Pakar Hybrid</h3>
                <p class="text-body text-ink-muted leading-relaxed">
                    Sistem mengombinasikan hasil Machine Learning dan runtun waktu dengan kuisioner gejala luar klinis (seperti riwayat BBLR, ASI eksklusif, GTM, infeksi, sanitasi). Metode <strong class="text-ink font-bold">Certainty Factor</strong> menghitung kepastian akhir (CF) dan rekomendasi intervensi.
                </p>
            </div>

        </div>
    </section>

    <!-- SECTION 4: FITUR UNGGULAN -->
    <section id="fitur" class="bg-surface-2/40 py-20 px-6 md:px-12 border-y border-hairline">
        <div class="max-w-7xl mx-auto">
            <!-- Section Header -->
            <div class="text-center max-w-3xl mx-auto mb-16 flex flex-col gap-3">
                <span class="text-eyebrow text-primary-teal font-bold uppercase tracking-wider">Fungsionalitas Utama</span>
                <h2 class="text-display-lg text-ink font-bold leading-tight">
                    Fitur Unggulan Sistem Pakar
                </h2>
                <p class="text-body-lg text-ink-muted leading-relaxed">
                    Modul-modul integrasi yang memudahkan pemantauan gizi dan koordinasi antara Ibu balita dengan tenaga medis.
                </p>
            </div>

            <!-- Grid Fitur -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                
                <!-- Fitur 1: Time Series -->
                <div class="bg-surface-1 border border-hairline rounded-lg p-6 hover:shadow-md transition-all duration-150">
                    <div class="h-12 w-12 rounded-lg bg-primary-light text-primary-teal flex items-center justify-center mb-5">
                        <!-- Line Chart Icon -->
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <h3 class="text-card-title text-ink font-bold mb-3">Pencatatan Runtun Waktu</h3>
                    <p class="text-body text-ink-muted leading-relaxed">
                        Visualisasi grafik tumbuh kembang anak terpadu (BB/U, TB/U, BB/TB) dari bulan ke bulan. Memberikan peringatan dini otomatis kepada bidan/kader ketika mendeteksi anomali kurva pertumbuhan.
                    </p>
                </div>

                <!-- Fitur 2: Expert System -->
                <div class="bg-surface-1 border border-hairline rounded-lg p-6 hover:shadow-md transition-all duration-150">
                    <div class="h-12 w-12 rounded-lg bg-primary-light text-primary-teal flex items-center justify-center mb-5">
                        <!-- Expert Brain Icon -->
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                        </svg>
                    </div>
                    <h3 class="text-card-title text-ink font-bold mb-3">Konsultasi Pakar Interaktif</h3>
                    <p class="text-body text-ink-muted leading-relaxed">
                        Mesin inferensi Certainty Factor yang menganalisis riwayat stunting, status ekonomi, sanitasi, dan gejala fisik klinis. Menghasilkan skor tingkat risiko (Normal, Risiko Sedang, Risiko Tinggi) secara real-time.
                    </p>
                </div>

                <!-- Fitur 3: Integrated Dashboard -->
                <div class="bg-surface-1 border border-hairline rounded-lg p-6 hover:shadow-md transition-all duration-150">
                    <div class="h-12 w-12 rounded-lg bg-primary-light text-primary-teal flex items-center justify-center mb-5">
                        <!-- Dashboard Icon -->
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zm0 8a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zm10 0a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1h-6a1 1 0 01-1-1v-6z"/>
                        </svg>
                    </div>
                    <h3 class="text-card-title text-ink font-bold mb-3">Dashboard Posyandu Terintegrasi</h3>
                    <p class="text-body text-ink-muted leading-relaxed">
                        Portal data induk balita untuk kader posyandu. Menyederhanakan pencatatan batch bulanan, pemetaan data geospasial risiko, ekspor pelaporan ke instansi kesehatan (Puskesmas), serta pengarsipan digital.
                    </p>
                </div>

            </div>
        </div>
    </section>

    <!-- SECTION 5: VALIDITAS MEDIS (STANDAR NASIONAL & INTERNASIONAL) -->
    <section class="py-12 bg-surface-1 border-b border-hairline">
        <div class="max-w-7xl mx-auto px-6 md:px-12 flex flex-col md:flex-row items-center justify-between gap-8">
            <div class="max-w-md text-center md:text-left">
                <span class="text-eyebrow text-primary-teal font-bold tracking-wider">Validitas Sains & Medis</span>
                <h4 class="text-headline text-ink font-bold mt-1">Acuan Rekayasa Knowledge Base</h4>
                <p class="text-body-sm text-ink-muted mt-2">
                    Mesin inferensi dan batasan perhitungan z-score dalam sistem ini merujuk sepenuhnya pada literatur kedokteran & regulasi resmi.
                </p>
            </div>
            
            <!-- Documents references badges -->
            <div class="flex flex-wrap items-center justify-center gap-6">
                <!-- Ref 1 -->
                <div class="flex items-center gap-3 px-5 py-3 bg-canvas border border-hairline rounded-md">
                    <span class="text-primary-teal font-bold text-body-lg">RI</span>
                    <div class="flex flex-col">
                        <span class="text-caption font-bold text-ink leading-tight">Permenkes RI</span>
                        <span class="text-[10px] text-ink-subtle">No. 2 Tahun 2020</span>
                    </div>
                </div>
                <!-- Ref 2 -->
                <div class="flex items-center gap-3 px-5 py-3 bg-canvas border border-hairline rounded-md">
                    <span class="text-primary-teal font-bold text-body-lg">IDAI</span>
                    <div class="flex flex-col">
                        <span class="text-caption font-bold text-ink leading-tight">Ikatan Dokter Anak</span>
                        <span class="text-[10px] text-ink-subtle">Standardisasi Kurva</span>
                    </div>
                </div>
                <!-- Ref 3 -->
                <div class="flex items-center gap-3 px-5 py-3 bg-canvas border border-hairline rounded-md">
                    <span class="text-primary-teal font-bold text-body-lg">WHO</span>
                    <div class="flex flex-col">
                        <span class="text-caption font-bold text-ink leading-tight">World Health Org</span>
                        <span class="text-[10px] text-ink-subtle">Z-Score Growth Reference</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- SECTION 6: TEAM PROFILE -->
    <section id="tim" class="py-20 px-6 md:px-12 max-w-7xl mx-auto scroll-mt-16">
        <!-- Section Header -->
        <div class="text-center max-w-3xl mx-auto mb-16 flex flex-col gap-3">
            <span class="text-eyebrow text-primary-teal font-bold uppercase tracking-wider">Tim Pengembang</span>
            <h2 class="text-display-lg text-ink font-bold leading-tight">
                SiPakar Team
            </h2>
            <p class="text-body-lg text-ink-muted leading-relaxed">
                Mahasiswa tingkat akhir Program Studi D4 Teknologi Rekayasa Perangkat Lunak, Politeknik Negeri Padang.
            </p>
        </div>

        <!-- Team Profiles Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            
            <!-- Member 1 -->
            <div class="bg-surface-1 border border-hairline rounded-lg p-6 flex flex-col items-center text-center shadow-sm hover:scale-[1.02] transition-transform duration-200">
                <div class="h-24 w-24 rounded-full bg-primary-light text-primary-teal flex items-center justify-center font-bold text-display-md mb-4 border-2 border-primary-teal">
                    ZR
                </div>
                <h3 class="text-headline text-ink font-bold leading-tight">Zaky Ramadhan</h3>
                <span class="text-caption font-semibold text-primary-teal uppercase tracking-wider mt-1 block">Project Manager & ML Engineer</span>
                <p class="text-body-sm text-ink-muted mt-3 leading-relaxed">
                    Bertanggung jawab atas manajemen sprint proyek, perancangan model Random Forest untuk antropometri, dan integrasi backend Laravel 13.
                </p>
                <div class="border-t border-hairline-soft w-full mt-5 pt-4 flex justify-center gap-2.5">
                    <span class="text-[11px] font-semibold text-ink-subtle">D4 TRPL PNP</span>
                </div>
            </div>

            <!-- Member 2 -->
            <div class="bg-surface-1 border border-hairline rounded-lg p-6 flex flex-col items-center text-center shadow-sm hover:scale-[1.02] transition-transform duration-200">
                <div class="h-24 w-24 rounded-full bg-primary-light text-primary-teal flex items-center justify-center font-bold text-display-md mb-4 border-2 border-primary-teal">
                    NK
                </div>
                <h3 class="text-headline text-ink font-bold leading-tight">Naufal Khalil Aldeza</h3>
                <span class="text-caption font-semibold text-primary-teal uppercase tracking-wider mt-1 block">Frontend & UX Lead Designer</span>
                <p class="text-body-sm text-ink-muted mt-3 leading-relaxed">
                    Bertanggung jawab atas arsitektur frontend web, pembuatan sistem desain (design tokens), implementasi Tailwind CSS, dan aspek UX aksesibilitas.
                </p>
                <div class="border-t border-hairline-soft w-full mt-5 pt-4 flex justify-center gap-2.5">
                    <span class="text-[11px] font-semibold text-ink-subtle">D4 TRPL PNP</span>
                </div>
            </div>

            <!-- Member 3 -->
            <div class="bg-surface-1 border border-hairline rounded-lg p-6 flex flex-col items-center text-center shadow-sm hover:scale-[1.02] transition-transform duration-200">
                <div class="h-24 w-24 rounded-full bg-primary-light text-primary-teal flex items-center justify-center font-bold text-display-md mb-4 border-2 border-primary-teal">
                    IF
                </div>
                <h3 class="text-headline text-ink font-bold leading-tight">Ilham Fadhli Akbar</h3>
                <span class="text-caption font-semibold text-primary-teal uppercase tracking-wider mt-1 block">Expert System & Database Architect</span>
                <p class="text-body-sm text-ink-muted mt-3 leading-relaxed">
                    Bertanggung jawab merancang knowledge base Certainty Factor, relasi data posyandu, penalaran forward chaining, dan struktur database MySQL.
                </p>
                <div class="border-t border-hairline-soft w-full mt-5 pt-4 flex justify-center gap-2.5">
                    <span class="text-[11px] font-semibold text-ink-subtle">D4 TRPL PNP</span>
                </div>
            </div>

        </div>
    </section>

    <!-- SECTION 7: FINAL CALL TO ACTION (CTA) BANNER -->
    <section class="max-w-7xl mx-auto px-6 md:px-12 pb-24">
        <div class="bg-inverse-canvas text-white rounded-xxl p-8 md:p-12 shadow-xl relative overflow-hidden flex flex-col md:flex-row items-center justify-between gap-8">
            <!-- Decorative circle backgrounds -->
            <div class="absolute -right-12 -bottom-12 w-64 h-64 rounded-full bg-primary-teal/20 blur-2xl"></div>
            <div class="absolute -left-12 -top-12 w-48 h-48 rounded-full bg-primary-teal/15 blur-2xl"></div>

            <div class="flex-1 z-10 text-center md:text-left">
                <span class="text-[11px] font-bold tracking-widest text-primary-light uppercase block mb-2">Mari Berkolaborasi</span>
                <h2 class="text-headline md:text-display-md font-bold leading-tight text-white">
                    Lindungi Tumbuh Kembang Si Kecil Bersama SiPakar Stunting
                </h2>
                <p class="text-body-lg text-primary-light/80 mt-3 leading-relaxed max-w-xl">
                    Deteksi dini risiko growth faltering secara runtun waktu membantu penanganan stunting secara efektif sebelum terlambat.
                </p>
            </div>

            <div class="z-10 shrink-0 flex flex-col sm:flex-row items-center gap-4">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ route('dashboard') }}" 
                           class="px-6 py-4 bg-white text-primary-teal font-bold text-button-label rounded-md shadow hover:bg-primary-light transition-all hover:scale-[1.02] active:scale-95 duration-150 w-full sm:w-auto text-center">
                            <span>Buka Dashboard</span>
                        </a>
                    @else
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" 
                               class="px-6 py-4 bg-white text-primary-teal font-bold text-button-label rounded-md shadow hover:bg-primary-light transition-all hover:scale-[1.02] active:scale-95 duration-150 w-full sm:w-auto text-center">
                                <span>Daftar Akun Kader</span>
                            </a>
                        @endif
                        <a href="{{ route('login') }}" 
                           class="px-6 py-4 bg-transparent border-2 border-white hover:bg-white/10 text-white font-bold text-button-label rounded-md transition-all hover:scale-[1.02] active:scale-95 duration-150 w-full sm:w-auto text-center">
                            <span>Masuk</span>
                        </a>
                    @endauth
                @endif
            </div>
        </div>
    </section>

</x-layouts::public>
