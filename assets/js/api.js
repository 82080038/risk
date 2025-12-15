/**
 * API Helper Functions
 * Risk Assessment Objek Wisata
 * Menggunakan jQuery untuk AJAX
 */

// Pastikan jQuery sudah loaded
if (typeof jQuery === 'undefined') {
    console.error('jQuery is required but not loaded');
} else {
    // Alias jQuery ke $ jika belum
    if (typeof $ === 'undefined') {
        window.$ = jQuery;
    }
}

// Base URL untuk API
const API_BASE = BASE_URL + 'api/';

// AJAX Default Settings
$.ajaxSetup({
    timeout: 30000,
    error: function(xhr, status, error) {
        console.error('AJAX Error:', status, error);
        if (xhr.status === 401) {
            alert('Session expired. Silakan login kembali.');
            window.location.href = BASE_URL + 'pages/login.php';
        } else if (xhr.status === 0) {
            showAlert('Tidak dapat terhubung ke server. Pastikan server berjalan.', 'danger');
        } else {
            try {
                const response = JSON.parse(xhr.responseText);
                showAlert(response.message || 'Terjadi kesalahan', 'danger');
            } catch (e) {
                showAlert('Terjadi kesalahan: ' + error, 'danger');
            }
        }
    }
});

/**
 * Objek Wisata API
 */
const ObjekWisataAPI = {
    // Get all
    getAll: function(params = {}) {
        return $.ajax({
            url: API_BASE + 'objek_wisata.php',
            method: 'GET',
            data: params,
            dataType: 'json'
        });
    },
    
    // Get by ID
    getById: function(id) {
        return $.ajax({
            url: API_BASE + 'objek_wisata.php',
            method: 'GET',
            data: { id: id },
            dataType: 'json'
        });
    },
    
    // Create
    create: function(data) {
        return $.ajax({
            url: API_BASE + 'objek_wisata.php',
            method: 'POST',
            contentType: 'application/json',
            data: JSON.stringify(data),
            dataType: 'json'
        });
    },
    
    // Update
    update: function(id, data) {
        return $.ajax({
            url: API_BASE + 'objek_wisata.php?id=' + id,
            method: 'PUT',
            contentType: 'application/json',
            data: JSON.stringify(data),
            dataType: 'json'
        });
    },
    
    // Delete
    delete: function(id) {
        return $.ajax({
            url: API_BASE + 'objek_wisata.php?id=' + id,
            method: 'DELETE',
            dataType: 'json'
        });
    }
};

/**
 * Penilaian API
 */
const PenilaianAPI = {
    // Get all
    getAll: function(params = {}) {
        return $.ajax({
            url: API_BASE + 'penilaian.php',
            method: 'GET',
            data: params,
            dataType: 'json'
        }).then(function(response) {
            // Normalize response structure
            if (response && response.success) {
                return response;
            }
            return response;
        });
    },
    
    // Get by ID
    getById: function(id) {
        return $.ajax({
            url: API_BASE + 'penilaian.php',
            method: 'GET',
            data: { id: id },
            dataType: 'json'
        });
    },
    
    // Create
    create: function(data) {
        return $.ajax({
            url: API_BASE + 'penilaian.php',
            method: 'POST',
            contentType: 'application/json',
            data: JSON.stringify(data),
            dataType: 'json'
        });
    },
    
    // Update
    update: function(id, data) {
        return $.ajax({
            url: API_BASE + 'penilaian.php?id=' + id,
            method: 'PUT',
            contentType: 'application/json',
            data: JSON.stringify(data),
            dataType: 'json'
        });
    },
    
    // Save draft
    saveDraft: function(data) {
        data.status = 'draft';
        if (data.id) {
            return this.update(data.id, data);
        } else {
            return this.create(data);
        }
    },
    
    // Submit
    submit: function(id, data) {
        data.status = 'selesai';
        return this.update(id, data);
    }
};

/**
 * Kriteria API
 */
const KriteriaAPI = {
    // Get all aspek dengan struktur lengkap
    getAllAspek: function() {
        return $.ajax({
            url: API_BASE + 'kriteria.php',
            method: 'GET',
            dataType: 'json'
        });
    },
    
    // Get kriteria by aspek
    getByAspek: function(aspek_id) {
        return $.ajax({
            url: API_BASE + 'kriteria.php',
            method: 'GET',
            data: { aspek_id: aspek_id },
            dataType: 'json'
        });
    },
    
    // Get kriteria by elemen
    getByElemen: function(elemen_id) {
        return $.ajax({
            url: API_BASE + 'kriteria.php',
            method: 'GET',
            data: { elemen_id: elemen_id },
            dataType: 'json'
        });
    }
};

/**
 * Dashboard API
 */
const DashboardAPI = {
    // Get statistics
    getStats: function() {
        return $.ajax({
            url: API_BASE + 'dashboard.php',
            method: 'GET',
            dataType: 'json'
        });
    },
    
    // Get penilaian terbaru
    getPenilaianTerbaru: function(limit = 5) {
        return PenilaianAPI.getAll({
            status: 'selesai',
            limit: limit
        });
    },
    
    // Get objek belum dinilai
    getObjekBelumDinilai: function(limit = 5) {
        return ObjekWisataAPI.getAll({
            belum_dinilai: true,
            limit: limit
        });
    }
};

// Export untuk penggunaan global
window.ObjekWisataAPI = ObjekWisataAPI;
window.PenilaianAPI = PenilaianAPI;
window.KriteriaAPI = KriteriaAPI;
window.DashboardAPI = DashboardAPI;

