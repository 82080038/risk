/**
 * Dashboard Charts
 * Risk Assessment Objek Wisata
 * Menggunakan Chart.js
 */

// Pastikan Chart.js sudah loaded
if (typeof Chart === 'undefined') {
    console.error('Chart.js is required but not loaded');
} else {
    console.log('Chart.js loaded');
}

let kategoriChart = null;
let aspekChart = null;

/**
 * Initialize charts
 */
function initDashboardCharts() {
    // Load data untuk charts
    loadChartData();
}

/**
 * Load chart data
 */
function loadChartData() {
    // Get data dari API
    $.when(
        PenilaianAPI.getAll({ status: 'selesai' }),
        DashboardAPI.getStats()
    ).done(function(penilaianResponse, statsResponse) {
        // Use helper function (if available) or extract manually
        let penilaianData = [];
        if (typeof extractArrayFromResponse === 'function') {
            penilaianData = extractArrayFromResponse(penilaianResponse);
        } else {
            // Fallback manual extraction
            if (penilaianResponse[0] && penilaianResponse[0].success) {
                if (Array.isArray(penilaianResponse[0].data)) {
                    penilaianData = penilaianResponse[0].data;
                } else if (penilaianResponse[0].data && Array.isArray(penilaianResponse[0].data.data)) {
                    penilaianData = penilaianResponse[0].data.data;
                }
            }
        }
        
        const statsData = (statsResponse[0] && statsResponse[0].data) ? statsResponse[0].data : {};
        
        // Render kategori chart
        renderKategoriChart(penilaianData);
        
        // Render aspek chart (jika ada data)
        if (penilaianData.length > 0) {
            renderAspekChart(penilaianData);
        }
    });
}

/**
 * Render Kategori Chart (Pie Chart)
 */
function renderKategoriChart(penilaianData) {
    const ctx = document.getElementById('kategoriChart');
    if (!ctx) return;
    
    // Count by kategori
    // Sesuai file acuan: RISK ASSESMENT OBJEK WISATA 2025.txt
    const kategoriCount = {
        'Baik Sekali (Kategori Emas)': 0,
        'Baik (Kategori Perak)': 0,
        'Cukup (Kategori Perunggu)': 0,
        'Kurang (Tindakan Pembinaan untuk Perbaikan)': 0
    };
    
    penilaianData.forEach(function(p) {
        const kategori = p.kategori || '';
        if (kategori.includes('Emas') || kategori.includes('Baik Sekali')) {
            kategoriCount['Baik Sekali (Kategori Emas)']++;
        } else if (kategori.includes('Perak') || (kategori.includes('Baik') && !kategori.includes('Sekali'))) {
            kategoriCount['Baik (Kategori Perak)']++;
        } else if (kategori.includes('Perunggu') || kategori.includes('Cukup')) {
            kategoriCount['Cukup (Kategori Perunggu)']++;
        } else if (kategori.includes('Kurang') || kategori.includes('Tindakan Pembinaan')) {
            kategoriCount['Kurang (Tindakan Pembinaan untuk Perbaikan)']++;
        }
    });
    
    // Destroy existing chart
    if (kategoriChart) {
        kategoriChart.destroy();
    }
    
    kategoriChart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: Object.keys(kategoriCount),
            datasets: [{
                data: Object.values(kategoriCount),
                backgroundColor: [
                    '#FFD700', // Emas
                    '#C0C0C0', // Perak
                    '#CD7F32', // Perunggu
                    '#DC3545'  // Kurang
                ],
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const label = context.label || '';
                            const value = context.parsed || 0;
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                            return label + ': ' + value + ' (' + percentage + '%)';
                        }
                    }
                }
            }
        }
    });
}

/**
 * Render Aspek Chart (Bar Chart)
 */
function renderAspekChart(penilaianData) {
    const ctx = document.getElementById('aspekChart');
    if (!ctx) return;
    
    // Get average score per aspek (simplified - would need detailed data)
    // For now, show distribution of final scores
    const scoreRanges = {
        '86-100%': 0,
        '71-85%': 0,
        '56-70%': 0,
        '< 56%': 0
    };
    
    penilaianData.forEach(function(p) {
        const skor = parseFloat(p.skor_final) || 0;
        if (skor >= 86) {
            scoreRanges['86-100%']++;
        } else if (skor >= 71) {
            scoreRanges['71-85%']++;
        } else if (skor >= 56) {
            scoreRanges['56-70%']++;
        } else {
            scoreRanges['< 56%']++;
        }
    });
    
    // Destroy existing chart
    if (aspekChart) {
        aspekChart.destroy();
    }
    
    aspekChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: Object.keys(scoreRanges),
            datasets: [{
                label: 'Jumlah Penilaian',
                data: Object.values(scoreRanges),
                backgroundColor: [
                    '#FFD700',
                    '#C0C0C0',
                    '#CD7F32',
                    '#DC3545'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
}

// Initialize on document ready
$(document).ready(function() {
    // Check if we're on dashboard page
    if (window.location.pathname.includes('dashboard.php')) {
        // Wait for Chart.js to load
        if (typeof Chart !== 'undefined') {
            initDashboardCharts();
        } else {
            // Load Chart.js if not loaded
            const script = document.createElement('script');
            script.src = 'https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js';
            script.onload = function() {
                initDashboardCharts();
            };
            document.head.appendChild(script);
        }
    }
});

