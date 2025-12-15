# CARA MENJALANKAN SIMULASI PENILAIAN

## 📋 Deskripsi
Script simulasi penilaian digunakan untuk menguji sistem perhitungan skor penilaian dengan data simulasi.

## 🚀 Cara Menggunakan

### Opsi 1: Melalui Browser (Recommended)
1. Buka browser
2. Akses: `http://localhost/RISK/simulasi_penilaian.php`
3. Hasil akan ditampilkan dalam format HTML yang lengkap dan mudah dibaca

### Opsi 2: Melalui Command Line
1. Buka terminal/command prompt
2. Jalankan: `php test_simulasi_penilaian.php`
3. Hasil akan ditampilkan dalam format text

## 📊 Apa yang Dilakukan Script?

1. **Mengambil Data:**
   - Objek wisata pertama dari database
   - User penilai pertama dari database

2. **Membuat Penilaian Baru:**
   - Membuat record penilaian baru dengan status 'draft'
   - Mengisi data penilai dan objek wisata

3. **Mengisi Nilai Kriteria (Simulasi):**
   - Mengambil semua aspek, elemen, dan kriteria
   - Memberikan nilai random untuk setiap kriteria:
     - 15% mendapat nilai 0 (Tidak dapat dipenuhi)
     - 25% mendapat nilai 1 (Terdapat kekurangan)
     - 60% mendapat nilai 2 (Dapat dipenuhi)
   - Menyimpan nilai, temuan, dan rekomendasi ke database

4. **Menghitung Skor:**
   - **Skor Elemen** = (Jumlah nilai kriteria / (Jumlah kriteria × 2)) × 100
   - **Skor Aspek** = Σ(Skor Elemen × Bobot Elemen)
   - **Skor Final** = Σ(Skor Aspek × Bobot Aspek)

5. **Menentukan Kategori:**
   - 86-100%: Baik Sekali (Kategori Emas) 🥇
   - 71-85%: Baik (Kategori Perak) 🥈
   - 56-70%: Cukup (Kategori Perunggu) 🥉
   - < 55%: Kurang (Tindakan Pembinaan) ⚠️

6. **Menyimpan Hasil:**
   - Update penilaian dengan skor final dan kategori
   - Mengubah status menjadi 'selesai'

## 📈 Output yang Ditampilkan

### Versi HTML (`simulasi_penilaian.php`):
- ✅ Data simulasi (objek wisata, penilai)
- ✅ Skor final dan kategori dengan visualisasi
- ✅ Detail per aspek dengan perhitungan
- ✅ Detail per elemen dengan perhitungan
- ✅ Perhitungan skor final lengkap
- ✅ Statistik (total kriteria, total nilai, dll)

### Versi Command Line (`test_simulasi_penilaian.php`):
- ✅ Data simulasi
- ✅ Skor final dan kategori
- ✅ Detail per aspek
- ✅ Detail per elemen
- ✅ Perhitungan skor final

## ⚠️ Catatan

1. **Pastikan Database Sudah Terisi:**
   - Minimal ada 1 objek wisata
   - Minimal ada 1 user dengan role 'penilai'
   - Data aspek, elemen, dan kriteria sudah ada

2. **Data yang Dibuat:**
   - Script akan membuat penilaian baru setiap kali dijalankan
   - Data simulasi akan tersimpan di database
   - Status penilaian: 'selesai'

3. **Distribusi Nilai:**
   - Distribusi nilai adalah random untuk simulasi
   - Dalam penggunaan nyata, nilai diisi oleh penilai

## 🔍 Verifikasi Hasil

Setelah menjalankan simulasi, Anda dapat:
1. Melihat hasil di halaman simulasi
2. Memeriksa di database:
   - Tabel `penilaian` - ada record baru dengan skor_final
   - Tabel `penilaian_detail` - ada nilai untuk setiap kriteria
3. Melihat di aplikasi:
   - Halaman "Daftar Penilaian" - penilaian baru muncul
   - Halaman "Detail Penilaian" - bisa melihat detail lengkap
   - Halaman "Laporan" - bisa download PDF

## 📝 Contoh Output

```
=== SIMULASI PENILAIAN ===

Objek Wisata: Danau Toba
Penilai: John Doe
Tanggal: 2025-01-15

Penilaian ID: 1

=== HASIL PENILAIAN ===

Skor Final: 75.50%
Kategori: 🥈 Baik (Kategori Perak)
Total Kriteria: 150
Total Nilai: 226
Nilai Maksimal: 300

=== DETAIL PER ASPEK ===

Aspek: INFRASTRUKTUR (Bobot: 20%)
  Skor Aspek: 78.50%
  Kontribusi: 15.70%
  - Elemen: KELAIKAN GEDUNG/VENUE (Bobot: 50%)
    Skor: 80.00%
    Kriteria: 25 (Nilai: 40)
    Perhitungan: (40 / (25 × 2)) × 100 = 80.00%
  ...

=== PERHITUNGAN SKOR FINAL ===

INFRASTRUKTUR: 78.50% × 20% = 15.70%
KEAMANAN: 72.30% × 20% = 14.46%
...
Total: 75.50%
```

---

**Selamat mencoba simulasi penilaian!** 🎉

