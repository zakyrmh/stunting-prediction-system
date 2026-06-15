## 🔝 1. Isi Header (Navbar)

*Header* harus tetap bersih, melayang saat di-scroll (*sticky*), dan memiliki kontras yang jelas agar pengguna (terutama Ibu balita atau kader yang mengakses lewat HP) tidak kebingungan.

### **Komponen Kiri: Logo & Identitas Aplikasi**

* **Logo Simbol:** Ikon geometri sederhana yang melambangkan pertumbuhan anak, kesehatan, atau kombinasi huruf **S** (SiPakar).
* **Nama Aplikasi (Branding):** Sediakan nama proyek yang *catchy*, misalnya **"StuntingGuard"**, **"NutriGrow"**, atau **"Si-Stunting"**.
* **Sub-Teks (Opsional):** Beri label kecil bertuliskan *"Hybrid AI System"* di bawah nama aplikasi untuk langsung menarik perhatian dosen.

### **Komponen Tengah: Navigasi Utama (Link)**

Sesuai dengan kesepakatan pembagian halaman sebelumnya, menu di tengah dibuat sangat ringkas:

* **Home (`/`):** Mengarah ke halaman utama *Landing Page*.
* **Edukasi Gizi (`/edukasi`):** Mengarah ke halaman literasi standar medis Kemenkes/WHO.

### **Komponen Kanan: Call to Action (CTA) Button**

Alih-alih hanya tulisan "Login" biasa, gunakan komponen *button* yang tegas untuk membedakan tamu umum dengan pengguna sistem.

* **Tombol "Masuk ke Dashboard":** * *Kondisi Belum Login:* Diarahkan ke halaman `/login` dengan visual tombol solid (misal warna *emerald green* atau *sky blue* Tailwind).
* *Kondisi Sudah Login (Menggunakan logika `@auth` Laravel):* Teks otomatis berubah menjadi *"Buka Dashboard"* atau menampilkan nama user yang sedang aktif untuk efisiensi akses.



---

## 🔚 2. Isi Footer (Kaki Halaman)

*Footer* adalah tempat terbaik untuk meletakkan kredibilitas ilmiah proyek akhirmu. Ketika dosen melakukan *scroll* sampai ke bagian paling bawah, mereka harus melihat bahwa aplikasi ini dibangun dengan metodologi rekayasa yang matang.

Bagi *Footer* menjadi **4 Kolom Utama** menggunakan *grid* Tailwind (`grid grid-cols-1 md:grid-cols-4 gap-8`):

### **Kolom 1: Tentang Aplikasi (Branding & Singkat)**

* Ulangi logo dan nama aplikasi dalam versi *light* (jika *background footer* gelap).
* **Deskripsi Singkat:** *"Sistem informasi cerdas berbasis Hybrid AI untuk mendeteksi dini risiko stunting secara runtun waktu dan memberikan rekomendasi intervensi gizi tepercaya bagi masyarakat."*

### **Kolom 2: Sumber & Validitas Medis (Kredibilitas)**

Tuliskan teks atau pasang logo kecil institusi kesehatan yang menjadi landasan utama pembuatan *Knowledge Base* (Tabel Aturan) sistem pakarmu:

* Kementerian Kesehatan RI (Permenkes No. 2 Tahun 2020)
* Ikatan Dokter Anak Indonesia (IDAI)
* World Health Organization (WHO)

### **Kolom 3: Navigasi Cepat (Quick Links)**

* Beranda Aplikasi
* Modul Edukasi Gizi & Stunting
* Portal Login Petugas
* *Link* Dokumen Buku Saku Kemenkes (Tautan Eksternal)

### **Kolom 4: Pengembang Sistem (SiPakar Team)**

Tampilkan identitas kelompok kamu sebagai bentuk penegasan hak cipta karya Tugas Akhir:

* **SiPakar Team**
* Politeknik Negeri Padang (PNP)
* D4 Teknologi Rekayasa Perangkat Lunak (TRPL)

### **Baris Paling Bawah (Bottom Bar / Copyright)**

* Garis pembatas horizontal tipis (`border-t border-gray-700`).
* **Teks Hak Cipta:** *“© 2026 SiPakar Team - D4 Teknologi Rekayasa Perangkat Lunak Politeknik Negeri Padang. All Rights Reserved.”*
