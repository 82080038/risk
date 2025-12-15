# 🆘 Troubleshooting Railway Deployment

## Error: Docker Build Failed

### Error yang Terjadi
```
10 | >>>     libfreetype6-dev \
```

### Solusi 1: Gunakan Dockerfile.railway (Sudah Dibuat)

Railway akan otomatis menggunakan `Dockerfile.railway` karena sudah dikonfigurasi di `railway.json`.

**File yang sudah dibuat**:
- ✅ `Dockerfile.railway` - Versi sederhana untuk Railway (PHP CLI)
- ✅ `railway.json` - Konfigurasi untuk menggunakan Dockerfile.railway

### Solusi 2: Railway Auto-Detect (Tanpa Dockerfile)

Jika masih error, coba **hapus Dockerfile** dan biarkan Railway auto-detect:

1. **Di Railway Dashboard**:
   - Klik Web Service → **"Settings"** tab
   - **Hapus** atau **nonaktifkan** Dockerfile
   - Railway akan otomatis detect PHP dan setup

2. **Set Build & Start Command**:
   - **Build Command**: `composer install --no-dev --optimize-autoloader`
   - **Start Command**: `php -S 0.0.0.0:$PORT -t .`

### Solusi 3: Gunakan Nixpacks (Railway Auto-Detect)

Railway menggunakan **Nixpacks** untuk auto-detect aplikasi. Untuk PHP:

1. **Pastikan file berikut ada**:
   - `composer.json` ✅ (sudah ada)
   - `index.php` ✅ (sudah ada)

2. **Railway akan otomatis**:
   - Detect PHP
   - Install dependencies via Composer
   - Setup PHP server

3. **Tidak perlu Dockerfile** jika Railway bisa auto-detect

---

## 🔧 Konfigurasi Manual di Railway

Jika auto-detect tidak bekerja:

### 1. Settings → Build

- **Build Command**: 
  ```bash
  composer install --no-dev --optimize-autoloader
  ```

### 2. Settings → Deploy

- **Start Command**: 
  ```bash
  php -S 0.0.0.0:$PORT -t .
  ```

### 3. Settings → Healthcheck

- **Healthcheck Path**: `/`
- **Healthcheck Timeout**: 100

---

## 📝 Alternatif: Gunakan InfinityFree (Lebih Mudah)

Jika Railway terus bermasalah, **gunakan InfinityFree** yang:
- ✅ 100% gratis tanpa kartu kredit
- ✅ Setup lebih mudah (cPanel)
- ✅ Tidak perlu Dockerfile
- ✅ MySQL langsung support

**Panduan**: Lihat [HOSTING_GRATIS.md](HOSTING_GRATIS.md)

---

## ✅ Checklist Troubleshooting

- [ ] Dockerfile sudah di-update (sudah ✅)
- [ ] Dockerfile.railway sudah dibuat (sudah ✅)
- [ ] railway.json sudah dikonfigurasi (sudah ✅)
- [ ] Coba rebuild di Railway
- [ ] Cek build logs untuk error detail
- [ ] Jika masih error, coba nonaktifkan Dockerfile dan gunakan auto-detect
- [ ] Atau gunakan InfinityFree sebagai alternatif

---

**File sudah di-push ke GitHub. Coba rebuild di Railway! 🚂**

