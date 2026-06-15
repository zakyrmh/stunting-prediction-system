<x-layouts::public>
    <x-slot:title>Edukasi Gizi & Stunting - Standar Medis Kemenkes/WHO</x-slot:title>

    <!-- Header Section -->
    <section class="py-12 md:py-16 px-6 md:px-12 max-w-7xl mx-auto">
        <div class="text-center max-w-3xl mx-auto flex flex-col gap-3">
            <span class="text-eyebrow text-primary-teal font-bold uppercase tracking-wider">Literasi Medis Terverifikasi</span>
            <h1 class="text-display-md md:text-display-lg text-ink font-bold leading-tight">
                Modul Edukasi Gizi & Stunting Balita
            </h1>
            <p class="text-subhead text-ink-muted leading-relaxed">
                Portal informasi klinis pencegahan stunting, deteksi dini gagal tumbuh (growth faltering), dan tata laksana gizi berdasarkan regulasi Kemenkes RI & WHO.
            </p>
        </div>
    </section>

    <!-- Main Content Layout: Sticky Sidebar + Articles -->
    <section class="pb-24 px-6 md:px-12 max-w-7xl mx-auto">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-start">
            
            <!-- Sticky Anchor Navigation (Left Column: 3 cols) -->
            <aside class="hidden lg:block lg:col-span-3 sticky top-24 bg-surface-1 border border-hairline rounded-lg p-5 shadow-sm">
                <h3 class="text-caption font-bold text-ink-subtle uppercase tracking-wider mb-4">Daftar Isi Modul</h3>
                <nav class="flex flex-col gap-2.5">
                    <a href="#fundamental" class="flex items-center gap-2.5 px-3 py-2 rounded-md text-body-sm font-medium text-ink-muted hover:bg-canvas hover:text-primary-teal transition-all border-l-2 border-transparent hover:border-primary-teal">
                        <span class="shrink-0 text-caption font-bold">01</span>
                        <span>Dasar Stunting & Z-Score</span>
                    </a>
                    <a href="#gagal-tumbuh" class="flex items-center gap-2.5 px-3 py-2 rounded-md text-body-sm font-medium text-ink-muted hover:bg-canvas hover:text-primary-teal transition-all border-l-2 border-transparent hover:border-primary-teal">
                        <span class="shrink-0 text-caption font-bold">02</span>
                        <span>Mengenal Faltering (2T)</span>
                    </a>
                    <a href="#protokol-risiko" class="flex items-center gap-2.5 px-3 py-2 rounded-md text-body-sm font-medium text-ink-muted hover:bg-canvas hover:text-primary-teal transition-all border-l-2 border-transparent hover:border-primary-teal">
                        <span class="shrink-0 text-caption font-bold">03</span>
                        <span>Panduan Kelompok Risiko</span>
                    </a>
                    <a href="#pmt-lokal" class="flex items-center gap-2.5 px-3 py-2 rounded-md text-body-sm font-medium text-ink-muted hover:bg-canvas hover:text-primary-teal transition-all border-l-2 border-transparent hover:border-primary-teal">
                        <span class="shrink-0 text-caption font-bold">04</span>
                        <span>PMT Lokal Protein Hewani</span>
                    </a>
                </nav>

                <div class="border-t border-hairline-soft mt-6 pt-5 flex flex-col gap-3">
                    <span class="text-[11px] font-semibold text-ink-subtle uppercase tracking-wider">Aksesibilitas</span>
                    <a href="{{ route('login') }}" class="w-full inline-flex items-center justify-center gap-2 py-2 bg-primary-teal hover:bg-[#096B50] text-white text-button-label rounded-md shadow-sm transition-colors text-center text-xs">
                        <span>Konsultasi Pakar</span>
                    </a>
                </div>
            </aside>

            <!-- Articles (Right Column: 9 cols) -->
            <div class="lg:col-span-9 flex flex-col gap-16">
                
                <!-- SECTION 1: EDUKASI FUNDAMENTAL -->
                <section id="fundamental" class="scroll-mt-24 flex flex-col gap-6">
                    <div class="border-b border-hairline pb-4">
                        <div class="flex items-center gap-2 text-primary-teal text-eyebrow font-bold uppercase tracking-wider">
                            <span>01 &middot;</span>
                            <span>Edukasi Fundamental</span>
                        </div>
                        <h2 class="text-display-md text-ink font-bold mt-1">Apa itu Stunting & Mengapa Terjadi?</h2>
                    </div>

                    <div class="bg-surface-1 border border-hairline rounded-lg p-6 md:p-8 flex flex-col gap-6 shadow-sm">
                        <!-- Definisi Medis -->
                        <div>
                            <h3 class="text-headline text-ink font-bold mb-2">Definisi Medis</h3>
                            <p class="text-body-default text-ink-muted leading-relaxed">
                                Stunting adalah gangguan pertumbuhan dan perkembangan anak akibat kekurangan gizi kronis dan infeksi berulang, yang ditandai dengan panjang atau tinggi badannya berada di bawah standar yang ditetapkan oleh Kementerian Kesehatan RI dan WHO.
                            </p>
                        </div>

                        <!-- Ambang Batas Klinis (Z-Score) -->
                        <div class="border-t border-hairline-soft pt-6">
                            <h3 class="text-headline text-ink font-bold mb-3">Ambang Batas Klinis (Z-Score)</h3>
                            <p class="text-body-default text-ink-muted leading-relaxed mb-4">
                                Berdasarkan <strong class="text-ink">Permenkes No. 2 Tahun 2020</strong>, diagnosis stunting ditegakkan melalui kurva antropometri panjang badan menurut umur (PB/U) atau tinggi badan menurut umur (TB/U). Klasifikasi tingkat risiko stunting dibagi sebagai berikut:
                            </p>

                            <!-- Visual Z-Score Gauge using the Risk Palette -->
                            <div class="bg-canvas border border-hairline rounded-lg p-5">
                                <span class="text-caption font-bold text-ink-subtle uppercase tracking-wider block mb-3">Z-Score PB/U atau TB/U Standard (WHO / Kemenkes)</span>
                                
                                <div class="relative w-full h-8 flex rounded-md overflow-hidden border border-hairline text-center text-caption font-bold text-white shadow-inner">
                                    <div class="bg-risk-high w-1/3 flex items-center justify-center" title="Sangat Pendek / Severely Stunted">
                                        <span>Sangat Pendek (&lt; -3 SD)</span>
                                    </div>
                                    <div class="bg-risk-medium w-1/3 flex items-center justify-center border-x border-white/20" title="Pendek / Stunted">
                                        <span>Pendek (-3 SD s/d &lt; -2 SD)</span>
                                    </div>
                                    <div class="bg-risk-low w-1/3 flex items-center justify-center" title="Normal / Tumbuh Baik">
                                        <span>Normal (&ge; -2 SD)</span>
                                    </div>
                                </div>
                                <div class="flex justify-between text-[11px] font-semibold text-ink-subtle mt-1.5 px-1">
                                    <span>-4 SD</span>
                                    <span>-3 SD (Severely Stunted)</span>
                                    <span>-2 SD (Stunted Limit)</span>
                                    <span>+2 SD (Normal)</span>
                                </div>
                            </div>
                        </div>

                        <!-- Penyebab Multi-Dimensi -->
                        <div class="border-t border-hairline-soft pt-6">
                            <h3 class="text-headline text-ink font-bold mb-3">Penyebab Multi-Dimensi</h3>
                            <p class="text-body-default text-ink-muted leading-relaxed mb-4">
                                Stunting tidak disebabkan oleh satu faktor tunggal. Kementerian Kesehatan menegaskan stunting lahir dari ketidakseimbangan gizi yang dipengaruhi secara multi-dimensi:
                            </p>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Penyebab Langsung -->
                                <div class="bg-risk-high-surface/30 border-l-4 border-risk-high p-4 rounded-r-md">
                                    <h4 class="text-body-default font-bold text-risk-high mb-1.5">Penyebab Langsung</h4>
                                    <ul class="list-disc pl-4 text-body-sm text-ink-muted flex flex-col gap-1">
                                        <li>Asupan gizi mikro & makro yang tidak adekuat dalam konsumsi harian.</li>
                                        <li>Siklus penyakit infeksi berulang (diare, TBC, ISPA) yang menguras cadangan nutrisi tubuh.</li>
                                    </ul>
                                </div>
                                <!-- Penyebab Tidak Langsung -->
                                <div class="bg-risk-medium-surface/30 border-l-4 border-risk-medium p-4 rounded-r-md">
                                    <h4 class="text-body-default font-bold text-risk-medium mb-1.5">Penyebab Tidak Langsung</h4>
                                    <ul class="list-disc pl-4 text-body-sm text-ink-muted flex flex-col gap-1">
                                        <li><strong class="text-ink font-semibold">Ketahanan pangan rumah tangga</strong> yang tidak memadai (akses pangan berkualitas rendah).</li>
                                        <li>Pola asuh kurang tepat (tidak mendapat ASI eksklusif 6 bulan).</li>
                                        <li>Buruknya akses air bersih dan sanitasi lingkungan rumah.</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                    </div>
                </section>

                <!-- SECTION 2: MENGENAL GROWTH FALTERING (GAGAL TUMBUH) -->
                <section id="gagal-tumbuh" class="scroll-mt-24 flex flex-col gap-6">
                    <div class="border-b border-hairline pb-4">
                        <div class="flex items-center gap-2 text-primary-teal text-eyebrow font-bold uppercase tracking-wider">
                            <span>02 &middot;</span>
                            <span>Deteksi Dini</span>
                        </div>
                        <h2 class="text-display-md text-ink font-bold mt-1">Memahami Growth Faltering (Gagal Tumbuh)</h2>
                    </div>

                    <div class="bg-surface-1 border border-hairline rounded-lg p-6 md:p-8 flex flex-col gap-6 shadow-sm">
                        
                        <div>
                            <h3 class="text-headline text-ink font-bold mb-2">Konsep Weight Faltering</h3>
                            <p class="text-body-default text-ink-muted leading-relaxed">
                                Sebelum seorang anak jatuh ke dalam diagnosis stunting secara fisik linear (tinggi badan pendek), tubuhnya akan menunjukkan tanda-tanda penolakan pertumbuhan pada berat badan. Berat badan yang mendatar atau turun adalah indikator paling sensitif terhadap malnutrisi akut. Kondisi ini disebut <strong class="text-ink">Weight Faltering</strong>.
                            </p>
                        </div>

                        <!-- 3 Kondisi Faltering -->
                        <div class="border-t border-hairline-soft pt-6">
                            <h3 class="text-headline text-ink font-bold mb-3">Definisi Klinis Faltering</h3>
                            <p class="text-body-default text-ink-muted leading-relaxed mb-4">
                                Menurut <em class="italic">Pedoman Pencegahan dan Tatalaksana Gizi Buruk pada Balita Kemenkes</em>, hambatan pertumbuhan (growth faltering) dikonfirmasi apabila tren kenaikan berat badan bulanan anak menunjukkan 3 kondisi ini:
                            </p>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div class="p-4 bg-canvas border border-hairline rounded-lg text-center">
                                    <span class="text-display-md">📈</span>
                                    <h4 class="text-body-default font-bold text-ink mt-2">1. Naik, Tapi Tidak Optimal</h4>
                                    <p class="text-caption text-ink-muted mt-1 leading-normal">
                                        Berat badan naik, namun tidak mencapai Kenaikan Berat Badan Minimum (KBM) sesuai usianya pada tabel KMS.
                                    </p>
                                </div>
                                <div class="p-4 bg-canvas border border-hairline rounded-lg text-center">
                                    <span class="text-display-md">➡️</span>
                                    <h4 class="text-body-default font-bold text-ink mt-2">2. Tidak Naik (Tetap)</h4>
                                    <p class="text-caption text-ink-muted mt-1 leading-normal">
                                        Hasil timbangan berat badan bulan ini sama persis dengan bulan sebelumnya (grafik mendatar).
                                    </p>
                                </div>
                                <div class="p-4 bg-canvas border border-hairline rounded-lg text-center">
                                    <span class="text-display-md">📉</span>
                                    <h4 class="text-body-default font-bold text-ink mt-2">3. Turun</h4>
                                    <p class="text-caption text-ink-muted mt-1 leading-normal">
                                        Berat badan anak menyusut dibandingkan bulan lalu akibat sakit atau asupan gizi yang drop.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Logika 2T Kemenkes -->
                        <div class="border-t border-hairline-soft pt-6">
                            <h3 class="text-headline text-ink font-bold mb-3">Logika "2T" Kemenkes (Dua Kali Tidak Naik)</h3>
                            <p class="text-body-default text-ink-muted leading-relaxed mb-5">
                                Kemenkes memberlakukan aturan <strong class="text-ink">2T (Dua kali Tidak naik berat badannya secara berturut-turut)</strong> sebagai sinyal bahaya primer. Anak dengan status 2T berisiko tinggi mengalami penurunan imunitas dan kemunduran fisik linear secara permanen. Status 2T mewajibkan rujukan segera untuk evaluasi medis intensif.
                            </p>

                            <!-- Visual 2T Logic Mockup -->
                            <div class="grid grid-cols-1 md:grid-cols-12 gap-6 items-center bg-canvas border border-hairline rounded-lg p-5">
                                <div class="md:col-span-5 flex justify-center">
                                    <!-- Visual Chart of 2T Flatline -->
                                    <svg class="w-full max-w-[240px] h-32 text-primary-teal" viewBox="0 0 200 120">
                                        <!-- Safety line (Normal) -->
                                        <path d="M 10 90 L 70 70 L 130 50 L 190 30" fill="none" stroke="#16A34A" stroke-width="2" stroke-linecap="round"></path>
                                        
                                        <!-- Flatline / 2T child -->
                                        <path d="M 10 90 L 70 70 L 130 70 L 190 70" fill="none" stroke="#DC2626" stroke-width="2.5" stroke-linecap="round"></path>
                                        
                                        <!-- Points -->
                                        <circle cx="10" cy="90" r="3" fill="#4A6B57"></circle>
                                        <circle cx="70" cy="70" r="3" fill="#4A6B57"></circle>
                                        <!-- 1T -->
                                        <circle cx="130" cy="70" r="4.5" fill="#D97706" stroke="#FFFFFF" stroke-width="1.5"></circle>
                                        <!-- 2T -->
                                        <circle cx="190" cy="70" r="4.5" fill="#DC2626" stroke="#FFFFFF" stroke-width="1.5"></circle>
                                        
                                        <!-- Labels -->
                                        <text x="130" y="85" fill="#D97706" font-size="8" font-weight="bold" text-anchor="middle">T-1 (Flat)</text>
                                        <text x="190" y="85" fill="#DC2626" font-size="8" font-weight="bold" text-anchor="middle">T-2 (2T Alert!)</text>
                                        
                                        <!-- X Axis -->
                                        <line x1="0" y1="110" x2="200" y2="110" stroke="#C0D9CA" stroke-width="1"></line>
                                        <text x="10" y="118" fill="#6B8C74" font-size="7">Bulan 1</text>
                                        <text x="70" y="118" fill="#6B8C74" font-size="7">Bulan 2</text>
                                        <text x="130" y="118" fill="#6B8C74" font-size="7">Bulan 3</text>
                                        <text x="190" y="118" fill="#6B8C74" font-size="7">Bulan 4</text>
                                    </svg>
                                </div>
                                <div class="md:col-span-7 flex flex-col gap-2">
                                    <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 bg-risk-high-surface text-risk-high rounded text-caption font-bold w-fit">
                                        🚨 LAMPU MERAH KESEHATAN
                                    </div>
                                    <h4 class="text-body-default font-bold text-ink">Konsekuensi Klinis 2T:</h4>
                                    <p class="text-body-sm text-ink-muted leading-relaxed">
                                        Jika berat badan tidak naik 2 bulan berturut-turut, cadangan lemak dan otot anak mulai dideplesi. Organ vital mengorbankan tinggi badan untuk bertahan hidup, memicu mulainya stunting kronis.
                                    </p>
                                </div>
                            </div>
                        </div>

                    </div>
                </section>

                <!-- SECTION 3: PROTOKOL KESEHATAN & GIZI (KELOMPOK RISIKO) -->
                <section id="protokol-risiko" class="scroll-mt-24 flex flex-col gap-6">
                    <div class="border-b border-hairline pb-4">
                        <div class="flex items-center gap-2 text-primary-teal text-eyebrow font-bold uppercase tracking-wider">
                            <span>03 &middot;</span>
                            <span>Protokol Kesehatan & Gizi</span>
                        </div>
                        <h2 class="text-display-md text-ink font-bold mt-1">Panduan Berdasarkan Kelompok Risiko</h2>
                    </div>

                    <div class="flex flex-col gap-6">
                        
                        <!-- Risiko 1: Masalah Makan & GTM -->
                        <div class="bg-surface-1 border border-hairline rounded-lg p-6 md:p-8 shadow-sm">
                            <div class="flex items-center gap-3 mb-4">
                                <div class="h-10 w-10 bg-amber-50 text-accent-amber rounded-lg flex items-center justify-center text-headline shrink-0">
                                    🍽️
                                </div>
                                <h3 class="text-headline text-ink font-bold">Risiko 1: Masalah Makan & GTM (Gerakan Tutup Mulut)</h3>
                            </div>
                            <p class="text-body-default text-ink-muted leading-relaxed mb-4">
                                Ikatan Dokter Anak Indonesia (IDAI) menekankan bahwa kegagalan tumbuh kembang sering berawal dari ketidakdisiplinan penerapan asuhan makan. Evaluasi fungsi oromotor (kemampuan motorik mulut untuk mengunyah dan menelan) harus diperiksa sejak dini oleh dokter.
                            </p>
                            <div class="bg-canvas border border-hairline rounded-lg p-4">
                                <h4 class="text-body-sm font-bold text-ink mb-2">Pilar Asuhan Nutrisi Pediatrik (IDAI):</h4>
                                <ul class="list-disc pl-5 text-body-sm text-ink-muted flex flex-col gap-1.5">
                                    <li><strong class="text-ink">Responsive Feeding:</strong> Pemberian makan yang responsif terhadap sinyal lapar dan kenyang anak, tidak memaksa atau mendistraksi anak saat makan (Kemenkes).</li>
                                    <li><strong class="text-ink">Pemberian Makan Terjadwal:</strong> Mengatur jadwal makan utama dan makanan selingan secara konsisten setiap hari.</li>
                                    <li><strong class="text-ink">Toleransi Durasi:</strong> Waktu makan maksimal dibatasi 30 menit demi mendidik rasa lapar secara sehat.</li>
                                </ul>
                            </div>
                        </div>

                        <!-- Risiko 2: BBLR & Prematurnitas -->
                        <div class="bg-surface-1 border border-hairline rounded-lg p-6 md:p-8 shadow-sm">
                            <div class="flex items-center gap-3 mb-4">
                                <div class="h-10 w-10 bg-primary-light text-primary-teal rounded-lg flex items-center justify-center text-headline shrink-0">
                                    👶
                                </div>
                                <h3 class="text-headline text-ink font-bold">Risiko 2: Dampak Riwayat Lahir (BBLR & Prematur)</h3>
                            </div>
                            <p class="text-body-default text-ink-muted leading-relaxed mb-4">
                                Bayi lahir dengan riwayat Berat Badan Lahir Rendah (BBLR &lt; 2.500 gram) atau lahir prematur sebelum usia kandungan mencapai 37 minggu memiliki organ metabolik yang belum berkembang secara sempurna. Hal ini membatasi kapasitas tubuh untuk menyerap nutrisi makro secara efisien.
                            </p>
                            <div class="bg-primary-light/50 border-l-4 border-primary-teal p-4 rounded-r-md">
                                <h4 class="text-body-default font-bold text-primary-teal mb-1">Catch-Up Growth (Tumbuh Kejar)</h4>
                                <p class="text-body-sm text-ink leading-relaxed">
                                    Siklus tumbuh kejar yang memadai sangat krusial pada 1.000 hari pertama. Kegagalan mencapai tumbuh kejar (catch-up growth) pada fase ini meningkatkan risiko stunting permanen. Evaluasi Z-score menggunakan grafik koreksi prematuritas wajib dilakukan secara rutin.
                                </p>
                            </div>
                        </div>

                        <!-- Risiko 3: Siklus Infeksi & Sanitasi -->
                        <div class="bg-surface-1 border border-hairline rounded-lg p-6 md:p-8 shadow-sm">
                            <div class="flex items-center gap-3 mb-4">
                                <div class="h-10 w-10 bg-risk-high-surface text-risk-high rounded-lg flex items-center justify-center text-headline shrink-0">
                                    🧼
                                </div>
                                <h3 class="text-headline text-ink font-bold">Risiko 3: Siklus Infeksi & Sanitasi Lingkungan</h3>
                            </div>
                            <p class="text-body-default text-ink-muted leading-relaxed mb-4">
                                Menurut laporan WHO, buruknya sanitasi lingkungan memicu kondisi subklinis kronis pada dinding usus halus balita yang disebut <strong class="text-ink">Environmental Enteropathy</strong> (Enteropati Lingkungan).
                            </p>
                            <div class="bg-risk-high-surface/30 border-l-4 border-risk-high p-4 rounded-r-md">
                                <h4 class="text-body-default font-bold text-risk-high mb-1">Malabsorpsi Nutrisi Kronis (WHO)</h4>
                                <p class="text-body-sm text-ink leading-relaxed">
                                    Kontaminasi bakteri feses di air minum dan makanan anak memicu peradangan dinding usus konstan. Akibatnya, permukaan usus menjadi rusak (tumpul), memicu kegagalan penyerapan sari makanan (malabsorpsi) meski asupan gizi anak sudah tercukupi.
                                </p>
                            </div>
                        </div>

                    </div>
                </section>

                <!-- SECTION 4: MODUL INTERVENSI (PMT LOKAL) -->
                <section id="pmt-lokal" class="scroll-mt-24 flex flex-col gap-6">
                    <div class="border-b border-hairline pb-4">
                        <div class="flex items-center gap-2 text-primary-teal text-eyebrow font-bold uppercase tracking-wider">
                            <span>04 &middot;</span>
                            <span>Modul Intervensi</span>
                        </div>
                        <h2 class="text-display-md text-ink font-bold mt-1">Pedoman Makanan Tambahan (PMT) Lokal</h2>
                    </div>

                    <div class="bg-surface-1 border border-hairline rounded-lg p-6 md:p-8 flex flex-col gap-6 shadow-sm">
                        
                        <!-- Fokus Protein Hewani -->
                        <div>
                            <h3 class="text-headline text-ink font-bold mb-3">Keutamaan Protein Hewani</h3>
                            <p class="text-body-default text-ink-muted leading-relaxed mb-4">
                                Berbagai penelitian gizi klinis global menegaskan bahwa pencegahan stunting secara optimal diraih melalui asupan asam amino esensial lengkap yang bersumber dari protein hewani (seperti telur, hati ayam, ikan, dan daging sapi) — bukan protein nabati.
                            </p>
                            
                            <blockquote class="bg-primary-light/50 border-l-4 border-primary-teal p-4 rounded-r-md text-body-sm font-medium text-primary-teal leading-relaxed italic">
                                "Animal-source foods are the best sources of high-quality nutrients (Makanan bersumber hewani adalah sumber terbaik untuk nutrisi berkualitas tinggi)."
                                <span class="text-[10px] text-ink-subtle font-semibold block mt-1.5 not-italic">— World Health Organization (WHO) Guidelines</span>
                            </blockquote>
                        </div>

                        <!-- Contoh Menu PMT Pemulihan Lokal -->
                        <div class="border-t border-hairline-soft pt-6">
                            <h3 class="text-headline text-ink font-bold mb-3">Resep PMT Pemulihan Berbasis Bahan Lokal</h3>
                            <p class="text-body-default text-ink-muted leading-relaxed mb-4">
                                Kementerian Kesehatan merekomendasikan resep PMT padat energi menggunakan bahan makanan lokal yang murah dan mudah didapat di pasar tradisional untuk pemulihan balita di posyandu:
                            </p>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Resep 1 -->
                                <div class="border border-hairline rounded-lg p-5 bg-canvas flex flex-col gap-3">
                                    <div class="flex items-center justify-between">
                                        <span class="text-caption font-bold text-primary-teal px-2 py-0.5 bg-primary-light rounded">Usia 6-11 Bulan</span>
                                        <span class="text-caption font-semibold text-ink-subtle">Kepadatan Kalori Tinggi</span>
                                    </div>
                                    <h4 class="text-body-default font-bold text-ink">Bubur Singkong Saus Hati Ayam</h4>
                                    <p class="text-caption text-ink-muted leading-relaxed">
                                        Menyatukan karbohidrat dari singkong halus dengan zat besi tinggi dari hati ayam. Diberi santan segar untuk menambah kalori lemak esensial guna merangsang kenaikan berat badan.
                                    </p>
                                    <div class="border-t border-hairline-soft pt-2 mt-1">
                                        <span class="text-[10px] font-bold text-ink-subtle uppercase">Kandungan Utama:</span>
                                        <span class="text-[10px] text-primary-teal font-semibold block">Protein Hewani (Hati Ayam) + Lemak Rantai Sedang (Santan)</span>
                                    </div>
                                </div>

                                <!-- Resep 2 -->
                                <div class="border border-hairline rounded-lg p-5 bg-canvas flex flex-col gap-3">
                                    <div class="flex items-center justify-between">
                                        <span class="text-caption font-bold text-accent-amber px-2 py-0.5 bg-amber-50 rounded">Usia 12-23 Bulan</span>
                                        <span class="text-caption font-semibold text-ink-subtle">Oromotor Makanan Padat</span>
                                    </div>
                                    <h4 class="text-body-default font-bold text-ink">Nasi Tim Kembung Kuning</h4>
                                    <p class="text-caption text-ink-muted leading-relaxed">
                                        Ikan kembung lokal memiliki kandungan asam lemak Omega-3 dan protein yang setara dengan ikan salmon impor. Dimasak bumbu kuning jahe untuk meningkatkan nafsu makan anak.
                                    </p>
                                    <div class="border-t border-hairline-soft pt-2 mt-1">
                                        <span class="text-[10px] font-bold text-ink-subtle uppercase">Kandungan Utama:</span>
                                        <span class="text-[10px] text-primary-teal font-semibold block">Protein Hewani (Ikan Kembung) + Omega 3 Asam Lemak</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </section>

            </div>

        </div>
    </section>
</x-layouts::public>
