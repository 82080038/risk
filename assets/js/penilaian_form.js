/**
 * Penilaian Form JavaScript
 * Risk Assessment Objek Wisata
 * Auto-save, perhitungan skor, validasi
 */

// Data structure untuk menyimpan bobot
let aspekData = {};
let elemenData = {};
let kriteriaData = {};

// Auto-save interval
let autoSaveInterval = null;
let autoSaveEnabled = false;
let lastSaveTime = null;

// Initialize
$(document).ready(function() {
    console.log('Penilaian form script loaded');
    
    // Load data structure
    loadDataStructure();
    
    // Setup event handlers
    setupEventHandlers();
    
    // Calculate initial scores
    calculateAllScores();
    
    // Update progress
    updateProgress();
    
    // Load existing data if editing
    if ($('#penilaian-form').data('penilaian-id')) {
        loadExistingData();
    }
});

/**
 * Load data structure dari DOM
 */
function loadDataStructure() {
    $('[data-aspek-id]').each(function() {
        const aspekId = $(this).data('aspek-id');
        const elemenId = $(this).data('elemen-id');
        const kriteriaId = $(this).data('kriteria-id');
        
        // Get bobot dari DOM
        const aspekBobot = parseFloat($(`#aspek-${aspekId}`).find('.card-header').text().match(/Bobot: ([\d.]+)%/)?.[1] || 0) / 100;
        const elemenBobot = parseFloat($(`[data-elemen-id="${elemenId}"]`).closest('.card').find('.card-header').text().match(/Bobot Elemen: ([\d.]+)%/)?.[1] || 0) / 100;
        
        if (!aspekData[aspekId]) {
            aspekData[aspekId] = { bobot: aspekBobot, elemen: {} };
        }
        if (!aspekData[aspekId].elemen[elemenId]) {
            aspekData[aspekId].elemen[elemenId] = { bobot: elemenBobot, kriteria: [] };
        }
        aspekData[aspekId].elemen[elemenId].kriteria.push(kriteriaId);
    });
    
    console.log('Data structure loaded:', aspekData);
}

/**
 * Setup event handlers
 */
function setupEventHandlers() {
    // Nilai change
    $(document).on('change', '.nilai-select', function() {
        const kriteriaId = $(this).data('kriteria-id');
        const nilai = $(this).val();
        const row = $(this).closest('tr');
        const uploadRow = $(`.upload-row[data-kriteria-id="${kriteriaId}"]`);
        
        // Show/hide temuan dan rekomendasi berdasarkan nilai
        if (nilai === '0' || nilai === '1') {
            row.find('.temuan-input, .rekomendasi-input').prop('required', true);
            row.find('.temuan-input, .rekomendasi-input').closest('td').addClass('table-warning');
            // Show upload section
            uploadRow.show();
        } else {
            row.find('.temuan-input, .rekomendasi-input').prop('required', false);
            row.find('.temuan-input, .rekomendasi-input').closest('td').removeClass('table-warning');
            // Hide upload section
            uploadRow.hide();
        }
        
        // Calculate scores
        calculateAllScores();
        updateProgress();
        
        // Auto-save jika enabled
        if (autoSaveEnabled) {
            debounceAutoSave();
        }
    });
    
    // Temuan/rekomendasi change
    $(document).on('input', '.temuan-input, .rekomendasi-input', function() {
        if (autoSaveEnabled) {
            debounceAutoSave();
        }
    });
    
    // Tab change
    $('#aspek-tabs button').on('shown.bs.tab', function() {
        updateProgress();
    });
    
    // Save draft button
    $('#btn-save-draft').on('click', function() {
        saveDraft();
    });
    
    // Submit button
    $('#btn-submit').on('click', function() {
        submitPenilaian();
    });
    
    // Auto-save toggle
    $('#btn-auto-save-status').on('click', function() {
        toggleAutoSave();
    });
    
    // Mobile buttons
    $('#btn-submit-mobile').on('click', function() {
        submitPenilaian();
    });
    
    $('#btn-save-draft-mobile').on('click', function() {
        saveDraft();
    });
    
    $('#btn-auto-save-status-mobile').on('click', function() {
        toggleAutoSave();
    });
}

/**
 * Calculate score per elemen
 */
function calculateElemenScore(elemenId) {
    const row = $(`[data-elemen-id="${elemenId}"]`);
    const kriteriaRows = row.find('tr[data-kriteria-id]');
    
    let totalNilai = 0;
    let totalKriteria = 0;
    let filledKriteria = 0;
    
    kriteriaRows.each(function() {
        const nilai = $(this).find('.nilai-select').val();
        if (nilai !== '' && nilai !== null) {
            totalNilai += parseInt(nilai);
            filledKriteria++;
        }
        totalKriteria++;
    });
    
    // Skor Elemen = (Jumlah nilai kriteria / (Jumlah kriteria × 2)) × 100
    const skor = totalKriteria > 0 ? (totalNilai / (totalKriteria * 2)) * 100 : 0;
    
    // Update display
    $(`#skor-elemen-${elemenId}`).text(skor.toFixed(2) + '%');
    
    // Update badge color
    const badge = $(`#skor-elemen-${elemenId}`);
    badge.removeClass('text-primary text-success text-warning text-danger');
    if (skor >= 86) {
        badge.addClass('text-success');
    } else if (skor >= 71) {
        badge.addClass('text-info');
    } else if (skor >= 56) {
        badge.addClass('text-warning');
    } else {
        badge.addClass('text-danger');
    }
    
    return {
        skor: skor,
        filled: filledKriteria,
        total: totalKriteria
    };
}

/**
 * Calculate score per aspek
 */
function calculateAspekScore(aspekId) {
    let totalSkor = 0;
    let totalBobot = 0;
    let totalFilled = 0;
    let totalKriteria = 0;
    
    // Get all elemen in this aspek
    const elemenIds = Object.keys(aspekData[aspekId].elemen);
    
    elemenIds.forEach(elemenId => {
        const elemenInfo = aspekData[aspekId].elemen[elemenId];
        const elemenScore = calculateElemenScore(elemenId);
        
        // Skor Aspek = Σ(Skor Elemen × Bobot Elemen)
        totalSkor += elemenScore.skor * elemenInfo.bobot;
        totalBobot += elemenInfo.bobot;
        totalFilled += elemenScore.filled;
        totalKriteria += elemenScore.total;
    });
    
    // Update display
    $(`#skor-aspek-${aspekId}`).text(totalSkor.toFixed(2) + '%');
    
    // Kontribusi ke skor final = Skor Aspek × Bobot Aspek
    const kontribusi = totalSkor * aspekData[aspekId].bobot;
    $(`#kontribusi-aspek-${aspekId}`).text(kontribusi.toFixed(2) + '%');
    
    // Update badge
    const badge = $(`#badge-${aspekId}`);
    badge.text(totalFilled + '/' + totalKriteria);
    
    // Update check icon
    const checkIcon = $(`#check-${aspekId}`);
    if (totalFilled === totalKriteria && totalKriteria > 0) {
        checkIcon.removeClass('d-none').addClass('text-success');
        badge.removeClass('bg-secondary').addClass('bg-success');
    } else {
        checkIcon.addClass('d-none');
        badge.removeClass('bg-success').addClass('bg-secondary');
    }
    
    return {
        skor: totalSkor,
        kontribusi: kontribusi,
        filled: totalFilled,
        total: totalKriteria
    };
}

/**
 * Calculate final score
 */
function calculateFinalScore() {
    let totalKontribusi = 0;
    let totalFilled = 0;
    let totalKriteria = 0;
    
    // Skor Final = Σ(Skor Aspek × Bobot Aspek)
    Object.keys(aspekData).forEach(aspekId => {
        const aspekScore = calculateAspekScore(aspekId);
        totalKontribusi += aspekScore.kontribusi;
        totalFilled += aspekScore.filled;
        totalKriteria += aspekScore.total;
    });
    
    // Update display
    $('#skor-final').text(totalKontribusi.toFixed(2) + '%');
    
    // Update kategori
    const kategori = getKategoriFromScore(totalKontribusi);
    $('#kategori-final').text(kategori.nama);
    $('#kategori-final').removeClass('text-warning text-info text-primary text-danger');
    $('#kategori-final').addClass(kategori.class);
    
    // Update progress bar
    const progressBar = $('#progress-final');
    progressBar.css('width', totalKontribusi + '%');
    progressBar.text(totalKontribusi.toFixed(2) + '%');
    progressBar.removeClass('bg-danger bg-warning bg-info bg-success');
    progressBar.addClass(kategori.progressClass);
    
    return {
        skor: totalKontribusi,
        kategori: kategori,
        filled: totalFilled,
        total: totalKriteria
    };
}

/**
 * Calculate all scores
 */
function calculateAllScores() {
    const finalScore = calculateFinalScore();
    return finalScore;
}

/**
 * Get kategori from score
 */
function getKategoriFromScore(skor) {
    // Sesuai file acuan: RISK ASSESMENT OBJEK WISATA 2025.txt
    // 86% - 100%: Baik Sekali (Kategori Emas)
    // 71% - 85%: Baik (Kategori Perak)
    // 56% - 70%: Cukup (Kategori Perunggu)
    // < 55%: Kurang (Tindakan Pembinaan untuk Perbaikan)
    
    if (skor >= 86) {
        return {
            nama: 'Baik Sekali (Kategori Emas) 🥇',
            badgeText: 'EMAS',
            class: 'text-warning',
            progressClass: 'bg-warning'
        };
    } else if (skor >= 71) {
        return {
            nama: 'Baik (Kategori Perak) 🥈',
            badgeText: 'PERAK',
            class: 'text-info',
            progressClass: 'bg-info'
        };
    } else if (skor >= 56) {
        return {
            nama: 'Cukup (Kategori Perunggu) 🥉',
            badgeText: 'PERUNGGU',
            class: 'text-primary',
            progressClass: 'bg-primary'
        };
    } else {
        return {
            nama: 'Kurang (Tindakan Pembinaan untuk Perbaikan) ⚠️',
            badgeText: 'KURANG',
            class: 'text-danger',
            progressClass: 'bg-danger'
        };
    }
}

/**
 * Update progress
 */
function updateProgress() {
    const finalScore = calculateAllScores();
    const progress = finalScore.total > 0 ? (finalScore.filled / finalScore.total) * 100 : 0;
    
    $('#progress-bar').css('width', progress + '%');
    $('#progress-bar').text(progress.toFixed(0) + '%');
    $('#progress-percent').text(progress.toFixed(0) + '%');
    
    // Update progress text
    if (progress === 100) {
        $('#progress-text').html('<i class="fas fa-check-circle text-success me-1"></i>Semua kriteria sudah dinilai');
    } else {
        $('#progress-text').text(`${finalScore.filled} dari ${finalScore.total} kriteria sudah dinilai`);
    }
}

/**
 * Collect form data
 */
function collectFormData() {
    const formData = {
        objek_wisata_id: $('input[name="objek_wisata_id"]').val(),
        tanggal_penilaian: $('input[name="tanggal_penilaian"]').val(),
        nama_penilai: $('input[name="nama_penilai"]').val(),
        pangkat_nrp: $('input[name="pangkat_nrp"]').val(),
        details: []
    };
    
    // Collect all kriteria values
    $('tr[data-kriteria-id]').each(function() {
        const kriteriaId = $(this).data('kriteria-id');
        const nilai = $(this).find('.nilai-select').val();
        const temuan = $(this).find('.temuan-input').val();
        const rekomendasi = $(this).find('.rekomendasi-input').val();
        
        if (nilai !== '' && nilai !== null) {
            formData.details.push({
                kriteria_id: kriteriaId,
                nilai: parseInt(nilai),
                temuan: temuan,
                rekomendasi: rekomendasi
            });
        }
    });
    
    // Calculate and add scores
    const finalScore = calculateAllScores();
    formData.skor_final = finalScore.skor;
    formData.kategori = finalScore.kategori.nama;
    
    return formData;
}

/**
 * Validate form
 */
/**
 * Check if kriteria has uploaded files
 */
function hasReferensiFiles(kriteriaId) {
    const uploadedFiles = $(`#uploaded-files-${kriteriaId} .uploaded-file-item`);
    return uploadedFiles.length > 0;
}

/**
 * Validate form dengan validasi referensi dokumen
 */
function validateForm() {
    let isValid = true;
    let errors = [];
    
    // Check all kriteria harus dinilai
    $('tr[data-kriteria-id]').each(function() {
        const nilai = $(this).find('.nilai-select').val();
        if (!nilai || nilai === '') {
            isValid = false;
            const kriteriaDesc = $(this).find('td:eq(1)').text().trim();
            errors.push(`Kriteria "${kriteriaDesc.substring(0, 50)}..." belum dinilai`);
        } else if (nilai === '0' || nilai === '1') {
            // Check temuan dan rekomendasi wajib
            const temuan = $(this).find('.temuan-input').val().trim();
            const rekomendasi = $(this).find('.rekomendasi-input').val().trim();
            const kriteriaId = $(this).data('kriteria-id');
            
            if (!temuan) {
                isValid = false;
                errors.push(`Temuan wajib diisi untuk nilai ${nilai}`);
            }
            if (!rekomendasi) {
                isValid = false;
                errors.push(`Rekomendasi wajib diisi untuk nilai ${nilai}`);
            }
            
            // Warning jika tidak ada referensi dokumen (tidak wajib, tapi dianjurkan)
            if (!hasReferensiFiles(kriteriaId)) {
                const kriteriaDesc = $(this).find('td:eq(1)').text().trim();
                errors.push(`⚠️ Peringatan: Kriteria "${kriteriaDesc.substring(0, 50)}..." dengan nilai ${nilai} sebaiknya memiliki referensi dokumen`);
            }
        }
    });
    
    // Separate errors and warnings
    const criticalErrors = errors.filter(e => !e.includes('⚠️'));
    const warnings = errors.filter(e => e.includes('⚠️'));
    
    if (criticalErrors.length > 0 || warnings.length > 0) {
        let message = '';
        if (criticalErrors.length > 0) {
            isValid = false;
            message = 'Validasi gagal:\n' + criticalErrors.slice(0, 5).join('\n') + (criticalErrors.length > 5 ? '\n...' : '');
        }
        if (warnings.length > 0) {
            message += (message ? '\n\n' : '') + 'Peringatan:\n' + warnings.slice(0, 3).join('\n') + (warnings.length > 3 ? '\n...' : '');
        }
        
        showAlert(message, criticalErrors.length > 0 ? 'danger' : 'warning');
    }
    
    return isValid;
}

/**
 * Save draft
 */
function saveDraft() {
    if (!validateForm()) {
        return;
    }
    
    showLoading();
    
    const formData = collectFormData();
    formData.status = 'draft';
    
    // Check if penilaian exists
    const penilaianId = $('#penilaian-form').data('penilaian-id');
    
    let apiCall;
    if (penilaianId) {
        apiCall = PenilaianAPI.update(penilaianId, formData);
    } else {
        apiCall = PenilaianAPI.create(formData);
    }
    
    apiCall.done(function(response) {
        if (response.success) {
            showAlert('Draft berhasil disimpan', 'success');
            lastSaveTime = new Date();
            updateAutoSaveStatus();
            
            // Set penilaian ID jika baru
            if (!penilaianId && response.data && response.data.id) {
                $('#penilaian-form').data('penilaian-id', response.data.id);
            }
        } else {
            showAlert('Gagal menyimpan draft: ' + response.message, 'danger');
        }
        hideLoading();
    }).fail(function() {
        showAlert('Gagal menyimpan draft', 'danger');
        hideLoading();
    });
}

/**
 * Submit penilaian
 */
function submitPenilaian() {
    if (!validateForm()) {
        return;
    }
    
    if (!confirm('Apakah Anda yakin ingin menyelesaikan penilaian ini? Setelah disubmit, penilaian tidak dapat diubah.')) {
        return;
    }
    
    showLoading();
    
    const formData = collectFormData();
    formData.status = 'selesai';
    
    const penilaianId = $('#penilaian-form').data('penilaian-id');
    
    if (!penilaianId) {
        showAlert('Silakan simpan draft terlebih dahulu', 'warning');
        hideLoading();
        return;
    }
    
    PenilaianAPI.update(penilaianId, formData).done(function(response) {
        if (response.success) {
            showAlert('Penilaian berhasil disubmit!', 'success');
            setTimeout(function() {
                window.location.href = BASE_URL + 'pages/penilaian.php?id=' + penilaianId;
            }, 2000);
        } else {
            showAlert('Gagal submit penilaian: ' + response.message, 'danger');
        }
        hideLoading();
    }).fail(function() {
        showAlert('Gagal submit penilaian', 'danger');
        hideLoading();
    });
}

/**
 * Auto-save functions
 */
let autoSaveTimer = null;
function debounceAutoSave() {
    clearTimeout(autoSaveTimer);
    autoSaveTimer = setTimeout(function() {
        if (autoSaveEnabled) {
            saveDraft();
        }
    }, 3000); // Auto-save setelah 3 detik tidak ada perubahan
}

function toggleAutoSave() {
    autoSaveEnabled = !autoSaveEnabled;
    
    if (autoSaveEnabled) {
        $('#auto-save-status, #auto-save-status-mobile').text('ON').removeClass('text-danger').addClass('text-success');
        autoSaveInterval = setInterval(function() {
            saveDraft();
        }, 30000); // Auto-save setiap 30 detik
        showAlert('Auto-save diaktifkan', 'success');
    } else {
        $('#auto-save-status, #auto-save-status-mobile').text('OFF').removeClass('text-success').addClass('text-danger');
        if (autoSaveInterval) {
            clearInterval(autoSaveInterval);
            autoSaveInterval = null;
        }
        showAlert('Auto-save dinonaktifkan', 'info');
    }
}

function updateAutoSaveStatus() {
    if (lastSaveTime) {
        const timeAgo = Math.floor((new Date() - lastSaveTime) / 1000);
        if (timeAgo < 60) {
            $('#auto-save-status, #auto-save-status-mobile').text('ON - ' + timeAgo + 's lalu');
        }
    }
}

// Initialize auto-save status
$(document).ready(function() {
    $('#auto-save-status, #auto-save-status-mobile').text('OFF').addClass('text-danger');
    
    // Setup upload handlers
    setupUploadHandlers();
    
    // Load uploaded files if editing
    const penilaianId = $('#penilaian-form').data('penilaian-id');
    if (penilaianId) {
        loadUploadedFiles(penilaianId);
    }
});

/**
 * Setup upload file handlers
 */
function setupUploadHandlers() {
    // Toggle upload row visibility
    $('.nilai-select').on('change', function() {
        const kriteriaId = $(this).data('kriteria-id');
        const nilai = $(this).val();
        const uploadRow = $(`.upload-row[data-kriteria-id="${kriteriaId}"]`);
        
        // Show upload section if nilai is 0 or 1
        if (nilai === '0' || nilai === '1') {
            uploadRow.show();
        } else {
            uploadRow.hide();
        }
    });
    
    // Upload button click
    $(document).on('click', '.btn-upload-file', function() {
        const kriteriaId = $(this).data('kriteria-id');
        const fileInput = $(`#file-${kriteriaId}`)[0];
        
        if (!fileInput.files || fileInput.files.length === 0) {
            showAlert('Pilih file terlebih dahulu', 'warning');
            return;
        }
        
        uploadFile(kriteriaId, fileInput.files[0]);
    });
    
    // Load existing uploaded files
    const penilaianId = $('#penilaian-form').data('penilaian-id');
    if (penilaianId) {
        loadUploadedFiles(penilaianId);
    }
}

/**
 * Upload file
 */
function uploadFile(kriteriaId, file) {
    const penilaianId = $('#penilaian-form').data('penilaian-id');
    
    if (!penilaianId) {
        showAlert('Simpan draft terlebih dahulu sebelum upload file', 'warning');
        return;
    }
    
    const formData = new FormData();
    formData.append('file', file);
    formData.append('penilaian_id', penilaianId);
    formData.append('kriteria_id', kriteriaId);
    
    $.ajax({
        url: BASE_URL + 'api/upload.php',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            if (response.success) {
                showAlert('File berhasil diupload', 'success');
                addUploadedFileToList(kriteriaId, response.data);
                // Clear file input
                $(`#file-${kriteriaId}`).val('');
            } else {
                showAlert(response.message || 'Gagal upload file', 'danger');
            }
        },
        error: function(xhr) {
            try {
                const response = JSON.parse(xhr.responseText);
                showAlert(response.message || 'Gagal upload file', 'danger');
            } catch (e) {
                showAlert('Gagal upload file', 'danger');
            }
        }
    });
}

/**
 * Add uploaded file to list
 */
function addUploadedFileToList(kriteriaId, fileData) {
    const container = $(`#uploaded-files-${kriteriaId}`);
    const fileItem = $(`
        <div class="uploaded-file-item d-flex align-items-center justify-content-between p-2 mb-2 bg-white border rounded">
            <div class="flex-grow-1">
                <i class="fas fa-file me-2"></i>
                <a href="${BASE_URL}${fileData.path}" target="_blank" class="text-decoration-none">
                    ${fileData.original_name}
                </a>
                <small class="text-muted ms-2">(${formatFileSize(fileData.size)})</small>
            </div>
            <button type="button" class="btn btn-sm btn-danger btn-delete-file" data-file-id="${fileData.id}">
                <i class="fas fa-times"></i>
            </button>
        </div>
    `);
    container.append(fileItem);
}

/**
 * Load uploaded files
 */
function loadUploadedFiles(penilaianId) {
    $.ajax({
        url: BASE_URL + 'api/upload.php',
        type: 'GET',
        data: { penilaian_id: penilaianId },
        success: function(response) {
            if (response.success && response.data) {
                // Group files by kriteria_id
                const filesByKriteria = {};
                
                response.data.forEach(function(file) {
                    const kriteriaId = file.kriteria_id || 0;
                    if (!filesByKriteria[kriteriaId]) {
                        filesByKriteria[kriteriaId] = [];
                    }
                    filesByKriteria[kriteriaId].push(file);
                });
                
                // Display files
                Object.keys(filesByKriteria).forEach(function(kriteriaId) {
                    if (kriteriaId > 0) {
                        // Show upload row for this kriteria
                        $(`.upload-row[data-kriteria-id="${kriteriaId}"]`).show();
                        
                        // Add files to list
                        filesByKriteria[kriteriaId].forEach(function(file) {
                            addUploadedFileToList(kriteriaId, {
                                id: file.id,
                                original_name: file.original_name || file.nama_file,
                                path: file.path || file.path_file,
                                size: file.ukuran_file || 0
                            });
                        });
                    }
                });
            }
        },
        error: function() {
            // Silently fail - files might not exist yet
        }
    });
}

/**
 * Format file size
 */
function formatFileSize(bytes) {
    if (bytes === 0) return '0 B';
    const k = 1024;
    const sizes = ['B', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
}

