# 📤 Panduan Upload ke GitHub menggunakan GitHub Desktop

## 🚀 Langkah-Langkah dengan GitHub Desktop

### 1. Buka GitHub Desktop

1. **Buka aplikasi GitHub Desktop** di komputer Anda
2. Pastikan sudah **login** ke akun GitHub (`82080038`)

### 2. Tambahkan Repository Lokal

#### Opsi A: Add Existing Repository

1. Di GitHub Desktop, klik **"File"** → **"Add Local Repository"**
2. Klik **"Choose..."** dan pilih folder aplikasi:
   ```
   E:\xampp\htdocs\RISK
   ```
3. Klik **"Add Repository"**

#### Opsi B: Create New Repository

1. Di GitHub Desktop, klik **"File"** → **"New Repository"**
2. Isi form:
   - **Name**: `risk-assessment-objek-wisata`
   - **Description**: `Aplikasi Penilaian Risiko Objek Wisata - Polres Samosir`
   - **Local Path**: `E:\xampp\htdocs\RISK`
   - **Git Ignore**: Pilih **"None"** (karena sudah ada `.gitignore`)
   - **License**: Pilih **"None"** (atau pilih sesuai kebutuhan)
3. Klik **"Create Repository"**

### 3. Review Perubahan

Setelah repository ditambahkan:
1. GitHub Desktop akan menampilkan semua file yang akan di-commit
2. Di panel kiri, Anda akan melihat:
   - **Changed files** (semua file baru)
   - **Summary** (untuk menulis commit message)

### 4. Buat Commit Pertama

1. Di bagian bawah, isi **"Summary"** (commit message):
   ```
   Initial commit: Risk Assessment Objek Wisata v1.0
   ```
2. (Opsional) Isi **"Description"** untuk detail lebih lanjut:
   ```
   Aplikasi penilaian risiko objek wisata dengan fitur:
   - Authentication & Session Management
   - CRUD Objek Wisata
   - Form Penilaian Lengkap (6 Aspek, 150+ Kriteria)
   - Laporan PDF dengan TCPDF
   - Mobile-First Responsive Design
   - Export/Import Data
   - Statistik Detail
   ```
3. Pastikan semua file yang ingin di-upload sudah tercentang (checked)
4. Klik **"Commit to main"** (atau branch yang sesuai)

### 5. Publish ke GitHub

Setelah commit dibuat:
1. Klik tombol **"Publish repository"** di bagian atas
2. Atau klik **"Repository"** → **"Push origin"** (jika repository sudah ada di GitHub)

#### Jika Repository Baru (Publish):
1. Centang **"Keep this code private"** jika ingin private, atau biarkan tidak tercentang untuk public
2. Klik **"Publish Repository"**
3. GitHub Desktop akan membuat repository baru di GitHub dan push semua file

#### Jika Repository Sudah Ada (Push):
1. Pastikan remote sudah dikonfigurasi dengan benar
2. Klik **"Push origin"** untuk mengirim perubahan ke GitHub

### 6. Verifikasi Upload

1. Setelah publish/push selesai, klik **"View on GitHub"** (tombol di bagian atas)
2. Atau buka browser dan akses: `https://github.com/82080038/risk-assessment-objek-wisata`
3. Pastikan semua file sudah ter-upload
4. **PENTING**: Pastikan file `config/database.php` dan `config/config.php` **TIDAK** ter-upload (karena ada di `.gitignore`)

---

## 🔄 Update Repository (Setelah Perubahan)

Jika ada perubahan file di aplikasi:

1. **Buka GitHub Desktop**
2. GitHub Desktop akan otomatis mendeteksi perubahan
3. Di panel kiri, Anda akan melihat file yang berubah
4. **Review perubahan** (klik file untuk melihat diff)
5. Isi **"Summary"** dengan deskripsi perubahan:
   ```
   Update: Menambahkan fitur panduan penggunaan
   ```
6. Klik **"Commit to main"**
7. Klik **"Push origin"** untuk mengirim ke GitHub

---

## ⚙️ Konfigurasi Repository (Jika Perlu)

### Menambahkan Remote Repository yang Sudah Ada

Jika repository sudah dibuat di GitHub web:

1. Di GitHub Desktop, klik **"Repository"** → **"Repository Settings"**
2. Klik tab **"Remote"**
3. Klik **"Add Remote"**
4. Isi:
   - **Name**: `origin`
   - **Primary remote**: Centang jika ini remote utama
   - **URL**: `https://github.com/82080038/risk-assessment-objek-wisata.git`
5. Klik **"Add Remote"**

### Mengubah Branch

1. Klik dropdown **"Current branch"** (di bagian atas)
2. Pilih branch yang ingin digunakan
3. Atau buat branch baru dengan klik **"New branch"**

---

## ✅ Checklist Upload dengan GitHub Desktop

- [ ] GitHub Desktop sudah terinstall dan login
- [ ] Repository lokal sudah ditambahkan ke GitHub Desktop
- [ ] Semua file penting sudah tercentang untuk commit
- [ ] File `config/database.php` dan `config/config.php` **TIDAK** tercentang (sudah di .gitignore)
- [ ] Commit message sudah diisi
- [ ] Repository sudah di-publish/push ke GitHub
- [ ] File sudah terverifikasi di GitHub web

---

## 🆘 Troubleshooting

### Error: "Repository not found"
- **Penyebab**: Repository belum dibuat di GitHub atau URL salah
- **Solusi**: 
  1. Buat repository dulu di GitHub web
  2. Atau gunakan opsi "Publish repository" untuk membuat otomatis

### Error: "Authentication failed"
- **Penyebab**: Belum login atau token expired
- **Solusi**: 
  1. Di GitHub Desktop, klik **"File"** → **"Options"** → **"Accounts"**
  2. Pastikan sudah login dengan akun yang benar
  3. Jika perlu, logout dan login lagi

### File Sensitif Ter-upload
- **Penyebab**: File tidak ada di `.gitignore` atau sudah ter-commit sebelumnya
- **Solusi**: 
  1. Hapus file dari commit history (advanced)
  2. Atau pastikan file ada di `.gitignore` dan commit perubahan `.gitignore`
  3. File yang sudah ter-commit perlu dihapus dari history (gunakan `git filter-branch` atau hubungi support)

### Tidak Ada Perubahan yang Terdeteksi
- **Penyebab**: File sudah ter-commit atau tidak ada perubahan
- **Solusi**: 
  1. Pastikan file sudah disimpan
  2. Refresh GitHub Desktop (klik **"Repository"** → **"Refresh"**)
  3. Atau restart GitHub Desktop

---

## 💡 Tips

1. **Commit Sering**: Lakukan commit setiap kali ada perubahan penting, jangan menunggu terlalu banyak perubahan sekaligus
2. **Commit Message Jelas**: Tulis commit message yang jelas dan deskriptif
3. **Review Sebelum Commit**: Selalu review file yang akan di-commit untuk memastikan tidak ada file sensitif
4. **Sync Regular**: Push ke GitHub secara berkala untuk backup kode
5. **Branch untuk Fitur Baru**: Gunakan branch terpisah untuk fitur besar, lalu merge ke main

---

## 📝 Contoh Workflow

```
1. Buka aplikasi → Edit file
2. Simpan perubahan
3. Buka GitHub Desktop → Review perubahan
4. Tulis commit message → Commit
5. Push ke GitHub
6. Verifikasi di GitHub web
```

---

**Selamat! Upload dengan GitHub Desktop lebih mudah dan user-friendly! 🎉**

