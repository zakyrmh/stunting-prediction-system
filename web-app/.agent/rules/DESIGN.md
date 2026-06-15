# SiPakar Stunting — DESIGN.md
## Sistem Pakar Hybrid: Deteksi Dini & Intervensi Risiko Stunting Balita

---

## Overview

SiPakar Stunting menggunakan **canvas hijau-krem** (`{colors.canvas}` #EFF7F2) sebagai
surface utama halaman — bukan putih murni, bukan abu-abu klinik. Kehangatan ini adalah
sinyal brand: sistem ini peduli pada tumbuh kembang, bukan hanya mendeteksi masalah.
Di atas canvas hijau-krem mengambang kartu-kartu putih (`{colors.surface-1}`), divider
hairline (`{colors.hairline}`), dan teks charcoal-hijau (`{colors.ink}` #1A2E22).

Tipografi utama adalah **Inter** — sans-serif geometrik open-source yang sangat terbaca
di semua ukuran layar dan semua usia pengguna, dari ibu muda hingga bidan senior. Body
menggunakan weight 400 pada ukuran minimum 15px untuk aksesibilitas pengguna dengan
literasi digital rendah. Display menggunakan weight 700 untuk hierarki yang tegas tanpa
terasa berat. Untuk tampilan data medis numerik (z-score, nilai BB/TB), digunakan
**JetBrains Mono** agar nilai-nilai terlihat presisi dan mudah dibaca sekilas.

Aksen kromatik utama adalah **Teal Sehat** (`{colors.primary}` #0B7A5C) — warna brand
yang mencerminkan kesehatan, pertumbuhan, dan kepercayaan. Digunakan pada CTA utama,
elemen navigasi aktif, dan highlight brand. Sistem ini JUGA memiliki **Palet Risiko**
khusus (Hijau/Amber/Merah) yang digunakan secara eksklusif pada komponen indikator
status stunting — bukan sebagai warna dekoratif.

Website memiliki dua area: **Area Publik** (tanpa login) untuk edukasi, informasi
stunting, dan onboarding — serta **Area Private** (perlu login) untuk input data,
deteksi risiko, grafik pertumbuhan, dan laporan tenaga medis maupun ibu balita.

**Karakteristik Utama:**
- **Canvas hijau-krem** (#EFF7F2) adalah surface anchor — bukan putih murni, bukan
  abu-abu. Kehangatan ini membedakan sistem kesehatan ini dari aplikasi klinik
  yang steril.
- Mobile-first untuk ibu balita: mayoritas pengguna ibu mengakses lewat smartphone
  Android entry-level. Semua komponen didesain dari 375px ke atas.
- **Inter** weight 700/600/400 membawa seluruh hierarki — tidak ada perubahan family.
  JetBrains Mono khusus untuk data numerik medis.
- **Teal Sehat** (#0B7A5C) adalah primary — button, navigasi aktif, aksen brand.
- **Palet Risiko** (low/medium/high) adalah sistem tersendiri — hanya untuk indikator
  status stunting, tidak pernah dipakai dekoratif.
- Corner radius sedikit lebih besar dari referensi (10–20px) untuk memberi kesan ramah
  dan modern sesuai audience perempuan dan ibu muda.
- Dua mode layout: mobile-first card stack untuk ibu, desktop sidebar+content untuk
  tenaga medis.

---

## Pengguna (User Personas)

### 1. Tenaga Medis Posyandu
- **Usia**: 25–55 tahun
- **Gender**: Mayoritas perempuan (bidan, kader, perawat)
- **Perangkat utama**: Laptop / PC di posyandu; kadang tablet
- **Literasi digital**: Menengah
- **Kebutuhan utama**: Input data batch, melihat laporan dan grafik, filter daftar
  balita, export data, tindak lanjut intervensi
- **Tone desain yang dibutuhkan**: Profesional, efisien, terorganisir. Data tampil
  jelas, aksi cepat dilakukan. Boleh menggunakan terminologi medis standar.

### 2. Ibu / Wali Balita
- **Usia**: 18–45 tahun
- **Gender**: Perempuan
- **Perangkat utama**: Smartphone Android (ukuran layar 5–6 inci, resolusi bervariasi)
- **Literasi digital**: Rendah–menengah
- **Kebutuhan utama**: Memantau status tumbuh kembang anak, memahami hasil deteksi
  dalam bahasa sederhana, mendapat tips nutrisi & intervensi, melihat jadwal posyandu
- **Tone desain yang dibutuhkan**: Hangat, empatik, visual-friendly. Hindari jargon
  medis. Selalu sertakan ikon dan penjelasan pendamping.

---

## Colors

> Sumber warna dirancang untuk konteks kesehatan ibu dan anak di Indonesia. Seluruh
> warna lolos uji kontras WCAG AA minimum.

### Brand & Accent
- **Teal Sehat** (`{colors.primary}`): #0B7A5C — Primary brand. CTA utama, navigasi
  aktif, highlight. Warna ini mencerminkan kesehatan dan pertumbuhan.
- **Teal Muda** (`{colors.primary-light}`): #E0F5EC — Soft background untuk item
  navigasi aktif, info box, dan tag aktif.
- **Putih** (`{colors.on-primary}`): #FFFFFF — Teks di atas CTA Teal dan kartu inverse.
- **Amber Edukasi** (`{colors.accent-amber}`): #D97706 — Elemen edukatif, tips nutrisi,
  highlight informatif (bukan risiko). Berbeda dari warna risiko sedang.

### Surface
- **Canvas** (`{colors.canvas}`): #EFF7F2 — Background halaman utama — hijau-krem
  hangat yang tidak steril. Bukan putih murni, bukan abu-abu.
- **Surface 1** (`{colors.surface-1}`): #FFFFFF — Kartu mengambang: profil, fitur,
  form, hasil deteksi.
- **Surface 2** (`{colors.surface-2}`): #E4F2EA — Background section alternatif, banner
  edukasi, stripe genap pada tabel data.
- **Hairline** (`{colors.hairline}`): #C0D9CA — Border 1px pada kartu dan kontainer.
- **Hairline Soft** (`{colors.hairline-soft}`): #D5EAD9 — Divider antar FAQ row,
  kolom footer, pemisah section ringan.
- **Inverse Canvas** (`{colors.inverse-canvas}`): #0B3D2E — Hijau tua dalam — strip
  testimonial, header section penting, banner penutup.
- **Inverse Surface 1** (`{colors.inverse-surface-1}`): #1A5C42 — Satu step lebih
  muda dari inverse canvas. Hover state pada elemen di area gelap.

### Text
- **Ink** (`{colors.ink}`): #1A2E22 — Semua headline, body, label button. Charcoal
  dengan hint hijau — terasa alami di atas canvas.
- **Ink Muted** (`{colors.ink-muted}`): #4A6B57 — Teks sekunder: meta info, label
  navigasi tidak aktif, sub-label form.
- **Ink Subtle** (`{colors.ink-subtle}`): #6B8C74 — Teks tersier: footer, helper
  text, placeholder keterangan.
- **Ink Tertiary** (`{colors.ink-tertiary}`): #9EB3A4 — Teks disabled, catatan kaki,
  timestamp.
- **Inverse Ink** (`{colors.inverse-ink}`): #FFFFFF — Teks di atas strip hijau tua.
- **Inverse Ink Muted** (`{colors.inverse-ink-muted}`): #B8D4C5 — Teks meta di atas
  strip gelap.

### Palet Risiko Stunting — Risk Palette
> Palet risiko HANYA digunakan pada komponen indikator status dan hasil deteksi.
> TIDAK PERNAH digunakan sebagai warna dekoratif, section background, atau CTA.

- **Risiko Rendah / Normal** (`{colors.risk-low}`): #16A34A — Status tumbuh normal,
  z-score dalam batas aman.
- **Risiko Rendah Surface** (`{colors.risk-low-surface}`): #DCFCE7 — Background badge
  dan kartu risiko rendah.
- **Risiko Rendah Border** (`{colors.risk-low-border}`): #86EFAC — Border aksen pada
  kartu dengan status normal.
- **Risiko Sedang** (`{colors.risk-medium}`): #D97706 — Status perlu perhatian,
  monitoring lebih ketat.
- **Risiko Sedang Surface** (`{colors.risk-medium-surface}`): #FEF3C7 — Background
  badge dan kartu risiko sedang.
- **Risiko Sedang Border** (`{colors.risk-medium-border}`): #FCD34D — Border aksen
  pada kartu dengan status sedang.
- **Risiko Tinggi / Stunting** (`{colors.risk-high}`): #DC2626 — Status stunting,
  perlu intervensi segera.
- **Risiko Tinggi Surface** (`{colors.risk-high-surface}`): #FEE2E2 — Background
  badge dan kartu risiko tinggi.
- **Risiko Tinggi Border** (`{colors.risk-high-border}`): #FCA5A5 — Border aksen
  pada kartu dengan status stunting.

### Semantic
- **Info** (`{colors.info}`): #0369A1 — Kotak informasi edukatif, tooltip, panduan.
- **Info Surface** (`{colors.info-surface}`): #E0F2FE — Background info box.
- **Success** (`{colors.success}`): #16A34A — Konfirmasi aksi berhasil (sama dengan
  risk-low, konteks berbeda).
- **Error** (`{colors.error}`): #DC2626 — Validasi form, error sistem.
- **Error Surface** (`{colors.error-surface}`): #FEE2E2 — Background error inline.

---

## Typography

### Font Family
- **Inter** — Sans-serif geometrik open-source. Fallback: `ui-sans-serif, system-ui,
  -apple-system`. Membawa seluruh hierarki: display, body, eyebrow, button, label.
  Sangat terbaca di layar kecil dan oleh semua usia.
- **JetBrains Mono** — Monospace open-source. Fallback: `ui-monospace, Consolas`.
  Digunakan HANYA untuk nilai data medis numerik: z-score, BB/U, TB/U, BB/TB.

Tidak ada perubahan family antar level hierarki. Hierarki dikomunikasikan melalui
ukuran + weight + letter-spacing — bukan perubahan font family.

### Hierarki

| Token | Ukuran | Weight | Line Height | Letter Spacing | Penggunaan |
|---|---|---|---|---|---|
| `{typography.display-xl}` | 52px | 700 | 1.10 | -1.2px | Hero headline halaman publik |
| `{typography.display-lg}` | 40px | 700 | 1.15 | -0.8px | Headline section utama |
| `{typography.display-md}` | 32px | 700 | 1.20 | -0.5px | Sub-section headline |
| `{typography.headline}` | 24px | 600 | 1.25 | -0.3px | Judul kartu, judul form, judul modal |
| `{typography.card-title}` | 20px | 600 | 1.30 | -0.2px | Judul kartu profil, judul hasil |
| `{typography.subhead}` | 18px | 500 | 1.45 | -0.1px | Lead body, intro paragraf section |
| `{typography.body-lg}` | 16px | 400 | 1.65 | 0 | Body hero, paragraf utama |
| `{typography.body}` | 15px | 400 | 1.65 | 0 | Body default — minimum di semua kartu |
| `{typography.body-sm}` | 13px | 400 | 1.55 | 0 | Helper text, footer, badge label |
| `{typography.caption}` | 12px | 400 | 1.45 | 0 | Caption, meta, timestamp, catatan |
| `{typography.button}` | 15px | 600 | 1.20 | 0.1px | Label button — tracking sedikit positif |
| `{typography.eyebrow}` | 12px | 600 | 1.30 | 0.6px | Section eyebrow — huruf kecil bertracking |
| `{typography.data}` | 14px | 500 | 1.50 | 0 | JetBrains Mono — nilai BB, TB, inline |
| `{typography.data-display}` | 36px | 700 | 1.15 | -0.5px | JetBrains Mono — z-score besar di kartu |
| `{typography.data-label}` | 11px | 600 | 1.30 | 0.8px | Label sumbu grafik, header tabel data |

### Prinsip Tipografi

- **Weight 700 untuk display, 600 untuk headline, 400 untuk body.** Tiga level ini
  cukup — tidak perlu weight 800 atau 300.
- **Base minimum 15px.** Lebih besar dari standar web untuk membantu ibu dengan
  keterbatasan penglihatan atau layar kecil.
- **Line height relaks pada body (1.65).** Memberi napas ekstra agar blok teks tidak
  melelahkan untuk dibaca.
- **Negative tracking hanya pada display.** Body dan button menggunakan 0 atau sedikit
  positif (+0.1px) untuk keterbacaan.
- **JetBrains Mono untuk data medis numerik saja.** Navigasi, label, body — semuanya
  Inter. Pergantian ke Mono adalah sinyal visual "ini adalah data presisi."
- **Eyebrow selalu sentence case dengan tracking positif.** Contoh yang benar:
  "profil balita". Contoh yang salah: "PROFIL BALITA".
- **Terjemahkan jargon medis untuk ibu.** Di komponen yang ditampilkan ke ibu,
  sertakan teks interpretasi plain bahasa selain nilai numerik.

---

## Layout

### Spacing System

- **Base unit**: 8px.
- **Tokens**: `{spacing.xxs}` 4px · `{spacing.xs}` 8px · `{spacing.sm}` 12px ·
  `{spacing.md}` 16px · `{spacing.lg}` 24px · `{spacing.xl}` 32px ·
  `{spacing.xxl}` 48px · `{spacing.section}` 80px.
- Padding interior kartu: `{spacing.lg}` 24px untuk kartu fitur/edukasi;
  `{spacing.xl}` 32px untuk kartu hasil deteksi dan form pengukuran;
  `{spacing.xxl}` 48px untuk banner CTA.
- Padding button: 12px vertikal · 20px horizontal. Lebih besar dari referensi
  Intercom (10×18) agar touch targets lebih nyaman di mobile.
- Gap antar field pada form: minimum `{spacing.lg}` 24px — jangan kompres form.
- Gap antar section: `{spacing.section}` 80px di desktop, 48px di mobile.

### Grid & Container

- Max content width: 1200px, center di halaman.
- **Area Publik**: grid kartu 3-up desktop, 2-up tablet, 1-up mobile.
- **Dashboard Tenaga Medis**: layout 2-kolom — sidebar 240px + main content.
- **Halaman Ibu Balita**: single-column card stack, mobile-first, max-width 600px
  di desktop untuk fokus konten.
- **Form Pengukuran**: single-column, satu grup input per baris — tidak pernah
  multi-kolom pada mobile.

### Arsitektur Halaman

**Area Publik (tanpa login):**
1. Beranda — hero, pengenalan singkat, CTA masuk/daftar, testimoni
2. Tentang Stunting — artikel edukasi, infografis, statistik
3. Cara Kerja — penjelasan sistem pakar hybrid, alur deteksi
4. Panduan Posyandu — informasi khusus tenaga medis
5. FAQ — pertanyaan umum ibu dan kader
6. Login / Registrasi

**Area Private — Ibu Balita (mobile-first):**
1. Dashboard Ibu — ringkasan status anak terbaru, jadwal posyandu berikutnya
2. Profil Anak — data lengkap, foto, riwayat pengukuran
3. Hasil Deteksi — hasil terbaru + histori, grafik pertumbuhan
4. Tips & Intervensi — rekomendasi nutrisi personal berdasarkan hasil
5. Jadwal Posyandu — kalender, reminder

**Area Private — Tenaga Medis (desktop-optimized):**
1. Dashboard Posyandu — overview semua balita, peringatan intervensi
2. Input Data Balita — form pengukuran batch
3. Daftar Balita — tabel + filter + pencarian
4. Laporan & Grafik — analitik populasi, tren risiko
5. Manajemen Pengguna — akun ibu terdaftar

### Whitespace Philosophy

Canvas hijau-krem mengerjakan apa yang dilakukan white space di brand lain. Section
dipisahkan `{spacing.section}` 80px di desktop. Kartu mengambang di atas canvas melalui
perubahan surface (canvas → kartu putih) — tanpa drop shadow tebal, tanpa border berat.
Ruang di sekitar konten bukan kemewahan — ini adalah aksesibilitas bagi pengguna
yang tidak terbiasa dengan UI padat.

---

## Elevation & Depth

| Level | Treatment | Penggunaan |
|---|---|---|
| 0 (flat) | Tanpa shadow, tanpa border | Body teks, hero heading, footer |
| 1 (lift) | `{colors.surface-1}` putih di atas canvas | Kartu fitur, edukasi, profil |
| 2 (hairline) | `{colors.surface-1}` + 1px `{colors.hairline}` border | Kartu form, kartu data yang butuh definisi tambahan |
| 3 (deep) | `{colors.inverse-canvas}` hijau tua | Strip testimonial, banner intervensi penting |
| 4 (risk-bordered) | `{colors.surface-1}` + 3px border kiri solid warna risiko | Kartu hasil deteksi dengan status risiko aktif |

SiPakar Stunting menghindari drop shadow dekoratif. Kedalaman dikomunikasikan melalui
perubahan surface (canvas → putih) dan border tipis. Pengecualian: `box-shadow` sangat
ringan diperbolehkan pada modal overlay (`0 4px 24px rgba(0,0,0,0.08)`).

---

## Shapes

### Border Radius Scale

| Token | Nilai | Penggunaan |
|---|---|---|
| `{rounded.xs}` | 4px | Chip kecil, tag inline |
| `{rounded.sm}` | 6px | Badge status risiko, label kategori |
| `{rounded.md}` | 10px | Semua button, semua form input |
| `{rounded.lg}` | 14px | Kartu fitur, kartu edukasi, FAQ row, kartu jadwal |
| `{rounded.xl}` | 20px | Kartu hasil deteksi, kartu dashboard, kartu profil anak |
| `{rounded.xxl}` | 28px | Banner CTA besar, modal sheet |
| `{rounded.pill}` | 9999px | Tab toggle Ibu/Tenaga Medis, badge risiko |
| `{rounded.full}` | 9999px | Avatar foto anak, avatar user |

**Catatan:** Corner yang sedikit lebih besar dari standar referensi (10px vs 8px
untuk button) memberikan kesan lebih ramah dan modern — konsisten dengan audience
perempuan dan ibu muda. JANGAN gunakan corner 0 (square) pada komponen interaktif.
JANGAN pill-round button utama.

---

## Components

### Buttons

**`button-primary`** — CTA Teal utama. Default primary action di semua halaman.
- Background `{colors.primary}`, teks `{colors.on-primary}`, tipografi
  `{typography.button}`, padding 12px 20px, rounded `{rounded.md}`.
- Hover: background darken 8% (#096B50). Pressed: darken 15%.
- Contoh label: "Mulai Deteksi", "Simpan Data", "Lihat Hasil", "Daftar Sekarang".

**`button-secondary`** — Button outlined Teal. CTA sekunder.
- Background `{colors.surface-1}`, teks `{colors.primary}`, tipografi
  `{typography.button}`, padding 12px 20px, rounded `{rounded.md}`.
- Border 1.5px `{colors.primary}`.
- Hover: background `{colors.primary-light}`.
- Contoh label: "Lihat Histori", "Unduh Laporan", "Kembali".

**`button-tertiary`** — Plain text action.
- Background transparan, teks `{colors.primary}`, tipografi `{typography.button}`,
  padding 12px 20px, rounded `{rounded.md}`.
- Hover: background `{colors.primary-light}`.
- Contoh label: "Lewati", "Batal", "Detail".

**`button-danger`** — Aksi destruktif / konfirmasi hapus.
- Background `{colors.risk-high}`, teks `{colors.on-primary}`, rounded `{rounded.md}`,
  padding 12px 20px.
- Hanya muncul di dalam dialog konfirmasi — tidak pernah standalone di halaman.
- Contoh label: "Hapus Data", "Batalkan Registrasi".

**`button-ghost`** — Button subtle untuk aksi tersier di tabel/dashboard.
- Background `{colors.surface-2}`, teks `{colors.ink-muted}`, tipografi
  `{typography.button}`, rounded `{rounded.md}`, padding 10px 16px.
- Contoh label: "Edit", "Arsip", "Ekspor".

### Badges & Status Indicators

**`badge-risk-low`** — Status tumbuh normal.
- Background `{colors.risk-low-surface}`, teks `{colors.risk-low}`, rounded
  `{rounded.pill}`, padding 4px 10px, tipografi `{typography.body-sm}` weight 600.
- Selalu sertakan ikon (✓ atau ikon daun/pertumbuhan).
- Label untuk ibu: "Tumbuh Normal". Label untuk tenaga medis: "Z-Score Normal".

**`badge-risk-medium`** — Status perlu perhatian.
- Background `{colors.risk-medium-surface}`, teks `{colors.risk-medium}`, rounded
  `{rounded.pill}`, padding 4px 10px, tipografi `{typography.body-sm}` weight 600.
- Selalu sertakan ikon (! atau ikon perhatian).
- Label untuk ibu: "Perlu Perhatian". Label untuk tenaga medis: "Risiko Sedang".

**`badge-risk-high`** — Status stunting / intervensi segera.
- Background `{colors.risk-high-surface}`, teks `{colors.risk-high}`, rounded
  `{rounded.pill}`, padding 4px 10px, tipografi `{typography.body-sm}` weight 600.
- Selalu sertakan ikon (⚠ atau ikon peringatan).
- Label untuk ibu: "Butuh Perhatian Dokter". Label untuk tenaga medis: "Stunting".

**`badge-info`** — Label informatif / kategori edukasi.
- Background `{colors.info-surface}`, teks `{colors.info}`, rounded `{rounded.pill}`,
  padding 4px 10px, tipografi `{typography.body-sm}` weight 600.

**`badge-role`** — Penanda peran user.
- Tenaga medis: background `{colors.primary-light}`, teks `{colors.primary}`.
- Ibu balita: background `{colors.surface-2}`, teks `{colors.ink-muted}`.
- Rounded `{rounded.sm}`, padding 3px 8px.

### Cards & Containers

**`child-profile-card`** — Kartu profil balita. Komponen entry point dashboard ibu.
- Background `{colors.surface-1}`, rounded `{rounded.xl}`, padding 24px,
  border 1px `{colors.hairline}`, elevasi 2.
- Konten: avatar foto anak (`{rounded.full}` 56px), nama anak, usia dalam bulan,
  nama ibu, tanggal pengukuran terakhir, badge risiko terakhir.
- Tap seluruh kartu → masuk ke halaman profil lengkap.

**`detection-result-card`** — Kartu hasil deteksi risiko. Komponen paling penting
  dalam sistem — perlakukan dengan penuh perhatian pada aksesibilitas dan kejelasan.
- Background `{colors.surface-1}`, border kiri 3px solid warna sesuai risk level
  (risk-low/medium/high border), rounded `{rounded.xl}`, padding 32px, elevasi 4.
- Konten atas: badge risiko + tanggal deteksi.
- Konten tengah: nilai z-score dalam `{typography.data-display}` (JetBrains Mono,
  36px, 700), indeks yang digunakan (BB/U · TB/U · BB/TB).
- Konten bawah: interpretasi plain bahasa untuk ibu ("Berat badan si kecil sedikit
  di bawah rata-rata untuk usianya"), rekomendasi langkah selanjutnya.
- Tombol: "Lihat Detail" (button-secondary), "Hubungi Posyandu" (button-primary).

**`measurement-input-card`** — Kartu form pengukuran balita. Digunakan tenaga medis.
- Background `{colors.surface-1}`, rounded `{rounded.xl}`, padding 32px,
  border 1px `{colors.hairline}`, elevasi 2.
- Section: identitas balita (dropdown nama + NIK), tanggal pengukuran (date-input),
  data antropometri (number-input BB kg, TB cm, LK cm), catatan tambahan (textarea).
- Setiap kelompok input dipisah `{spacing.lg}` 24px.
- Submit: button-primary "Simpan & Deteksi".

**`nutrition-tip-card`** — Kartu tips nutrisi dan intervensi. Ditujukan untuk ibu.
- Background `{colors.surface-2}`, teks `{colors.ink}`, rounded `{rounded.lg}`,
  padding 24px, elevasi 1.
- Konten: ikon ilustrasi 48px (makanan/ASI/sayur), eyebrow kategori, judul tip
  dalam `{typography.card-title}`, deskripsi 2 baris max, link "Baca selengkapnya".
- Gunakan bahasa konkret: "Berikan tempe 2 kali sehari" bukan "Tingkatkan asupan
  protein nabati."

**`education-card`** — Kartu artikel edukasi. Halaman publik dan area tips.
- Background `{colors.surface-1}`, rounded `{rounded.lg}`, padding 24px, elevasi 1.
- Konten: thumbnail ilustrasi (rounded-lg, 16:9), badge-info kategori, judul
  `{typography.card-title}`, excerpt 2 baris `{typography.body}`, link "Baca".

**`stat-card`** — Kartu statistik untuk dashboard tenaga medis.
- Background `{colors.surface-1}`, rounded `{rounded.lg}`, padding 24px, elevasi 2.
- Konten: eyebrow label metrik, nilai besar `{typography.display-md}` dengan warna
  sesuai konteks, sub-teks tren (↑ naik / ↓ turun vs periode lalu), tooltip (?).
- Contoh metrik: "Total Balita Terdaftar", "Risiko Stunting Bulan Ini", "Sudah
  Ditimbang", "Perlu Intervensi".

**`alert-intervention-card`** — Peringatan balita yang butuh tindak lanjut segera.
  Muncul di bagian atas dashboard tenaga medis jika ada kasus kritis.
- Background `{colors.risk-high-surface}`, border 1.5px `{colors.risk-high}`,
  rounded `{rounded.lg}`, padding 24px.
- Konten: ikon ⚠ `{colors.risk-high}`, nama balita, detail risiko, tombol
  "Tindak Lanjut" (button-danger).
- Maksimal 3 kartu tampil bersamaan — sisanya collapse ke "Lihat semua".

**`info-box`** — Kotak informasi edukatif inline di dalam form atau hasil.
- Background `{colors.info-surface}`, border kiri 3px solid `{colors.info}`,
  rounded `{rounded.md}`, padding 16px.
- Teks `{colors.ink}`, tipografi `{typography.body-sm}`.
- Gunakan untuk konteks tambahan, bukan peringatan. Contoh: "Z-score adalah
  perbandingan berat badan anak dengan standar WHO untuk usia yang sama."

**`warning-box`** — Kotak peringatan inline (bukan error form).
- Background `{colors.risk-medium-surface}`, border kiri 3px solid
  `{colors.risk-medium}`, rounded `{rounded.md}`, padding 16px.
- Digunakan untuk panduan penting yang harus diperhatikan pengguna.

**`feature-card`** — Kartu fitur di halaman publik / landing page.
- Background `{colors.surface-1}`, rounded `{rounded.lg}`, padding 24px, elevasi 1.
- Konten: ikon feature 40px `{colors.primary}`, judul `{typography.card-title}`,
  deskripsi singkat `{typography.body}`.

**`testimonial-card`** — Kutipan dari ibu atau tenaga medis.
- Background `{colors.surface-1}`, rounded `{rounded.lg}`, padding 32px, elevasi 1.
- Konten: tanda kutip besar `{colors.primary-light}`, kutipan `{typography.body-lg}`,
  avatar (`{rounded.full}` 44px), nama, peran (Ibu Balita / Bidan / Kader Posyandu).

**`schedule-card`** — Kartu jadwal posyandu.
- Background `{colors.surface-1}`, border kiri 3px solid `{colors.primary}`,
  rounded `{rounded.lg}`, padding 24px, elevasi 2.
- Konten: tanggal dengan format "Sabtu, 22 Feb 2025", lokasi posyandu, waktu,
  badge status (Terdaftar / Sudah Hadir / Tidak Hadir).
- Status "Sudah Hadir": badge-risk-low. "Tidak Hadir": badge-risk-medium.

**`growth-chart-card`** — Kartu grafik pertumbuhan balita. Komponen data utama.
- Background `{colors.surface-1}`, rounded `{rounded.xl}`, padding 24px, elevasi 2.
- Header: judul + filter waktu (3 bulan / 6 bulan / 1 tahun / semua).
- Chart: garis BB/U (`{colors.primary}`), garis TB/U (`{colors.accent-amber}`).
  Band WHO: area transparan `{colors.risk-low-surface}` (normal),
  `{colors.risk-medium-surface}` (perlu perhatian), `{colors.risk-high-surface}`
  (stunting). Grid axis: `{colors.hairline}`.
- Sumbu label: `{typography.data-label}` (Inter 11px, 600, tracking 0.8px).
- Di mobile: scroll horizontal dengan hint visual "← Geser untuk melihat →".
- Sertakan legenda di bawah grafik dengan ikon warna + label teks.

**`cta-banner`** — Banner CTA penutup section.
- Versi Primary: background `{colors.primary}`, teks putih, rounded `{rounded.xxl}`,
  padding 48px. Tombol: button yang diinvert (putih dengan border putih).
- Versi Secondary: background `{colors.surface-1}`, teks `{colors.ink}`,
  border 1px `{colors.hairline}`, rounded `{rounded.xxl}`, padding 48px.
- Tipografi judul: `{typography.headline}`. Maksimal 1 tombol utama + 1 tersier.

**`empty-state`** — State kosong saat belum ada data.
- Center di container, ilustrasi SVG sederhana `{colors.primary-light}`, judul
  `{typography.card-title}` `{colors.ink}`, deskripsi `{typography.body}`
  `{colors.ink-muted}`, satu button-primary atau button-secondary.
- Contoh: "Belum ada data pengukuran. Tambahkan data pertama si kecil."

### Navigation

**`top-nav-public`** — Navbar area publik (tanpa login).
- Background `{colors.canvas}`, border-bottom 1px `{colors.hairline}`, height 64px,
  position sticky, z-index 100.
- Kiri: logo + teks "SiPakar Stunting" `{typography.card-title}` `{colors.ink}`.
- Tengah: link navigasi `{typography.body}` `{colors.ink-muted}`, hover
  `{colors.primary}`.
- Kanan: "Masuk" (button-secondary kecil) + "Daftar" (button-primary kecil).
- Di bawah 768px: link collapse ke hamburger (☰), CTA "Masuk" tetap terlihat.

**`top-nav-private`** — Navbar area private (sudah login).
- Background `{colors.canvas}`, border-bottom 1px `{colors.hairline}`, height 64px.
- Kiri: logo. Tengah: breadcrumb / judul halaman aktif `{typography.body}`.
- Kanan: ikon notifikasi 🔔 (dengan dot merah jika ada alert) + avatar user (32px
  `{rounded.full}`) + chevron dropdown (nama + peran).

**`sidebar-nav`** — Navigasi sidebar area private di desktop.
- Background `{colors.surface-1}`, width 240px, border-right 1px `{colors.hairline}`,
  height 100vh, sticky, padding 24px 16px.
- Item navigasi: ikon 20px + label `{typography.body}`, padding 10px 12px,
  rounded `{rounded.md}`.
- Aktif: background `{colors.primary-light}`, teks `{colors.primary}`, ikon
  `{colors.primary}`.
- Tidak aktif: teks `{colors.ink-muted}`, hover background `{colors.surface-2}`.
- Tampil hanya di ≥ 1024px. Di bawahnya digantikan bottom-tab-bar.
- Group navigasi dipisah dengan label eyebrow `{colors.ink-subtle}`.

**`bottom-tab-bar`** — Tab bar bawah untuk mobile di area private.
- Background `{colors.surface-1}`, border-top 1px `{colors.hairline}`, height 64px,
  position fixed bottom 0, z-index 100.
- Untuk ibu: 5 tab — Beranda, Profil Anak, Deteksi, Tips, Saya.
- Untuk tenaga medis: 4 tab — Dashboard, Input Data, Daftar, Laporan.
- Tab aktif: ikon + label `{colors.primary}` `{typography.caption}` weight 600.
- Tidak aktif: `{colors.ink-subtle}`.
- Ikon minimum 24px. Touch target minimum 56px height per tab.

**`role-toggle`** — Toggle peran di halaman login/registrasi.
- Pill toggle `{rounded.pill}`, background container `{colors.surface-2}`, padding
  4px.
- Opsi: "Saya Ibu" dan "Tenaga Medis".
- Default: `{colors.surface-2}` background, `{colors.ink-muted}` teks.
- Selected: `{colors.surface-1}` background, `{colors.ink}` teks weight 600.
- Ukuran tap target minimum 44px height.

### Forms & Inputs

> Semua input menggunakan label di ATAS field — bukan hanya placeholder. Placeholder
> hanya sebagai contoh format, bukan sebagai label.

**`text-input`** — Field teks standar.
- Background `{colors.surface-1}`, border 1px `{colors.hairline}`, rounded
  `{rounded.md}`, padding 12px 14px, minimum height 48px.
- Label: `{typography.body}` weight 600 `{colors.ink}`, margin-bottom 6px.
- Placeholder: `{colors.ink-tertiary}`.
- Focused: border 2px `{colors.primary}`, outline none.
- Contoh: "Nama lengkap anak", "Nama ibu / wali".

**`text-input-error`** — Field dengan validasi error.
- Border 2px `{colors.error}`.
- Pesan error di bawah field: ikon ⚠ + teks `{colors.error}` `{typography.caption}`.
- Pesan error selalu eksplisit: "Berat badan tidak boleh kurang dari 1 kg."

**`number-input`** — Input angka untuk data antropometri.
- Style identik `text-input` + unit label di kanan (kg / cm) dalam kotak
  `{colors.surface-2}`, rounded kanan `{rounded.md}`.
- Validasi range otomatis: jika nilai di luar batas wajar, tampilkan warning-box
  inline "Nilai ini tidak biasa untuk usia balita. Pastikan kembali."
- Contoh: Berat Badan (kg), Tinggi Badan (cm), Lingkar Kepala (cm).

**`date-input`** — Input tanggal lahir atau tanggal pengukuran.
- Style `text-input` + ikon kalender di kanan. Klik ikon → buka date picker.
- Pada mobile: gunakan native date picker (`<input type="date">`).
- Format tampilan: DD/MM/YYYY (sesuai kebiasaan Indonesia).

**`select-input`** — Dropdown pilihan.
- Style identik `text-input` + custom chevron ▾ di kanan `{colors.ink-muted}`.
- Focused: border 2px `{colors.primary}`.
- Digunakan untuk: jenis kelamin, nama posyandu, kecamatan.

**`radio-card`** — Pilihan kartu radio — lebih mudah tap daripada radio button default.
- Background `{colors.canvas}`, border 1.5px `{colors.hairline}`, rounded `{rounded.lg}`,
  padding 16px 20px, minimum height 56px, display flex align-center, gap 12px.
- Ikon 24px di kiri + teks label `{typography.body}` weight 500.
- Selected: border 2px `{colors.primary}`, background `{colors.primary-light}`,
  ikon berubah ke warna `{colors.primary}`.
- Digunakan untuk: jenis kelamin (Laki-laki / Perempuan), pilihan peran.

**`checkbox-item`** — Pilihan checkbox untuk multi-select.
- Custom checkbox 20×20px rounded `{rounded.sm}`, border 1.5px `{colors.hairline}`.
- Checked: background `{colors.primary}`, checkmark putih.
- Label: `{typography.body}` `{colors.ink}`, padding-left 8px.
- Digunakan untuk: gejala tambahan, kondisi yang diamati.

**`textarea`** — Area teks multi-baris.
- Style identik `text-input`, minimum height 96px, resize vertical.
- Digunakan untuk catatan bidan, catatan intervensi, deskripsi kondisi.

**`file-upload`** — Upload foto anak (opsional).
- Area drop zone, border dashed 2px `{colors.hairline}`, rounded `{rounded.lg}`,
  padding 32px, text-center.
- Background `{colors.canvas}`. Hover: background `{colors.primary-light}`.
- Isi: ikon upload `{colors.primary}`, teks "Ketuk untuk pilih foto" (mobile) /
  "Seret foto ke sini" (desktop), sub-teks "Opsional · JPG, PNG · maks 5MB".

**`form-section-header`** — Pemisah antar kelompok input dalam form panjang.
- Teks `{typography.headline}` `{colors.ink}`, border-top 1px `{colors.hairline}`,
  padding-top 24px, margin-top 32px.
- Sertakan nomor urut jika form memiliki langkah (1 dari 3).

**`form-step-indicator`** — Indikator langkah pada form multi-step (wizard).
- Bar horisontal di atas form. Step selesai: `{colors.primary}`. Step aktif:
  `{colors.primary}` dengan label. Step mendatang: `{colors.hairline}`.
- Teks label step: `{typography.caption}` weight 600.

### Auth Components

**`auth-card`** — Container halaman login dan registrasi.
- Background `{colors.surface-1}`, rounded `{rounded.xxl}`, padding 40px,
  max-width 480px, center vertikal dan horisontal di halaman.
- Border 1px `{colors.hairline}`.
- Di mobile: full-width dengan padding 24px, border-radius hanya atas.

**`auth-divider`** — Divider "atau" antara form dan login alternatif.
- Garis `{colors.hairline}` kiri-kanan, teks "atau" center `{colors.ink-subtle}`
  `{typography.body-sm}`.

### Data Display

**`zscore-display`** — Komponen tampilan z-score besar di kartu hasil deteksi.
- Font JetBrains Mono, `{typography.data-display}` (36px, 700).
- Warna berdasarkan nilai: ≥ -2 SD → `{colors.risk-low}`. Antara -3 dan -2 SD →
  `{colors.risk-medium}`. < -3 SD → `{colors.risk-high}`.
- Selalu sertakan label di bawah: "BB/U" / "TB/U" / "BB/TB" dalam
  `{typography.data-label}` `{colors.ink-muted}`.

**`data-table`** — Tabel data pengukuran historis (dashboard tenaga medis).
- Header: background `{colors.surface-2}`, teks `{colors.ink}` `{typography.body-sm}`
  weight 600, border-bottom 1px `{colors.hairline}`.
- Row: alternating `{colors.surface-1}` dan `{colors.canvas}`, border-bottom 1px
  `{colors.hairline-soft}`, padding 12px 16px.
- Kolom status: badge-risk inline.
- Aksi per baris: button-ghost "Detail" + "Edit".
- Di mobile: collapse ke card list atau enable horizontal scroll dengan overflow-x.

**`pagination`** — Kontrol pagination untuk tabel data.
- Tombol halaman: style button-ghost, aktif: button-primary kecil.
- Tampilkan: "Menampilkan 1–20 dari 87 balita".
- Di mobile: cukup "< Sebelumnya" dan "Berikutnya >".

### FAQ

**`faq-row`** — Baris FAQ accordion.
- Background `{colors.canvas}`, teks `{colors.ink}`, rounded `{rounded.md}`,
  padding 20px 24px, border-bottom 1px `{colors.hairline-soft}`.
- Ikon chevron ▾ di kanan, rotate 180° saat expanded dengan transisi 200ms.
- Pertanyaan: `{typography.body}` weight 600. Jawaban: `{typography.body}` weight 400,
  padding-top 12px, `{colors.ink-muted}`.

### Footer

**`footer`** — Footer link grid.
- Background `{colors.canvas}`, border-top 1px `{colors.hairline}`, padding 64px 32px.
- Kiri: logo + tagline singkat `{typography.body-sm}` `{colors.ink-subtle}`.
- Tengah: grid link 4 kolom (Tentang / Fitur / Panduan / Bantuan) `{typography.caption}`
  `{colors.ink-subtle}`.
- Bawah: disclaimer privasi data kesehatan, copyright, link kebijakan privasi.
- Teks disclaimer: `{typography.caption}` `{colors.ink-tertiary}`. Penting karena
  sistem menangani data kesehatan anak.

---

## Do's and Don'ts

### Do

- Gunakan `{colors.canvas}` hijau-krem #EFF7F2 sebagai surface anchor — jangan
  ganti dengan putih murni atau abu-abu.
- Angkat kartu di atas canvas ke `{colors.surface-1}` putih untuk hierarki visual.
- Gunakan **palet risiko** (risk-low/medium/high) HANYA pada komponen indikator
  status stunting dan hasil deteksi — tidak pernah dekoratif.
- Tampilkan label teks DI ATAS setiap input field — bukan hanya placeholder.
- Gunakan minimum **15px** untuk body type — jangan lebih kecil demi aksesibilitas.
- Sediakan touch target minimum **44px height** untuk semua komponen interaktif.
- Sertakan ikon visual PENDAMPING teks pada komponen untuk ibu balita.
- Gunakan JetBrains Mono HANYA untuk nilai data medis numerik (z-score, BB, TB).
- Terjemahkan hasil numerik ke bahasa sederhana untuk ibu — selalu dua versi.
- Gunakan `{rounded.md}` 10px untuk button dan input, `{rounded.xl}` untuk kartu utama.
- Pisahkan kelompok input dengan `{spacing.lg}` 24px minimum — jangan kompres form.
- Sertakan disclaimer privasi data di footer dan di dekat form pengukuran.
- Validasi input dengan pesan error eksplisit dan panduan koreksi.

### Don't

- Jangan gunakan putih murni (#FFFFFF) sebagai canvas halaman — hanya untuk kartu.
- Jangan gunakan warna risiko (merah/amber) sebagai warna section, dekorasi, atau CTA.
- Jangan tampilkan nilai z-score tanpa interpretasi plain bahasa untuk ibu.
- Jangan tambah drop shadow berat pada kartu — gunakan border hairline.
- Jangan perkenalkan font family kedua di marketing chrome (Inter saja).
- Jangan pill-round button utama — `{rounded.md}` 10px untuk button.
- Jangan gunakan all-caps pada label atau eyebrow.
- Jangan kompres form pengukuran — satu kelompok input per baris di mobile.
- Jangan tampilkan lebih dari 3 aksi button dalam satu kartu.
- Jangan gunakan `{colors.risk-high}` merah sebagai background penuh pada elemen besar.
- Jangan tampilkan data kesehatan anak tanpa konteks usia dan standar acuan (WHO).
- Jangan gabungkan `button-primary` dan `button-danger` dalam satu viewport tanpa
  hierarki jelas dan jarak yang cukup.
- Jangan sembunyikan badge risiko di belakang interaksi — status harus terlihat
  langsung tanpa tap/hover.

---

## Responsive Behavior

### Breakpoints

| Nama | Lebar | Perubahan Utama |
|---|---|---|
| Desktop-XL | 1440px | Layout default desktop |
| Desktop | 1280px | Sidebar + main content 2-kolom |
| Tablet | 1024px | Sidebar collapse; grid 2-up |
| Mobile-Lg | 768px | Bottom tab bar aktif; single column penuh |
| Mobile | 480px | Satu kolom; display-xl 52px → 28px |
| Mobile-SM | 375px | Baseline desain untuk ibu balita |

### Mobile-First untuk Ibu Balita

Desain dimulai dari 375px (ukuran paling umum smartphone entry-level Indonesia)
lalu di-enhance untuk layar yang lebih besar. Hierarki keputusan:

1. Tampilkan konten paling penting terlebih dahulu di atas fold.
2. Satu aksi utama per halaman — jangan beri ibu terlalu banyak pilihan.
3. Form pengukuran: satu pertanyaan / kelompok per scroll — tidak pernah dua kolom.
4. Grafik pertumbuhan: scroll horizontal dengan hint gesture yang jelas.
5. Gunakan native input (`type="date"`, `type="number"`, `inputmode="numeric"`) untuk
   trigger keyboard yang tepat.
6. Kartu hasil deteksi: full-width di mobile dengan padding 20px.

### Touch Targets

- Semua button: minimum 44px height.
- Bottom tab bar: minimum 56px height total.
- Input fields: minimum 48px height.
- Radio cards: minimum 56px height.
- Baris tabel di mobile (card mode): minimum 64px height.
- Link footer: minimum 36px height.
- Ikon-only actions: minimum 40×40px area tap.

### Collapsing Strategy

- **Top nav**: link navigasi collapse ke hamburger (☰) di bawah 768px. CTA "Masuk"
  tetap terlihat di kanan.
- **Sidebar**: collapse ke bottom-tab-bar di bawah 1024px.
- **Card grids**: 3-up → 2-up (1024px) → 1-up (768px).
- **Data table**: horizontal scroll atau card-list mode di bawah 768px.
- **Growth chart**: horizontal scroll dengan overflow-x di bawah 768px.
- **Display type**: 52px → 40px (tablet) → 28px (mobile).
- **Auth card**: rounded-bottom menjadi 0, full-bleed dari bawah di mobile.

### Layout per Persona

- **Ibu Balita di mobile**: bottom-tab-bar, card stack vertikal, single-column form,
  visual-first (ikon besar, ilustrasi, warna status jelas).
- **Tenaga Medis di desktop**: sidebar-nav, data table, form multi-field, dashboard
  statistik, aksi batch.
- Satu codebase, dua mode layout — dibedakan melalui role dari auth context dan
  media queries. Jangan buat dua website terpisah.

---

## Aksesibilitas

### Kontras Warna (WCAG AA)

- `{colors.ink}` #1A2E22 di atas `{colors.canvas}` #EFF7F2: rasio ≥ 9:1 ✓
- `{colors.primary}` #0B7A5C di atas `{colors.surface-1}` #FFFFFF: rasio ≥ 4.5:1 ✓
- `{colors.on-primary}` #FFFFFF di atas `{colors.primary}` #0B7A5C: rasio ≥ 4.5:1 ✓
- `{colors.risk-high}` #DC2626 di atas `{colors.risk-high-surface}` #FEE2E2:
  gunakan weight 600+ agar memenuhi kontras 3:1 untuk large text ✓
- `{colors.risk-medium}` #D97706 di atas `{colors.surface-1}` #FFFFFF:
  gunakan weight 600+ ✓

### Prinsip Aksesibilitas

- Semua indikator status risiko HARUS menggunakan ikon + warna + teks — tidak boleh
  hanya mengandalkan warna saja (untuk pengguna buta warna).
- Semua form field HARUS memiliki `<label>` yang terhubung dengan atribut `for`/`id`.
- Error state menggunakan tiga sinyal: ikon ⚠ + border merah + teks penjelasan.
- Fokus indicator: outline 2px solid `{colors.primary}` dengan offset 2px pada SEMUA
  elemen interaktif. Jangan pernah `outline: none` tanpa pengganti.
- Gambar ilustrasi non-dekoratif harus memiliki atribut `alt` yang deskriptif.
- Grafik pertumbuhan harus memiliki teks deskripsi alternatif dan tabel data
  tersembunyi yang dapat dibaca screen reader.
- Respek `prefers-reduced-motion`: semua animasi transisi harus memiliki fallback
  tanpa animasi.
- Bahasa HTML harus `lang="id"` untuk semua halaman.

---

## Tone of Voice

### Untuk Ibu Balita
- **Hangat, empatik, menyemangati.** Sistem ini adalah teman yang membantu, bukan
  mesin yang menghakimi.
- Gunakan "si kecil" bukan "anak subjek", "balita" bukan "pasien".
- Hindari jargon medis. Jika harus menggunakan, selalu sertakan penjelasan.
- Setiap hasil deteksi diakhiri dengan langkah konkret yang bisa diambil sekarang.
- Gunakan kata ganti "Kamu" untuk komunikasi langsung, bukan "Ibu" (terasa formal
  dan kaku di konteks digital).
- Contoh ✓: "Berat badan si kecil sedikit di bawah rata-rata anak seusianya.
  Ini bisa diperbaiki dengan menambah porsi makan bergizi."
- Contoh ✗: "Z-score BB/U pasien adalah -2.3 SD, mengindikasikan risiko
  gizi kurang tingkat sedang."

### Untuk Tenaga Medis
- **Profesional, ringkas, presisi.** Data langsung, aksi jelas.
- Boleh menggunakan terminologi standar: Z-score, BB/U, TB/U, BB/TB, WAZ, LAZ, WLZ,
  SD, persentil.
- Prioritaskan efisiensi — data dapat diakses dalam 2 klik, form dapat diisi dalam
  satu sesi tanpa scroll berlebihan.
- Pesan error sistem menggunakan bahasa teknis yang tepat untuk pelaporan.
- Tombol aksi menggunakan kata kerja aktif: "Simpan Data", "Ekspor PDF", "Tandai
  Intervensi", "Arsipkan".

### Pesan Error & Empty State
- Error bukan momen untuk meminta maaf — ini momen untuk memberi panduan.
- Format: [Apa yang terjadi] + [Cara memperbaiki].
- Contoh ✓: "Berat badan harus diisi. Masukkan nilai dalam kilogram (kg)."
- Contoh ✗: "Error 422: Field validation failed."
- Empty state adalah undangan untuk bertindak, bukan kegagalan.
- Contoh ✓: "Belum ada data pengukuran. Tambahkan data pertama si kecil."

---

## Known Gaps

- **Dark mode** tidak didokumentasikan — sistem tidak mengimplementasikan dark theme
  pada versi awal. Tambahkan di iterasi ke-2 jika ada permintaan pengguna.
- **Micro-interactions dan animasi** belum didefinisikan sepenuhnya. Prinsip awal:
  transisi state 150–200ms ease-out, respek prefers-reduced-motion.
- **Growth chart** menggunakan library eksternal (Recharts atau Chart.js) — semua
  warna dan typography chart harus di-override menggunakan design tokens di atas.
  Jangan menggunakan warna default library.
- **Form multi-step / wizard** untuk onboarding ibu baru belum didefinisikan sebagai
  komponen terpisah — gunakan `form-step-indicator` dan `form-section-header` sebagai
  dasar.
- **Notifikasi push** untuk pengingat jadwal posyandu belum didefinisikan dalam sistem
  desain ini — dokumen terpisah diperlukan untuk pola notifikasi.
- **Offline mode** belum didefinisikan — penting untuk posyandu di daerah dengan
  koneksi tidak stabil. Pertimbangkan indikator status koneksi dan antrian sinkronisasi.
- **Print stylesheet** untuk laporan PDF belum didefinisikan. Tenaga medis mungkin
  perlu mencetak laporan. Tambahkan di iterasi berikutnya.
- **Inter** dan **JetBrains Mono** tersedia bebas (Google Fonts / open source) —
  tidak ada ketergantungan font proprietary.
- Komponen **notifikasi in-app** (toast, alert banner) belum didefinisikan secara
  lengkap — gunakan `info-box` dan `warning-box` sebagai pengganti sementara.
- **Internasionalisasi** (i18n) untuk bahasa daerah belum dipertimbangkan — tambahkan
  di roadmap jika sistem berkembang ke luar Jawa.
