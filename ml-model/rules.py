# Basis Pengetahuan Gejala Klinis & Faktor Risiko
ATURAN_GEJALA_CF = {
    "R03": {"nama": "Perlambatan Pertumbuhan Linear (Linear Faltering)", "cf_pakar": 0.80},
    "R04": {"nama": "Weight Faltering (Gagal Tumbuh)", "cf_pakar": 0.70},
    "R05": {"nama": "Wasted (Gizi Kurang berdasarkan BB/TB)", "cf_pakar": 0.75},
    "R06": {"nama": "Edema Bilateral (Pitting)", "cf_pakar": 0.90},
    "R07": {"nama": "Penyakit Infeksi Berulang (Diare/ISPA)", "cf_pakar": 0.60},
    "R08": {"nama": "Riwayat BBLR / Prematur", "cf_pakar": 0.50},
    "R09": {"nama": "Red-Flags Sistemik (Muntah/Demam)", "cf_pakar": 0.70}
}

def hitung_cf_kombinasi(daftar_gejala_user: dict, cf_ml_awal: float) -> float:
    """
    Fungsi untuk menghitung akumulasi Certainty Factor menggunakan metode Forward Chaining.
    daftar_gejala_user berisi key aturan dan nilai keyakinan user (0.0 sampai 1.0)
    Contoh: {"R04": 1.0, "R07": 0.6}
    cf_ml_awal adalah probabilitas stunting dari model Random Forest (0.0 - 1.0)
    """
    cf_list = []
    
    # 1. Masukkan CF dari Machine Learning sebagai keyakinan dasar (Pemicu Utama)
    if cf_ml_awal > 0:
        cf_list.append(cf_ml_awal)

    # 2. Hitung CF tiap gejala yang dialami: CF(H,E) = CF(Pakar) * CF(User)
    for rule_id, cf_user in daftar_gejala_user.items():
        if rule_id in ATURAN_GEJALA_CF and cf_user > 0:
            cf_pakar = ATURAN_GEJALA_CF[rule_id]["cf_pakar"]
            cf_gejala = cf_pakar * cf_user
            cf_list.append(cf_gejala)
            
    if not cf_list:
        return 0.0

    # 3. Kombinasikan semua nilai CF menggunakan rumus akumulasi:
    # CF_gabungan = CF1 + CF2 * (1 - CF1)
    cf_gabungan = cf_list[0]
    for next_cf in cf_list[1:]:
        if cf_gabungan >= 0 and next_cf >= 0:
            cf_gabungan = cf_gabungan + next_cf * (1 - cf_gabungan)
        # Catatan: Jika ada CF negatif (MD > MB), rumusnya berbeda. Namun karena di tabel kita Net CF positif semua, rumus ini sudah aman.

    return round(cf_gabungan * 100, 2) # Mengembalikan dalam bentuk persentase (0 - 100%)


def dapatkan_rekomendasi(status_ml: str, cf_total: float, daftar_gejala: list) -> list:
    """
    Mesin Inferensi akhir untuk menentukan tindakan berdasarkan status dan akumulasi CF
    """
    rekomendasi = []
    
    # Aturan Rekomendasi Medis Akut (R06 atau R09 atau CF sangat tinggi)
    if "R06" in daftar_gejala or "R09" in daftar_gejala or cf_total > 85:
        rekomendasi.append("⚠️ SEGERA RUJUK ke Fasilitas Kesehatan / Puskesmas untuk penanganan gizi buruk akut klinis.")
        rekomendasi.append("Berikan PMT (Pemberian Makanan Tambahan) Pemulihan di bawah pengawasan medis.")

    # Aturan Rekomendasi Intervensi Gizi Makro/Mikro
    if status_ml == "Stunting" or "R03" in daftar_gejala or "R04" in daftar_gejala:
        rekomendasi.append("Terapkan 'Feeding Rules' (Jadwal makan ketat) dari IDAI untuk mengatasi GTM/Anoreksia.")
        rekomendasi.append("Berikan MPASI padat energi yang kaya akan protein hewani (daging, hati ayam, telur).")
        rekomendasi.append("Konsultasikan ke kader Posyandu untuk pemberian suplemen Zinc dan Vitamin A.")

    # Aturan Rekomendasi Lingkungan & Preventif
    if "R07" in daftar_gejala:
        rekomendasi.append("Edukasi orang tua mengenai PHBS (Perilaku Hidup Bersih dan Sehat) untuk memutus rantai infeksi.")
        rekomendasi.append("Periksa kelayakan kualitas air bersih dan sanitasi di lingkungan tempat tinggal.")
        
    if "R08" in daftar_gejala:
        rekomendasi.append("Lakukan pemantauan tumbuh kembang secara lebih intensif (minimal 2 minggu sekali) karena riwayat BBLR/Prematur.")

    if not rekomendasi:
        rekomendasi.append("Pertahankan pola makan gizi seimbang dan rutin melakukan kunjungan bulanan ke Posyandu.")

    return rekomendasi