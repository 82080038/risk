# 📤 Panduan Upload Aplikasi ke GitHub

## 🎯 Pilih Metode Upload

### ✅ Opsi 1: GitHub Desktop (MUDAH - Disarankan)
Jika Anda sudah punya **GitHub Desktop**, gunakan panduan di: **[GITHUB_DESKTOP_GUIDE.md](GITHUB_DESKTOP_GUIDE.md)**

### ⚙️ Opsi 2: Command Line (Git)
Jika Anda lebih suka menggunakan command line, ikuti panduan di bawah ini.

---

## 🚀 Langkah-Langkah Upload ke GitHub (Command Line)

### 1. Pastikan Git Terinstall

Cek apakah Git sudah terinstall:
```bash
git --version
```

Jika belum, download dari: https://git-scm.com/downloads

### 2. Inisialisasi Git Repository

Buka terminal/command prompt di folder aplikasi:

```bash
# Masuk ke folder aplikasi
cd E:\xampp\htdocs\RISK

# Inisialisasi git
git init

# Tambahkan semua file
git add .

# Commit pertama
git commit -m "Initial commit: Risk Assessment Objek Wisata v1.0"
```

### 3. Buat Repository di GitHub

1. **Login ke GitHub**: https://github.com/login
2. **Klik tombol "+"** (kanan atas) → **"New repository"**
3. **Isi form**:
   - **Repository name**: `risk-assessment-objek-wisata` (atau nama lain)
   - **Description**: `Aplikasi Penilaian Risiko Objek Wisata - Polres Samosir`
   - **Visibility**: 
     - ✅ **Public** (jika ingin open source)
     - ✅ **Private** (jika hanya untuk internal)
   - **JANGAN** centang "Add a README file" (karena sudah ada)
   - **JANGAN** centang "Add .gitignore" (karena sudah ada)
   - **JANGAN** centang "Choose a license" (opsional)
4. **Klik "Create repository"**

### 4. Koneksikan Local Repository ke GitHub

Setelah repository dibuat, GitHub akan menampilkan instruksi. Pilih **"push an existing repository"**:

```bash
# Tambahkan remote repository
git remote add origin https://github.com/82080038/risk-assessment-objek-wisata.git

# Atau jika menggunakan SSH (jika sudah setup SSH key):
# git remote add origin git@github.com:82080038/risk-assessment-objek-wisata.git

# Rename branch ke main (jika perlu)
git branch -M main

# Push ke GitHub
git push -u origin main
```

### 5. Autentikasi GitHub

Jika diminta username dan password:
- **Username**: `82080038` (atau username GitHub Anda)
- **Password**: **JANGAN gunakan password GitHub**, gunakan **Personal Access Token**

#### Cara Membuat Personal Access Token:

1. **Login ke GitHub** → Klik **Profile** (kanan atas) → **Settings**
2. Scroll ke bawah → **Developer settings**
3. Klik **Personal access tokens** → **Tokens (classic)**
4. Klik **Generate new token** → **Generate new token (classic)**
5. **Isi form**:
   - **Note**: `Risk Assessment App Upload`
   - **Expiration**: Pilih durasi (90 days, 1 year, atau no expiration)
   - **Select scopes**: Centang **`repo`** (full control of private repositories)
6. Klik **Generate token**
7. **COPY TOKEN** (hanya muncul sekali, simpan dengan aman!)
8. Gunakan token ini sebagai password saat push

### 6. Verifikasi Upload

Setelah push berhasil:
1. Buka: https://github.com/82080038/risk-assessment-objek-wisata
2. Pastikan semua file sudah ter-upload
3. Cek bahwa file sensitif (`config/database.php`, `config/config.php`) **TIDAK** ter-upload (karena ada di `.gitignore`)

---

## 🔄 Update Repository (Setelah Perubahan)

Jika ada perubahan file, lakukan:

```bash
# Cek status perubahan
git status

# Tambahkan file yang berubah
git add .

# Atau tambahkan file spesifik
git add nama_file.php

# Commit perubahan
git commit -m "Deskripsi perubahan yang dilakukan"

# Push ke GitHub
git push origin main
```

---

## ⚠️ Catatan Penting

### ✅ File yang TIDAK Ter-upload (Sudah di .gitignore)
- `config/database.php` (berisi password database)
- `config/config.php` (berisi konfigurasi lokal)
- `assets/uploads/*` (file upload user)
- `vendor/` (dependencies, install via composer)
- `*.log` (log files)
- File temporary lainnya

### ✅ File Template yang Ter-upload
- `config/database.php.example` (template untuk setup)
- `config/config.php.example` (template untuk setup)
- `config/database.production.php` (template production)
- `config/config.production.php` (template production)

### 🔒 Security
- **JANGAN** commit file `config/database.php` dan `config/config.php` ke GitHub
- Gunakan file `.example` sebagai template
- Setiap developer/server harus membuat file config sendiri

---

## 📝 Contoh Perintah Lengkap

```bash
# 1. Masuk ke folder aplikasi
cd E:\xampp\htdocs\RISK

# 2. Inisialisasi git
git init

# 3. Tambahkan semua file
git add .

# 4. Commit
git commit -m "Initial commit: Risk Assessment Objek Wisata v1.0"

# 5. Tambahkan remote
git remote add origin https://github.com/82080038/risk-assessment-objek-wisata.git

# 6. Rename branch
git branch -M main

# 7. Push (akan diminta username dan token)
git push -u origin main
```

---

## 🆘 Troubleshooting

### Error: "remote origin already exists"
```bash
# Hapus remote yang ada
git remote remove origin

# Tambahkan lagi
git remote add origin https://github.com/82080038/risk-assessment-objek-wisata.git
```

### Error: "authentication failed"
- Pastikan menggunakan **Personal Access Token**, bukan password
- Pastikan token memiliki scope `repo`
- Coba buat token baru

### Error: "failed to push some refs"
```bash
# Pull dulu dari GitHub
git pull origin main --allow-unrelated-histories

# Lalu push lagi
git push origin main
```

### Error: "file too large"
- File besar (>100MB) tidak bisa di-upload langsung ke GitHub
- Gunakan Git LFS (Large File Storage) atau hapus file tersebut dari commit

---

## ✅ Checklist Upload

- [ ] Git sudah terinstall
- [ ] Repository sudah dibuat di GitHub
- [ ] Personal Access Token sudah dibuat
- [ ] File `config/database.php` dan `config/config.php` TIDAK ter-upload
- [ ] File template `.example` ter-upload
- [ ] Semua file penting sudah ter-upload
- [ ] Repository bisa diakses di GitHub

---

**Selamat! Aplikasi Anda sudah ter-upload ke GitHub! 🎉**

