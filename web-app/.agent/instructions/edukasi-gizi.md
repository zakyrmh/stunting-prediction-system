## 🧭 Struktur & Konten Detail Halaman Edukasi Gizi

Aplikasi Laravel 13 kamu sebaiknya membagi halaman edukasi ini ke dalam **4 sub-bagian utama (Grid/Sections)** yang scannable dan dilengkapi dengan navigasi jangkar (*anchor links*) agar pengguna mudah melompat ke topik yang diinginkan.

### 1. Edukasi Fundamental: Apa itu Stunting & Mengapa Terjadi?

Bagian ini memberikan pemahaman dasar komprehensif mengenai stunting kepada masyarakat umum atau kader.

* **Konten Detail yang Harus Ada:** 

  * **Definisi Medis:** Stunting adalah gangguan pertumbuhan dan perkembangan anak akibat kekurangan gizi kronis dan infeksi berulang, yang ditandai dengan panjang atau tinggi badannya berada di bawah standar yang ditetapkan.
  * **Ambang Batas Klinis (Z-Score):** Berdasarkan Permenkes No. 2 Tahun 2020, pastikan untuk menegaskan bahwa kriteria Pendek (Stunted) adalah Panjang/Tinggi Badan menurut Umur (PB/U atau TB/U) berada di rentang -3 SD sampai < -2 SD, dan Sangat Pendek (Severely Stunted) adalah < -3 SD (Permenkes Nomor 2 Tahun 2020).
  * **Penyebab Multi-Dimensi:** Tambahkan secara spesifik bahwa penyebab tidak langsung juga mencakup "Ketahanan Pangan Rumah Tangga" yang tidak memadai, di samping pola asuh dan sanitasi, karena hal ini adalah pondasi utama menurut kerangka kerja Kemenkes (PEDOMAN PENCEGAHAN DAN TATALAKSANA GIZI BURUK PADA BALITA).


* **Faktor Penyebab Utama (Multi-Dimensi):**

  * *Penyebab Langsung:* Asupan gizi yang tidak adekuat dan penyakit infeksi berulang.
  * *Penyebab Tidak Langsung:* Pola asuh yang kurang tepat (tidak ASI eksklusif), keterbatasan akses pelayanan kesehatan, serta buruknya air bersih dan sanitasi lingkungan.


### 2. Deteksi Dini: Memahami *Growth Faltering* (Gagal Tumbuh)

Bagian ini mengedukasi pengguna mengenai logika *time-series* yang diadopsi oleh sistem Laravel kamu. Ini adalah jembatan edukasi sebelum user menggunakan form log kunjungan bulanan.

* **Konten Detail yang Harus Ada:**

    * **Konsep *Weight Faltering*:** Menjelaskan bahwa sebelum anak jatuh ke status stunting (tinggi badan pendek), indikator awal yang paling sensitif adalah berat badan yang tidak naik sesuai grafik pertumbuhan (*weight faltering*).
    * **Definisi Faltering**: Perjelas bahwa growth faltering (hambatan pertumbuhan) ditandai oleh 3 kondisi berat badan: 1) naik, tapi tidak optimal; 2) tidak naik (tetap); atau 3) turun (PEDOMAN PENCEGAHAN DAN TATALAKSANA GIZI BURUK PADA BALITA).
    * **Logika "2T" Kemenkes:** Berikan ilustrasi visual mengenai aturan **2T (Dua kali Tidak naik berat badannya)**. Secara klinis, jika garis pertumbuhan anak terus mendatar (tidak ada kenaikan berat badan) atau memotong salah satu garis Z-score ke arah bawah, anak tersebut berisiko mengalami gagal tumbuh (at risk of failure to thrive) (Permenkes Nomor 2 Tahun 2020). Jika tren ini terjadi (Tidak Naik) dan tidak mengikuti garis pertumbuhan normal, anak wajib segera dikonfirmasi oleh petugas kesehatan karena ini adalah "Lampu Kuning" menuju gizi kurang atau stunting (Permenkes Nomor 2 Tahun 2020).


### 3. Protokol Kesehatan & Gizi: Panduan Berdasarkan Kelompok Risiko

Bagian ini memuat substansi dari aturan **R04 hingga R09** pada sistem pakarmu. Kamu memetakan apa saja tindakan yang direkomendasikan secara resmi oleh institusi kesehatan.

* **Konten Detail yang Harus Ada:**
    * **Kelompok Risiko 1: Masalah Makan & GTM (Gerakan Tutup Mulut)**
        * *Edukasi:* IDAI menekankan pentingnya menilai fungsi oromotor (kemampuan makan anak) dan menerapkan Asuhan Nutrisi Pediatrik yang disiplin (Rekomendasi-IDAI_Asuhan-Nutrisi-Pediatrik). Kementerian Kesehatan juga menyoroti bahwa kegagalan kenaikan berat badan sering terjadi karena pengasuh "tidak mempraktekkan pemberian makan yang responsif" (responsive feeding) (PEDOMAN PENCEGAHAN DAN TATALAKSANA GIZI BURUK PADA BALITA). Catatan: Aturan spesifik "maksimal 30 menit" adalah pedoman umum IDAI di luar dokumen ini, namun secara prinsip asuhan nutrisi (termasuk jadwal dan toleransi makan) sangat didukung oleh literatur IDAI (Rekomendasi-IDAI_Asuhan-Nutrisi-Pediatrik).
    * **Kelompok Risiko 2: Dampak Riwayat Lahir (BBLR & Prematurnitas)**
        * *Edukasi:* Konsep ini sangat tepat. Bayi lahir sebelum waktunya (< 37 minggu) atau BBLR (< 2500 gram) organ-organ tubuhnya belum berfungsi sepenuhnya, yang memicu risiko kegagalan fungsi tubuh (PEDOMAN PENCEGAHAN DAN TATALAKSANA GIZI BURUK PADA BALITA). Oleh karena itu, siklus "tumbuh kejar" (catch-up growth) yang memadai sangat krusial pada masa ini (PEDOMAN PENCEGAHAN DAN TATALAKSANA GIZI BURUK PADA BALITA).
    * **Kelompok Risiko 3: Siklus Infeksi & Sanitasi Lingkungan**
        * *Edukasi:* Konsep Environmental Enteropathy sangat didukung oleh WHO. Paparan lingkungan yang terkontaminasi dan kebersihan yang buruk menyebabkan infeksi subklinis. Hal ini secara langsung merusak fungsi usus sebagai pelindung terhadap organisme penyebab penyakit, sehingga menyebabkan malabsorpsi nutrisi (kegagalan penyerapan gizi) yang berujung pada stunting (WHO_NMH_NHD_14.3_eng).


### 4. Modul Intervensi: Pedoman Pemberian Makanan Tambahan (PMT) Lokal

Bagian penutup halaman edukasi yang menyajikan panduan praktis menu makanan yang kaya akan zat gizi makro dan mikro untuk memulihkan kondisi anak.

* **Konten Detail yang Harus Ada:**
    * **Fokus Protein Hewani:** Menyajikan data ilmiah bahwa pencegahan stunting paling efektif adalah lewat asupan asam amino esensial lengkap yang ada pada protein hewani (seperti telur, hati ayam, daging sapi, dan ikan lokal), bukan gizi nabati. WHO secara eksplisit menegaskan bahwa "makanan bersumber hewani adalah sumber terbaik untuk nutrisi berkualitas tinggi" (Animal-source foods are the best sources of high-quality nutrients) (WHO_NMH_NHD_14.3_eng). Anda bisa mengutip ini di halaman web untuk menekankan pentingnya protein hewani.
    * **Contoh Menu PMT Pemulihan Berbasis Bahan Lokal:** Tampilkan beberapa resep makanan padat energi yang murah dan mudah didapat di pasar tradisional Indonesia yang direkomendasikan Kemenkes untuk kader Posyandu. Kemenkes sangat merekomendasikan pembuatan Pangan untuk Keperluan Medis Khusus (PKMK) atau makanan tambahan pemulihan yang menggunakan bahan makanan lokal standar WHO untuk efisiensi dan kemudahan akses masyarakat (PEDOMAN PENCEGAHAN DAN TATALAKSANA GIZI BURUK PADA BALITA).
