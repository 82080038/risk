/**
 * Dynamic Rendering Functions
 * Risk Assessment Objek Wisata
 * Render dinamis tanpa reload menggunakan jQuery
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

/**
 * Helper function untuk extract array dari API response
 */
function extractArrayFromResponse(response) {
    if (!response) return [];
    
    // Handle direct response atau nested dalam array (jQuery $.when)
    const data = Array.isArray(response) ? response[0] : response;
    
    if (!data || !data.success) return [];
    
    // Check if data is array directly
    if (Array.isArray(data.data)) {
        return data.data;
    }
    
    // Check if data.data is array (nested structure)
    if (data.data && Array.isArray(data.data.data)) {
        return data.data.data;
    }
    
    return [];
}

/**
 * Render Dashboard Statistics
 */
function renderDashboardStats() {
    showLoading();
    
    // Get stats dari API Dashboard (lebih efisien)
    DashboardAPI.getStats().done(function(response) {
        if (response.success && response.data) {
            const stats = response.data;
            
            // Update cards
            $('#stat-total-objek').text(stats.total_objek || 0);
            $('#stat-sudah-dinilai').text(stats.objek_sudah_dinilai || 0);
            $('#stat-belum-dinilai').text(stats.objek_belum_dinilai || 0);
            $('#stat-total-penilaian').text(stats.total_penilaian || 0);
        }
        hideLoading();
    }).fail(function() {
        // Fallback: Get dari individual APIs
        $.when(
            ObjekWisataAPI.getAll(),
            PenilaianAPI.getAll({ status: 'selesai' })
        ).done(function(objekResponse, penilaianResponse) {
            // Handle nested data structure
            let objekData = [];
            let penilaianData = [];
            
            if (objekResponse[0] && objekResponse[0].success) {
                // Check if data is nested
                if (Array.isArray(objekResponse[0].data)) {
                    objekData = objekResponse[0].data;
                } else if (objekResponse[0].data && Array.isArray(objekResponse[0].data.data)) {
                    objekData = objekResponse[0].data.data;
                }
            }
            
        // Use helper function
        penilaianData = extractArrayFromResponse(penilaianResponse);
            
            // Hitung statistik
            const totalObjek = objekData.length || 0;
            const totalPenilaian = penilaianData.length || 0;
            const objekSudahDinilai = penilaianData.length > 0 
                ? new Set(penilaianData.map(p => p.objek_wisata_id)).size 
                : 0;
            const objekBelumDinilai = totalObjek - objekSudahDinilai;
            
            // Update cards
            $('#stat-total-objek').text(totalObjek);
            $('#stat-sudah-dinilai').text(objekSudahDinilai);
            $('#stat-belum-dinilai').text(objekBelumDinilai);
            $('#stat-total-penilaian').text(totalPenilaian);
            
            hideLoading();
        }).fail(function() {
            hideLoading();
        });
    });
}

/**
 * Render Penilaian Terbaru
 */
function renderPenilaianTerbaru(limit = 5) {
    PenilaianAPI.getAll({
        status: 'selesai',
        limit: limit
    }).done(function(response) {
        // Use helper function
        const penilaian = extractArrayFromResponse(response);
        
        if (penilaian.length > 0) {
            const container = $('#penilaian-terbaru-list');
            
            container.empty();
            
            if (penilaian.length === 0) {
                container.html('<p class="text-muted text-center mb-0">Belum ada penilaian</p>');
                return;
            }
        }
        
        if (penilaian && penilaian.length > 0) {
            penilaian.forEach(function(p) {
                const kategoriBadge = getKategoriBadge(p.kategori);
                const item = `
                    <div class="list-group-item">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="mb-1">${escapeHtml(p.objek_nama)}</h6>
                                <small class="text-muted">
                                    <i class="fas fa-user me-1"></i>${escapeHtml(p.penilai_nama)}<br>
                                    <i class="fas fa-calendar me-1"></i>${formatTanggal(p.tanggal_penilaian)}
                                </small>
                            </div>
                            <div class="text-end">
                                <span class="badge ${kategoriBadge.class}">${p.skor_final}%</span><br>
                                <small class="text-muted">${escapeHtml(p.kategori || '')}</small>
                            </div>
                        </div>
                    </div>
                `;
                container.append(item);
            });
        }
    });
}

/**
 * Render Objek Belum Dinilai
 */
function renderObjekBelumDinilai(limit = 5) {
    ObjekWisataAPI.getAll({
        limit: 1000 // Get all untuk filter
    }).done(function(response) {
        // Use helper function
        const semuaObjek = extractArrayFromResponse(response);
        
        if (semuaObjek.length > 0) {
            // Get semua penilaian untuk filter
            PenilaianAPI.getAll({ status: 'selesai' }).done(function(penilaianResponse) {
                const semuaPenilaian = extractArrayFromResponse(penilaianResponse);
                
                const objekSudahDinilai = semuaPenilaian.length > 0 
                    ? new Set(semuaPenilaian.map(p => p.objek_wisata_id))
                    : new Set();
                
                // Filter objek yang belum dinilai
                const objekBelumDinilai = semuaObjek
                    .filter(objek => !objekSudahDinilai.has(parseInt(objek.id)))
                    .slice(0, limit);
                
                const container = $('#objek-belum-dinilai-list');
                container.empty();
                
                if (objekBelumDinilai.length === 0) {
                    container.html('<p class="text-success text-center mb-0"><i class="fas fa-check-circle me-2"></i>Semua objek sudah dinilai!</p>');
                    return;
                }
                
                objekBelumDinilai.forEach(function(objek) {
                    const item = `
                        <div class="list-group-item">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="mb-1">${escapeHtml(objek.nama)}</h6>
                                    <small class="text-muted">${escapeHtml(objek.alamat.substring(0, 50))}...</small>
                                </div>
                                <div>
                                    <a href="${BASE_URL}pages/penilaian.php?action=new&objek_id=${objek.id}" 
                                       class="btn btn-sm btn-primary">
                                        <i class="fas fa-plus me-1"></i>Nilai
                                    </a>
                                </div>
                            </div>
                        </div>
                    `;
                    container.append(item);
                });
            });
        }
    });
}

/**
 * Render Daftar Objek Wisata (Table)
 */
function renderObjekWisataTable(container, params = {}) {
    showLoading();
    
    ObjekWisataAPI.getAll(params).done(function(response) {
        if (response.success && response.data) {
            const data = response.data.data || [];
            const total = response.data.total || 0;
            
            container.empty();
            
            if (data.length === 0) {
                container.html('<tr><td colspan="5" class="text-center">Tidak ada data</td></tr>');
                hideLoading();
                return;
            }
            
            data.forEach(function(objek, index) {
                const row = `
                    <tr>
                        <td>${(params.page - 1) * (params.limit || 10) + index + 1}</td>
                        <td>${escapeHtml(objek.nama)}</td>
                        <td>${escapeHtml(objek.alamat.substring(0, 50))}...</td>
                        <td>
                            <button class="btn btn-sm btn-primary btn-edit" data-id="${objek.id}">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-danger btn-delete" data-id="${objek.id}">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                `;
                container.append(row);
            });
            
            // Render pagination jika ada
            if (response.data.total_pages > 1) {
                renderPagination('#pagination-container', response.data);
            }
        }
        
        hideLoading();
    });
}

/**
 * Render Daftar Penilaian (Table)
 */
function renderPenilaianTable(container, params = {}) {
    showLoading();
    
    PenilaianAPI.getAll(params).done(function(response) {
        if (response.success && response.data) {
            const data = response.data.data || [];
            
            container.empty();
            
            if (data.length === 0) {
                container.html('<tr><td colspan="6" class="text-center">Tidak ada data</td></tr>');
                hideLoading();
                return;
            }
            
            data.forEach(function(p) {
                const kategoriBadge = getKategoriBadge(p.kategori);
                const statusBadge = p.status === 'selesai' 
                    ? '<span class="badge bg-success">Selesai</span>'
                    : '<span class="badge bg-warning">Draft</span>';
                
                const row = `
                    <tr>
                        <td>${escapeHtml(p.objek_nama)}</td>
                        <td>${escapeHtml(p.penilai_nama)}</td>
                        <td>${formatTanggal(p.tanggal_penilaian)}</td>
                        <td><span class="badge ${kategoriBadge.class}">${p.skor_final || 0}%</span></td>
                        <td>${escapeHtml(p.kategori || '-')}</td>
                        <td>${statusBadge}</td>
                        <td>
                            <a href="${BASE_URL}pages/penilaian.php?id=${p.id}" class="btn btn-sm btn-info">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                `;
                container.append(row);
            });
        }
        
        hideLoading();
    });
}

/**
 * Render Pagination
 */
function renderPagination(container, paginationData) {
    const currentPage = paginationData.page || 1;
    const totalPages = paginationData.total_pages || 1;
    
    let html = '<nav><ul class="pagination justify-content-center">';
    
    // Previous
    if (currentPage > 1) {
        html += `<li class="page-item"><a class="page-link" href="#" data-page="${currentPage - 1}">Previous</a></li>`;
    } else {
        html += '<li class="page-item disabled"><span class="page-link">Previous</span></li>';
    }
    
    // Page numbers
    for (let i = 1; i <= totalPages; i++) {
        if (i === currentPage) {
            html += `<li class="page-item active"><span class="page-link">${i}</span></li>`;
        } else {
            html += `<li class="page-item"><a class="page-link" href="#" data-page="${i}">${i}</a></li>`;
        }
    }
    
    // Next
    if (currentPage < totalPages) {
        html += `<li class="page-item"><a class="page-link" href="#" data-page="${currentPage + 1}">Next</a></li>`;
    } else {
        html += '<li class="page-item disabled"><span class="page-link">Next</span></li>';
    }
    
    html += '</ul></nav>';
    
    $(container).html(html);
}

/**
 * Helper Functions
 */
function escapeHtml(text) {
    const map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    };
    return String(text || '').replace(/[&<>"']/g, m => map[m]);
}

function formatTanggal(dateString) {
    if (!dateString) return '-';
    const date = new Date(dateString);
    const bulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                   'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
    return date.getDate() + ' ' + bulan[date.getMonth()] + ' ' + date.getFullYear();
}

function getKategoriBadge(kategori) {
    if (!kategori) return { class: 'bg-secondary', text: '-' };
    
    const kategoriLower = kategori.toLowerCase();
    if (kategoriLower.includes('emas') || kategoriLower.includes('baik sekali')) {
        return { class: 'bg-warning', text: kategori };
    } else if (kategoriLower.includes('perak') || kategoriLower.includes('baik')) {
        return { class: 'bg-info', text: kategori };
    } else if (kategoriLower.includes('perunggu') || kategoriLower.includes('cukup')) {
        return { class: 'bg-primary', text: kategori };
    } else {
        return { class: 'bg-danger', text: kategori };
    }
}

// Auto-refresh dashboard setiap 30 detik
if (window.location.pathname.includes('dashboard.php')) {
    setInterval(function() {
        renderDashboardStats();
        renderPenilaianTerbaru();
        renderObjekBelumDinilai();
    }, 30000); // 30 detik
}

