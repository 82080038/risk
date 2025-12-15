# JQUERY DYNAMIC RENDERING SETUP
## Risk Assessment Objek Wisata

---

## ✅ YANG SUDAH DIIMPLEMENTASIKAN

### 1. **API Endpoints (RESTful)**
- ✅ `api/api_base.php` - Base API dengan JSON response
- ✅ `api/objek_wisata.php` - CRUD Objek Wisata (GET, POST, PUT, DELETE)
- ✅ `api/penilaian.php` - CRUD Penilaian (GET, POST, PUT)
- ✅ `api/kriteria.php` - Get Kriteria (GET)
- ✅ `api/dashboard.php` - Get Statistik Dashboard (GET)

### 2. **JavaScript API Helpers**
- ✅ `assets/js/api.js` - API helper functions menggunakan jQuery
  - `ObjekWisataAPI` - CRUD Objek Wisata
  - `PenilaianAPI` - CRUD Penilaian
  - `KriteriaAPI` - Get Kriteria
  - `DashboardAPI` - Get Statistik

### 3. **Dynamic Rendering Functions**
- ✅ `assets/js/dynamic.js` - Functions untuk render dinamis
  - `renderDashboardStats()` - Update statistik tanpa reload
  - `renderPenilaianTerbaru()` - Update daftar penilaian terbaru
  - `renderObjekBelumDinilai()` - Update daftar objek belum dinilai
  - `renderObjekWisataTable()` - Render tabel objek wisata
  - `renderPenilaianTable()` - Render tabel penilaian
  - `renderPagination()` - Render pagination

### 4. **Dashboard Auto-Refresh**
- ✅ Auto-refresh setiap 30 detik
- ✅ Update statistik cards
- ✅ Update penilaian terbaru
- ✅ Update objek belum dinilai
- ✅ **Tanpa reload halaman**

---

## 🔧 CARA KERJA

### 1. **API Endpoints**
Semua API mengembalikan JSON dengan format:
```json
{
    "success": true/false,
    "message": "Pesan",
    "data": {...}
}
```

### 2. **jQuery AJAX**
Menggunakan jQuery untuk:
- GET data dari API
- POST/PUT data ke API
- DELETE data
- Handle response dan error
- Update DOM tanpa reload

### 3. **Dynamic Rendering**
- Data diambil via AJAX
- DOM di-update menggunakan jQuery
- Tidak ada reload halaman
- Auto-refresh untuk data real-time

---

## 📝 CONTOH PENGGUNAAN

### Get Data Objek Wisata:
```javascript
ObjekWisataAPI.getAll({ page: 1, limit: 10 })
    .done(function(response) {
        if (response.success) {
            console.log(response.data);
            // Render ke DOM
        }
    })
    .fail(function() {
        showAlert('Gagal mengambil data', 'danger');
    });
```

### Create Objek Wisata:
```javascript
ObjekWisataAPI.create({
    nama: 'Pantai Pasir Putih',
    alamat: 'Lokasi: Parbaba/Samosir'
})
.done(function(response) {
    if (response.success) {
        showAlert('Data berhasil ditambahkan', 'success');
        // Refresh table tanpa reload
        renderObjekWisataTable('#table-body');
    }
});
```

### Update Dashboard Stats:
```javascript
// Auto-refresh setiap 30 detik
setInterval(function() {
    renderDashboardStats();
    renderPenilaianTerbaru();
    renderObjekBelumDinilai();
}, 30000);
```

---

## 🎯 FITUR DYNAMIC RENDERING

### Dashboard:
- ✅ Statistik cards auto-update
- ✅ Penilaian terbaru auto-update
- ✅ Objek belum dinilai auto-update
- ✅ **Tanpa reload halaman**

### Tabel Data:
- ✅ Load data via AJAX
- ✅ Pagination tanpa reload
- ✅ Search/filter tanpa reload
- ✅ CRUD operations tanpa reload

### Forms:
- ✅ Submit via AJAX
- ✅ Validation real-time
- ✅ Error handling tanpa reload
- ✅ Success feedback

---

## 🔄 AUTO-REFRESH

### Dashboard:
- **Interval:** 30 detik
- **Update:** Statistik, Penilaian Terbaru, Objek Belum Dinilai
- **Method:** AJAX GET requests
- **No Reload:** ✅

### Custom Interval:
```javascript
// Set custom interval (dalam milidetik)
setInterval(function() {
    renderDashboardStats();
}, 60000); // 60 detik
```

---

## 📦 DEPENDENCIES

### Required:
- ✅ jQuery 3.7.1 (CDN)
- ✅ Bootstrap 5.3.2 (CDN)
- ✅ Font Awesome 6.5.1 (CDN)

### File Structure:
```
assets/js/
├── app.js          (Base functions)
├── api.js          (API helpers)
└── dynamic.js      (Dynamic rendering)
```

---

## 🚀 IMPLEMENTASI

### 1. **Dashboard**
File: `pages/dashboard.php`
- ID elements untuk update: `#stat-total-objek`, `#stat-sudah-dinilai`, dll
- Auto-refresh setiap 30 detik
- Render functions dipanggil saat page load

### 2. **API Endpoints**
Semua di folder `api/`
- JSON response
- Error handling
- Authentication check

### 3. **JavaScript**
Semua di folder `assets/js/`
- Loaded di footer.php
- BASE_URL didefinisikan untuk JavaScript
- Global functions untuk easy access

---

## ✅ CHECKLIST

- [x] API endpoints dibuat
- [x] jQuery API helpers dibuat
- [x] Dynamic rendering functions dibuat
- [x] Dashboard auto-refresh
- [x] Error handling
- [x] Loading indicators
- [x] Success/error alerts
- [x] No page reload

---

## 🎉 HASIL

**Aplikasi sekarang menggunakan jQuery untuk:**
- ✅ Render dinamis tanpa reload
- ✅ Auto-refresh data
- ✅ CRUD operations via AJAX
- ✅ Real-time updates
- ✅ Better user experience

**Semua operasi dilakukan tanpa reload halaman!**

