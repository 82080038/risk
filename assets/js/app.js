/**
 * Custom JavaScript
 * Risk Assessment Objek Wisata
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

// Wait for jQuery ready
(function($) {
    'use strict';
    
    $(document).ready(function() {
    // Auto-dismiss alerts after 5 seconds
    setTimeout(function() {
        $('.alert').fadeOut('slow', function() {
            $(this).remove();
        });
    }, 5000);
    
    // Confirm delete actions
    $('.btn-delete, .delete-confirm').on('click', function(e) {
        if (!confirm('Apakah Anda yakin ingin menghapus data ini?')) {
            e.preventDefault();
            return false;
        }
    });
    
    // Form validation
    $('form').on('submit', function(e) {
        var form = $(this);
        var requiredFields = form.find('[required]');
        var isValid = true;
        
        requiredFields.each(function() {
            var field = $(this);
            if (!field.val() || field.val().trim() === '') {
                field.addClass('is-invalid');
                isValid = false;
            } else {
                field.removeClass('is-invalid');
            }
        });
        
        if (!isValid) {
            e.preventDefault();
            alert('Mohon lengkapi semua field yang wajib diisi!');
            return false;
        }
    });
    
    // Remove invalid class on input
    $('input, textarea, select').on('input change', function() {
        $(this).removeClass('is-invalid');
    });
    
    // Loading state for buttons
    $('form').on('submit', function() {
        var submitBtn = $(this).find('button[type="submit"]');
        if (submitBtn.length) {
            submitBtn.prop('disabled', true);
            submitBtn.html('<span class="spinner-border spinner-border-sm me-2"></span>Memproses...');
        }
    });
    
    // Tooltip initialization
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // Popover initialization
    var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
    var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl);
    });
    });
    
})(jQuery || window.jQuery || window.$);

// AJAX Helper Functions (global functions, outside IIFE)
function showLoading() {
    if (typeof $ !== 'undefined') {
        $('body').append('<div id="loading-overlay" style="position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.5);z-index:9999;display:flex;align-items:center;justify-content:center;"><div class="spinner-border text-light" role="status"><span class="visually-hidden">Loading...</span></div></div>');
    }
}

function hideLoading() {
    if (typeof $ !== 'undefined') {
        $('#loading-overlay').remove();
    }
}

function showAlert(message, type) {
    type = type || 'success';
    if (typeof $ === 'undefined') {
        alert(message);
        return;
    }
    
    var alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
    var icon = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';
    
    var alertHtml = '<div class="alert ' + alertClass + ' alert-dismissible fade show" role="alert">' +
        '<i class="fas ' + icon + ' me-2"></i>' + message +
        '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>' +
        '</div>';
    
    $('.container-fluid, .container').first().prepend(alertHtml);
    
    setTimeout(function() {
        $('.alert').fadeOut('slow', function() {
            $(this).remove();
        });
    }, 5000);
}

