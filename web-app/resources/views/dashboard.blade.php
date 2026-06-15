<x-layouts::app :title="__('Dashboard')">
    <div class="flex h-full w-full flex-1 flex-col gap-6 p-4 md:p-6 bg-canvas text-ink font-sans">
        
        <!-- Welcome banner common for all roles -->
        <div class="bg-surface-1 border border-hairline rounded-xl p-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 shadow-sm">
            <div>
                <h1 class="text-headline font-bold text-ink leading-tight">Selamat Datang, {{ auth()->user()->name }}!</h1>
                <p class="text-body-sm text-ink-muted mt-1">
                    Anda masuk sebagai 
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 bg-primary-light text-primary-teal rounded text-caption font-bold">
                        @if(auth()->user()->isBidan())
                            Bidan / Tenaga Kesehatan (Super User)
                        @elseif(auth()->user()->isKader())
                            Kader Posyandu (Data Entry / Operator)
                        @elseif(auth()->user()->isOrangTua())
                            Orang Tua / Ibu Balita (Viewer)
                        @endif
                    </span>
                </p>
            </div>
            <div class="text-caption text-ink-subtle font-medium bg-canvas border border-hairline px-3 py-1.5 rounded-md">
                Hari ini: {{ \Carbon\Carbon::now()->translatedFormat('l, d M Y') }}
            </div>
        </div>

        <!-- ROLE 1: BIDAN / TENAGA KESEHATAN (SUPER USER) -->
        @if(auth()->user()->isBidan())
            <!-- Widget Statistik Global -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Stat Card 1: Total Balita -->
                <div class="bg-surface-1 border-l-4 border-primary-teal border-y border-r border-hairline rounded-lg p-6 shadow-sm flex flex-col justify-between">
                    <div>
                        <span class="text-caption font-bold text-ink-subtle uppercase tracking-wider block">Total Balita Terdaftar</span>
                        <span class="text-display-md text-ink font-bold block mt-2">148</span>
                    </div>
                    <p class="text-caption text-ink-muted mt-4">
                        <span class="text-risk-low font-bold">↑ 4 Balita baru</span> bulan ini di wilayah Puskesmas.
                    </p>
                </div>

                <!-- Stat Card 2: Balita Stunting -->
                <div class="bg-surface-1 border-l-4 border-risk-high border-y border-r border-hairline rounded-lg p-6 shadow-sm flex flex-col justify-between">
                    <div>
                        <span class="text-caption font-bold text-ink-subtle uppercase tracking-wider block">Balita Stunting (Lampu Merah)</span>
                        <div class="flex items-baseline gap-2 mt-2">
                            <span class="text-display-md text-risk-high font-bold">12</span>
                            <span class="text-body-sm font-semibold text-risk-high">(8.1%)</span>
                        </div>
                    </div>
                    <p class="text-caption text-ink-muted mt-4">
                        Tinggi badan di bawah <strong class="text-risk-high font-semibold">-2 SD WHO</strong> (Butuh Intervensi Gizi).
                    </p>
                </div>

                <!-- Stat Card 3: Growth Faltering -->
                <div class="bg-surface-1 border-l-4 border-risk-medium border-y border-r border-hairline rounded-lg p-6 shadow-sm flex flex-col justify-between">
                    <div>
                        <span class="text-caption font-bold text-ink-subtle uppercase tracking-wider block">Gagal Tumbuh (Lampu Kuning)</span>
                        <div class="flex items-baseline gap-2 mt-2">
                            <span class="text-display-md text-risk-medium font-bold">24</span>
                            <span class="text-body-sm font-semibold text-risk-medium">(16.2%)</span>
                        </div>
                    </div>
                    <p class="text-caption text-ink-muted mt-4">
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
                    <span class="px-2.5 py-1 bg-risk-high-surface text-risk-high font-bold text-xs rounded">3 Menunggu</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-body-sm">
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
                            <tr class="border-b border-hairline-soft hover:bg-canvas/40 transition-colors">
                                <td class="p-3 font-semibold">Azkadina Zanza</td>
                                <td class="p-3">18 Bulan</td>
                                <td class="p-3 font-mono font-bold text-risk-high">89.4%</td>
                                <td class="p-3">
                                    <span class="px-2.5 py-0.5 bg-risk-high-surface text-risk-high rounded text-xs font-semibold">Risiko Tinggi</span>
                                </td>
                                <td class="p-3 text-right">
                                    <button class="px-3.5 py-1.5 bg-primary-teal hover:bg-[#096B50] text-white text-xs font-semibold rounded transition-colors cursor-pointer">
                                        Tinjau Rekomendasi Gizi
                                    </button>
                                </td>
                            </tr>
                            <tr class="border-b border-hairline-soft hover:bg-canvas/40 transition-colors">
                                <td class="p-3 font-semibold">Muhammad Fathan</td>
                                <td class="p-3">24 Bulan</td>
                                <td class="p-3 font-mono font-bold text-risk-medium">76.5%</td>
                                <td class="p-3">
                                    <span class="px-2.5 py-0.5 bg-risk-medium-surface text-risk-medium rounded text-xs font-semibold">Risiko Sedang</span>
                                </td>
                                <td class="p-3 text-right">
                                    <button class="px-3.5 py-1.5 bg-primary-teal hover:bg-[#096B50] text-white text-xs font-semibold rounded transition-colors cursor-pointer">
                                        Tinjau Rekomendasi Gizi
                                    </button>
                                </td>
                            </tr>
                            <tr class="border-b border-hairline-soft hover:bg-canvas/40 transition-colors">
                                <td class="p-3 font-semibold">Rayyan Alfarizqi</td>
                                <td class="p-3">12 Bulan</td>
                                <td class="p-3 font-mono font-bold text-risk-high">81.2%</td>
                                <td class="p-3">
                                    <span class="px-2.5 py-0.5 bg-risk-high-surface text-risk-high rounded text-xs font-semibold">Risiko Tinggi</span>
                                </td>
                                <td class="p-3 text-right">
                                    <button class="px-3.5 py-1.5 bg-primary-teal hover:bg-[#096B50] text-white text-xs font-semibold rounded transition-colors cursor-pointer">
                                        Tinjau Rekomendasi Gizi
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        <!-- ROLE 2: KADER POSYANDU (DATA ENTRY / OPERATOR) -->
        @elseif(auth()->user()->isKader())
            <!-- Widget Operasional Posyandu Hari Ini -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Balita Hadir -->
                <div class="bg-surface-1 border border-hairline rounded-lg p-6 shadow-sm flex flex-col justify-between">
                    <div>
                        <span class="text-caption font-bold text-ink-subtle uppercase tracking-wider block">Balita Ditimbang Hari Ini</span>
                        <span class="text-display-md text-ink font-bold block mt-2">34</span>
                    </div>
                    <span class="text-caption text-risk-low font-medium mt-4">
                        ✓ Entri data posyandu aktif berjalan.
                    </span>
                </div>

                <!-- Target Belum Datang -->
                <div class="bg-surface-1 border border-hairline rounded-lg p-6 shadow-sm flex flex-col justify-between">
                    <div>
                        <span class="text-caption font-bold text-ink-subtle uppercase tracking-wider block">Belum Hadir Bulan Ini</span>
                        <span class="text-display-md text-ink-muted font-bold block mt-2">12</span>
                    </div>
                    <span class="text-caption text-ink-subtle mt-4">
                        Harap ingatkan orang tua untuk hadir menimbang.
                    </span>
                </div>

                <!-- Notifikasi Cepat -->
                <div class="bg-risk-medium-surface border border-risk-medium-border rounded-lg p-5 flex flex-col gap-2">
                    <span class="text-caption font-bold text-risk-medium uppercase tracking-wider flex items-center gap-1">
                        ⚠️ Peringatan Dini
                    </span>
                    <p class="text-body-sm text-ink leading-normal">
                        Ada <strong class="font-bold text-risk-high">3 balita</strong> di Posyandu Anda mengalami Gagal Tumbuh (2T) bulan ini. Segera picu kuesioner gejala luar!
                    </p>
                </div>
            </div>

            <!-- Aksi Cepat Operasional -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <a href="{{ route('balita.form') }}" class="bg-surface-1 hover:bg-primary-light border border-hairline rounded-xl p-8 shadow-sm flex items-center justify-between transition-all hover:scale-102 group">
                    <div class="flex items-center gap-4">
                        <div class="h-14 w-14 bg-primary-light text-primary-teal rounded-full flex items-center justify-center text-display-md">
                            ➕
                        </div>
                        <div class="flex flex-col text-left">
                            <h3 class="text-headline font-bold text-ink group-hover:text-primary-teal transition-colors">Tambah Balita Baru</h3>
                            <p class="text-body-sm text-ink-muted mt-1">Registrasi identitas awal anak dan orang tua.</p>
                        </div>
                    </div>
                    <span class="text-headline text-primary-teal opacity-0 group-hover:opacity-100 transition-opacity">➔</span>
                </a>

                <a href="{{ route('prediksi.form') }}" class="bg-surface-1 hover:bg-primary-light border border-hairline rounded-xl p-8 shadow-sm flex items-center justify-between transition-all hover:scale-102 group">
                    <div class="flex items-center gap-4">
                        <div class="h-14 w-14 bg-primary-light text-primary-teal rounded-full flex items-center justify-center text-display-md">
                            ⚖️
                        </div>
                        <div class="flex flex-col text-left">
                            <h3 class="text-headline font-bold text-ink group-hover:text-primary-teal transition-colors">Input Catatan Bulanan</h3>
                            <p class="text-body-sm text-ink-muted mt-1">Catat berat/tinggi bulanan & hitung stunting.</p>
                        </div>
                    </div>
                    <span class="text-headline text-primary-teal opacity-0 group-hover:opacity-100 transition-opacity">➔</span>
                </a>
            </div>

            <!-- Tabel Aktivitas Kunjungan Terakhir -->
            <div class="bg-surface-1 border border-hairline rounded-lg p-6 shadow-sm">
                <div class="flex items-center justify-between mb-4 border-b border-hairline-soft pb-3">
                    <div>
                        <h3 class="text-headline text-ink font-bold">Aktivitas Kunjungan Posyandu Hari Ini</h3>
                        <p class="text-body-sm text-ink-muted">Entri data pengukuran balita terbaru untuk verifikasi kebenaran ketik angka.</p>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-body-sm">
                        <thead>
                            <tr class="bg-surface-2 text-ink font-semibold border-b border-hairline">
                                <th class="p-3">Balita</th>
                                <th class="p-3">Berat Badan (BB)</th>
                                <th class="p-3">Tinggi Badan (TB)</th>
                                <th class="p-3">Waktu Input</th>
                                <th class="p-3 text-right">Status Entri</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="border-b border-hairline-soft hover:bg-canvas/40 transition-colors">
                                <td class="p-3 font-semibold">Khansa Inara</td>
                                <td class="p-3 font-mono">10.2 kg</td>
                                <td class="p-3 font-mono">82.5 cm</td>
                                <td class="p-3">10:15 WIB</td>
                                <td class="p-3 text-right text-risk-low font-bold">✓ Tersimpan</td>
                            </tr>
                            <tr class="border-b border-hairline-soft hover:bg-canvas/40 transition-colors">
                                <td class="p-3 font-semibold">Zayan Ghaisan</td>
                                <td class="p-3 font-mono">12.4 kg</td>
                                <td class="p-3 font-mono">91.2 cm</td>
                                <td class="p-3">10:05 WIB</td>
                                <td class="p-3 text-right text-risk-low font-bold">✓ Tersimpan</td>
                            </tr>
                            <tr class="border-b border-hairline-soft hover:bg-canvas/40 transition-colors">
                                <td class="p-3 font-semibold">Aira Nabila</td>
                                <td class="p-3 font-mono">9.8 kg</td>
                                <td class="p-3 font-mono">78.4 cm</td>
                                <td class="p-3">09:50 WIB</td>
                                <td class="p-3 text-right text-risk-low font-bold">✓ Tersimpan</td>
                            </tr>
                            <tr class="border-b border-hairline-soft hover:bg-canvas/40 transition-colors">
                                <td class="p-3 font-semibold">Kaysan Syah</td>
                                <td class="p-3 font-mono">11.5 kg</td>
                                <td class="p-3 font-mono">88.0 cm</td>
                                <td class="p-3">09:30 WIB</td>
                                <td class="p-3 text-right text-risk-low font-bold">✓ Tersimpan</td>
                            </tr>
                            <tr class="border-b border-hairline-soft hover:bg-canvas/40 transition-colors">
                                <td class="p-3 font-semibold">Naura Shiza</td>
                                <td class="p-3 font-mono">8.9 kg</td>
                                <td class="p-3 font-mono">76.2 cm</td>
                                <td class="p-3">09:15 WIB</td>
                                <td class="p-3 text-right text-risk-low font-bold">✓ Tersimpan</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        <!-- ROLE 3: ORANG TUA / IBU BALITA (VIEWER / READ-ONLY) -->
        @elseif(auth()->user()->isOrangTua())
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
                
                <!-- Left Details (5 cols) -->
                <div class="lg:col-span-5 flex flex-col gap-6">
                    <!-- Ringkasan Status Anak Terakhir -->
                    <div class="bg-surface-1 border-l-4 border-risk-medium border-y border-r border-hairline rounded-xl p-6 shadow-sm">
                        <span class="text-caption font-bold text-ink-subtle uppercase tracking-wider block mb-4">Profil Tumbuh Kembang</span>
                        
                        <div class="flex items-center gap-3.5 mb-5 border-b border-hairline-soft pb-4">
                            <div class="h-14 w-14 rounded-full bg-primary-light text-primary-teal flex items-center justify-center font-bold text-headline border border-primary-teal">
                                RP
                            </div>
                            <div class="flex flex-col text-left">
                                <h3 class="text-headline font-bold text-ink leading-tight">Rania Putri</h3>
                                <span class="text-body-sm text-ink-muted">Anak Ibu {{ auth()->user()->name }}</span>
                            </div>
                        </div>

                        <!-- Data Grid in JetBrains Mono -->
                        <div class="grid grid-cols-2 gap-4 mb-5">
                            <div>
                                <span class="text-[10px] text-ink-subtle font-bold uppercase block">Usia Tumbuh</span>
                                <span class="text-body-default font-bold text-ink">15 Bulan</span>
                            </div>
                            <div>
                                <span class="text-[10px] text-ink-subtle font-bold uppercase block">Jenis Kelamin</span>
                                <span class="text-body-default font-bold text-ink">Perempuan</span>
                            </div>
                            <div>
                                <span class="text-[10px] text-ink-subtle font-bold uppercase block">Berat Badan (BB)</span>
                                <span class="text-subhead font-bold text-ink font-mono">9.2 kg</span>
                            </div>
                            <div>
                                <span class="text-[10px] text-ink-subtle font-bold uppercase block">Tinggi Badan (TB)</span>
                                <span class="text-subhead font-bold text-ink font-mono">76.5 cm</span>
                            </div>
                        </div>

                        <!-- Status Badge -->
                        <div class="flex items-center justify-between bg-risk-medium-surface border border-risk-medium-border rounded-md p-3.5">
                            <span class="text-body-sm font-semibold text-ink">Status Gizi (TB/U):</span>
                            <span class="px-3 py-1 bg-risk-medium text-white rounded-full text-caption font-bold flex items-center gap-1 shadow-sm">
                                ⚠️ Waspada Gagal Tumbuh
                            </span>
                        </div>
                    </div>

                    <!-- Diagnosis Box & Recommendations -->
                    <div class="bg-surface-1 border border-hairline rounded-xl p-6 shadow-sm flex flex-col gap-4">
                        <h3 class="text-card-title font-bold text-ink flex items-center gap-2">
                            <span>🩺</span> Diagnosis & Rekomendasi Gizi
                        </h3>
                        
                        <div class="bg-risk-high-surface/30 border-l-4 border-risk-high p-4 rounded-r-md">
                            <p class="text-body-sm text-ink leading-relaxed font-medium">
                                "Hasil pemeriksaan Posyandu terakhir menunjukkan Ananda membutuhkan perhatian pada sektor zat gizi mikro."
                            </p>
                        </div>

                        <div class="flex flex-col gap-3">
                            <span class="text-caption font-bold text-ink-subtle uppercase tracking-wider">Langkah Intervensi Orang Tua:</span>
                            <div class="flex items-start gap-2.5 text-body-sm text-ink-muted">
                                <span class="text-primary-teal font-bold shrink-0">🥚</span>
                                <span><strong>Fokus Protein Hewani:</strong> Berikan telur 1-2 butir harian dan hati ayam secara bergantian (sumber zat besi optimal).</span>
                            </div>
                            <div class="flex items-start gap-2.5 text-body-sm text-ink-muted">
                                <span class="text-primary-teal font-bold shrink-0">⏱️</span>
                                <span><strong>Penerapan Feeding Rules (IDAI):</strong> Jadwal makan tertib maksimal 30 menit, dan hindari gadget/distraksi saat menyuapi.</span>
                            </div>
                            <div class="flex items-start gap-2.5 text-body-sm text-ink-muted">
                                <span class="text-primary-teal font-bold shrink-0">🧼</span>
                                <span><strong>Pencegahan Infeksi:</strong> Pastikan peralatan makan steril dan air minum dimasak hingga mendidih (cegah peradangan usus).</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Time-Series Chart (7 cols) -->
                <div class="lg:col-span-7 bg-surface-1 border border-hairline rounded-xl p-6 shadow-sm flex flex-col gap-4">
                    <div>
                        <h3 class="text-card-title text-ink font-bold">Grafik Pemantauan Runtun Waktu (Time-Series)</h3>
                        <p class="text-body-sm text-ink-muted">Pemantauan riwayat tinggi badan Rania Putri dibanding ambang batas WHO.</p>
                    </div>

                    <!-- Custom SVG Line Chart representation matching WHO standard curves -->
                    <div class="bg-canvas border border-hairline rounded-lg p-4 flex flex-col items-center">
                        <svg class="w-full h-64 text-primary-teal" viewBox="0 0 400 220">
                            <!-- Background color zones (WHO Standards) -->
                            <!-- Normal Area -->
                            <rect x="40" y="20" width="340" height="90" class="fill-risk-low-surface/40"></rect>
                            <!-- Borderline / Risk-Medium Area -->
                            <rect x="40" y="110" width="340" height="50" class="fill-risk-medium-surface/40"></rect>
                            <!-- Stunting / Risk-High Area -->
                            <rect x="40" y="160" width="340" height="40" class="fill-risk-high-surface/40"></rect>

                            <!-- Axis lines -->
                            <line x1="40" y1="20" x2="40" y2="200" stroke="#C0D9CA" stroke-width="1.5"></line>
                            <line x1="40" y1="200" x2="380" y2="200" stroke="#C0D9CA" stroke-width="1.5"></line>

                            <!-- Grid lines -->
                            <line x1="40" y1="110" x2="380" y2="110" stroke="#C0D9CA" stroke-width="1" stroke-dasharray="3 3"></line>
                            <line x1="40" y1="160" x2="380" y2="160" stroke="#C0D9CA" stroke-width="1" stroke-dasharray="3 3"></line>

                            <!-- Curve limits labels -->
                            <text x="375" y="105" fill="#16A34A" font-size="7" font-weight="bold" text-anchor="end">WHO Median (-2 SD limit)</text>
                            <text x="375" y="155" fill="#D97706" font-size="7" font-weight="bold" text-anchor="end">Batas Pendek (-3 SD limit)</text>

                            <!-- WHO standard growth line -->
                            <path d="M 40 100 Q 150 70, 260 45 T 380 25" fill="none" stroke="#6B8C74" stroke-width="1.5" stroke-dasharray="5 5"></path>

                            <!-- Child growth curve path (Rania) -->
                            <path d="M 40 125 Q 120 115, 200 118 T 280 135 T 360 148" fill="none" stroke="#D97706" stroke-width="3" stroke-linecap="round"></path>

                            <!-- Data points -->
                            <circle cx="40" cy="125" r="4" fill="#D97706" stroke="#FFFFFF" stroke-width="1"></circle>
                            <circle cx="120" cy="115" r="4" fill="#D97706" stroke="#FFFFFF" stroke-width="1"></circle>
                            <circle cx="200" cy="118" r="4" fill="#D97706" stroke="#FFFFFF" stroke-width="1"></circle>
                            <circle cx="280" cy="135" r="4" fill="#DC2626" stroke="#FFFFFF" stroke-width="1.5"></circle>
                            <circle cx="360" cy="148" r="5" fill="#DC2626" stroke="#FFFFFF" stroke-width="2"></circle>

                            <!-- Point label (Weight Faltering alert) -->
                            <text x="360" y="136" fill="#DC2626" font-size="7" font-weight="bold" text-anchor="middle">Flat/Down (2T!)</text>

                            <!-- Axis labels -->
                            <!-- X Axis Months -->
                            <text x="40" y="212" fill="#6B8C74" font-size="7" text-anchor="middle">11 bln</text>
                            <text x="120" y="212" fill="#6B8C74" font-size="7" text-anchor="middle">12 bln</text>
                            <text x="200" y="212" fill="#6B8C74" font-size="7" text-anchor="middle">13 bln</text>
                            <text x="280" y="212" fill="#6B8C74" font-size="7" text-anchor="middle">14 bln</text>
                            <text x="360" y="212" fill="#6B8C74" font-size="7" text-anchor="middle" font-weight="bold">15 bln (Kini)</text>

                            <!-- Y Axis TB (cm) -->
                            <text x="34" y="25" fill="#6B8C74" font-size="7" text-anchor="end">85 cm</text>
                            <text x="34" y="110" fill="#6B8C74" font-size="7" text-anchor="end">78 cm</text>
                            <text x="34" y="160" fill="#6B8C74" font-size="7" text-anchor="end">75 cm</text>
                            <text x="34" y="200" fill="#6B8C74" font-size="7" text-anchor="end">70 cm</text>
                        </svg>

                        <!-- Legend -->
                        <div class="flex flex-wrap justify-center gap-4 text-[10px] text-ink-muted mt-2 border-t border-hairline-soft pt-3 w-full">
                            <div class="flex items-center gap-1">
                                <span class="h-2 w-2 rounded-full bg-risk-low"></span>
                                <span>Kurva Ideal Median WHO</span>
                            </div>
                            <div class="flex items-center gap-1">
                                <span class="h-2.5 w-2.5 rounded-full bg-risk-medium"></span>
                                <span>Tumbuh Kembang Rania</span>
                            </div>
                            <div class="flex items-center gap-1">
                                <span class="h-2 w-2 rounded bg-risk-high-surface border border-risk-high-border"></span>
                                <span>Zona Stunting (Risiko Tinggi)</span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        @endif

    </div>
</x-layouts::app>
