/**
 * Dashboard Specific JavaScript
 * Risk Assessment Objek Wisata
 * Render dinamis untuk dashboard
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

// Dashboard initialization
$(document).ready(function() {
    console.log('Dashboard script loaded');
    
    // Check if functions are available
    if (typeof renderDashboardStats === 'function') {
        console.log('renderDashboardStats available');
        renderDashboardStats();
    } else {
        console.warn('renderDashboardStats not available');
    }
    
    if (typeof renderPenilaianTerbaru === 'function') {
        console.log('renderPenilaianTerbaru available');
        renderPenilaianTerbaru();
    } else {
        console.warn('renderPenilaianTerbaru not available');
    }
    
    if (typeof renderObjekBelumDinilai === 'function') {
        console.log('renderObjekBelumDinilai available');
        renderObjekBelumDinilai();
    } else {
        console.warn('renderObjekBelumDinilai not available');
    }
    
    // Auto-refresh setiap 30 detik
    setInterval(function() {
        if (typeof renderDashboardStats === 'function') {
            renderDashboardStats();
        }
        if (typeof renderPenilaianTerbaru === 'function') {
            renderPenilaianTerbaru();
        }
        if (typeof renderObjekBelumDinilai === 'function') {
            renderObjekBelumDinilai();
        }
    }, 30000); // 30 detik
});

