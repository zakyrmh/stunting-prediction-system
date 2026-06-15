<footer class="w-full bg-canvas border-t border-hairline pt-16 pb-8 px-6 md:px-12 mt-auto">
    <!-- Grid 4 Kolom -->
    <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-4 gap-8 mb-12">
        
        <!-- Kolom 1: Tentang Aplikasi -->
        <div class="flex flex-col gap-4">
            <div class="flex items-center gap-3">
                <div class="p-1.5 bg-primary-light rounded-md text-primary-teal">
                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                        <path d="M12 9v7"/>
                        <path d="M8 12v4"/>
                        <path d="M16 11v5"/>
                    </svg>
                </div>
                <span class="text-card-title text-ink font-bold leading-tight">
                    SiPakar Stunting
                </span>
            </div>
            <p class="text-body-sm text-ink-muted leading-relaxed">
                Sistem informasi cerdas berbasis Hybrid AI untuk mendeteksi dini risiko stunting secara runtun waktu dan memberikan rekomendasi intervensi gizi tepercaya bagi masyarakat.
            </p>
        </div>

        <!-- Kolom 2: Sumber & Validitas Medis -->
        <div class="flex flex-col gap-4">
            <h4 class="text-body-default font-semibold text-ink">Sumber & Validitas</h4>
            <div class="flex flex-col gap-3">
                <div class="flex items-start gap-2 text-body-sm text-ink-muted">
                    <svg class="h-5 w-5 text-primary-teal shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                    <span>Kementerian Kesehatan RI<br><span class="text-[11px] text-ink-subtle">(Permenkes No. 2 Tahun 2020)</span></span>
                </div>
                <div class="flex items-start gap-2 text-body-sm text-ink-muted">
                    <svg class="h-5 w-5 text-primary-teal shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                    <span>Ikatan Dokter Anak Indonesia (IDAI)</span>
                </div>
                <div class="flex items-start gap-2 text-body-sm text-ink-muted">
                    <svg class="h-5 w-5 text-primary-teal shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                    <span>World Health Organization (WHO)</span>
                </div>
            </div>
        </div>

        <!-- Kolom 3: Navigasi Cepat -->
        <div class="flex flex-col gap-4">
            <h4 class="text-body-default font-semibold text-ink">Navigasi Cepat</h4>
            <ul class="flex flex-col gap-2.5 text-body-sm text-ink-muted">
                <li>
                    <a href="{{ route('home') }}" class="hover:text-primary-teal transition-colors">Beranda Aplikasi</a>
                </li>
                <li>
                    <a href="{{ route('edukasi') ?? '#edukasi' }}" class="hover:text-primary-teal transition-colors">Modul Edukasi Gizi & Stunting</a>
                </li>
                <li>
                    <a href="{{ route('login') }}" class="hover:text-primary-teal transition-colors">Portal Login Petugas</a>
                </li>
                <li>
                    <a href="https://kesmas.kemkes.go.id/assets/uploads/contents/attachments/d2e1c3132cf0bc2a832f05c48b29dbcd.pdf" 
                       target="_blank" rel="noopener noreferrer" 
                       class="inline-flex items-center gap-1 hover:text-primary-teal transition-colors">
                        <span>Buku Saku Kemenkes</span>
                        <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                        </svg>
                    </a>
                </li>
            </ul>
        </div>

        <!-- Kolom 4: Pengembang Sistem -->
        <div class="flex flex-col gap-4">
            <h4 class="text-body-default font-semibold text-ink">Pengembang Sistem</h4>
            <div class="flex flex-col gap-1 text-body-sm text-ink-muted">
                <span class="font-bold text-ink">SiPakar Team</span>
                <span>Politeknik Negeri Padang (PNP)</span>
                <span>D4 Teknologi Rekayasa Perangkat Lunak (TRPL)</span>
            </div>
        </div>

    </div>

    <!-- Baris Paling Bawah: Bottom Bar -->
    <div class="max-w-7xl mx-auto border-t border-hairline-soft pt-6 flex flex-col md:flex-row items-center justify-between gap-4">
        <!-- Copyright -->
        <span class="text-caption text-ink-subtle text-center md:text-left">
            &copy; 2026 SiPakar Team - D4 Teknologi Rekayasa Perangkat Lunak Politeknik Negeri Padang. All Rights Reserved.
        </span>
        <!-- Privacy / Medical Disclaimer -->
        <div class="flex items-center gap-4 text-caption text-ink-subtle">
            <span class="text-[11px] leading-relaxed max-w-xs text-center md:text-right italic">
                *Disclaimer: Sistem ini adalah alat deteksi dini (skrining) dan bukan pengganti diagnosis klinis dokter spesialis anak.
            </span>
        </div>
    </div>
</footer>
