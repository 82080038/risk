<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/functions.php';
requireLogin();

$page_title = 'Panduan Menggunakan Aplikasi';
$show_navbar = true;
include __DIR__ . '/../includes/header.php';
?>

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-book me-2"></i>Panduan Menggunakan Aplikasi
                    </h4>
                </div>
                <div class="card-body">
                    <!-- Pendahuluan -->
                    <section class="mb-5">
                        <h5 class="text-primary mb-3">
                            <i class="fas fa-info-circle me-2"></i>Pendahuluan
                        </h5>
                        <p>
                            Aplikasi <strong>Risk Assessment Objek Wisata</strong> adalah sistem penilaian risiko 
                            untuk objek wisata yang dikembangkan untuk membantu proses penilaian dan evaluasi 
                            keamanan, keselamatan, kesehatan, dan infrastruktur objek wisata.
                        </p>
                        <p>
                            Aplikasi ini dirancang khusus untuk penggunaan di perangkat mobile (smartphone) 
                            namun tetap dapat diakses dengan baik melalui komputer atau laptop.
                        </p>
                    </section>

                    <!-- Menu Utama -->
                    <section class="mb-5">
                        <h5 class="text-primary mb-3">
                            <i class="fas fa-list me-2"></i>Menu Utama
                        </h5>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="card border-primary h-100">
                                    <div class="card-body">
                                        <h6 class="card-title text-primary">
                                            <i class="fas fa-home me-2"></i>Dashboard
                                        </h6>
                                        <p class="card-text small">
                                            Halaman utama yang menampilkan ringkasan statistik, grafik penilaian, 
                                            dan daftar objek wisata yang belum dinilai.
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card border-primary h-100">
                                    <div class="card-body">
                                        <h6 class="card-title text-primary">
                                            <i class="fas fa-map-marker-alt me-2"></i>Objek Wisata
                                        </h6>
                                        <p class="card-text small">
                                            <strong>Hanya untuk Admin:</strong> Kelola data objek wisata (tambah, 
                                            edit, hapus, cari). Data yang perlu diisi: nama, alamat, jenis, 
                                            wilayah hukum, dan keterangan.
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card border-primary h-100">
                                    <div class="card-body">
                                        <h6 class="card-title text-primary">
                                            <i class="fas fa-clipboard-check me-2"></i>Penilaian
                                        </h6>
                                        <p class="card-text small">
                                            Lakukan penilaian risiko objek wisata berdasarkan 6 aspek: 
                                            Infrastruktur, Keamanan, Keselamatan, Kesehatan, Sistem PAM, 
                                            dan Informasi. Setiap aspek memiliki elemen dan kriteria penilaian.
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card border-primary h-100">
                                    <div class="card-body">
                                        <h6 class="card-title text-primary">
                                            <i class="fas fa-file-pdf me-2"></i>Laporan
                                        </h6>
                                        <p class="card-text small">
                                            Lihat daftar laporan penilaian yang sudah selesai, unduh laporan PDF, 
                                            dan akses statistik detail per aspek, objek, dan personil.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- Cara Melakukan Penilaian -->
                    <section class="mb-5">
                        <h5 class="text-primary mb-3">
                            <i class="fas fa-clipboard-list me-2"></i>Cara Melakukan Penilaian
                        </h5>
                        <div class="accordion" id="accordionPenilaian">
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse1">
                                        <strong>1. Pilih Objek Wisata</strong>
                                    </button>
                                </h2>
                                <div id="collapse1" class="accordion-collapse collapse show" data-bs-parent="#accordionPenilaian">
                                    <div class="accordion-body">
                                        <p>Dari halaman <strong>Penilaian</strong>, klik tombol <strong>"Penilaian Baru"</strong> 
                                        atau pilih objek wisata yang ingin dinilai dari daftar.</p>
                                        <p class="mb-0"><small class="text-muted">
                                            <i class="fas fa-info-circle me-1"></i>
                                            Pastikan data objek wisata sudah lengkap sebelum melakukan penilaian.
                                        </small></p>
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse2">
                                        <strong>2. Isi Data Penilaian</strong>
                                    </button>
                                </h2>
                                <div id="collapse2" class="accordion-collapse collapse" data-bs-parent="#accordionPenilaian">
                                    <div class="accordion-body">
                                        <p>Form penilaian terbagi menjadi 6 aspek:</p>
                                        <ul>
                                            <li><strong>Infrastruktur</strong> - Penilaian kondisi infrastruktur objek wisata</li>
                                            <li><strong>Keamanan</strong> - Penilaian sistem keamanan</li>
                                            <li><strong>Keselamatan</strong> - Penilaian aspek keselamatan</li>
                                            <li><strong>Kesehatan</strong> - Penilaian fasilitas kesehatan</li>
                                            <li><strong>Sistem PAM</strong> - Penilaian sistem pengamanan</li>
                                            <li><strong>Informasi</strong> - Penilaian sistem informasi</li>
                                        </ul>
                                        <p class="mb-0">Setiap aspek memiliki beberapa elemen, dan setiap elemen memiliki kriteria penilaian.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse3">
                                        <strong>3. Berikan Nilai untuk Setiap Kriteria</strong>
                                    </button>
                                </h2>
                                <div id="collapse3" class="accordion-collapse collapse" data-bs-parent="#accordionPenilaian">
                                    <div class="accordion-body">
                                        <p>Untuk setiap kriteria, berikan nilai sesuai kondisi:</p>
                                        <ul>
                                            <li><strong>Nilai 2 (Baik)</strong> - Kriteria terpenuhi dengan baik</li>
                                            <li><strong>Nilai 1 (Kurang)</strong> - Kriteria terpenuhi sebagian atau kurang</li>
                                            <li><strong>Nilai 0 (Tidak Ada)</strong> - Kriteria tidak terpenuhi</li>
                                        </ul>
                                        <p class="mb-2"><strong>Catatan Penting:</strong></p>
                                        <ul>
                                            <li>Jika nilai <strong>0 atau 1</strong>, wajib mengisi <strong>Temuan</strong> dan <strong>Rekomendasi</strong></li>
                                            <li>Jika nilai <strong>0 atau 1</strong>, wajib mengunggah <strong>Referensi Dokumen/Foto</strong> sebagai bukti</li>
                                            <li>Form akan otomatis tersimpan saat Anda mengisi data</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse4">
                                        <strong>4. Unggah Referensi Dokumen/Foto</strong>
                                    </button>
                                </h2>
                                <div id="collapse4" class="accordion-collapse collapse" data-bs-parent="#accordionPenilaian">
                                    <div class="accordion-body">
                                        <p>Untuk kriteria dengan nilai 0 atau 1, unggah dokumen atau foto sebagai bukti:</p>
                                        <ul>
                                            <li>Format yang didukung: JPG, JPEG, PNG, PDF, DOC, DOCX</li>
                                            <li>Ukuran maksimal: 5 MB per file</li>
                                            <li>Dapat mengunggah beberapa file untuk satu kriteria</li>
                                            <li>File yang sudah diunggah dapat dihapus jika diperlukan</li>
                                        </ul>
                                        <p class="mb-0"><small class="text-danger">
                                            <i class="fas fa-exclamation-triangle me-1"></i>
                                            <strong>Peringatan:</strong> Pastikan semua kriteria dengan nilai 0 atau 1 sudah memiliki referensi dokumen/foto sebelum menyelesaikan penilaian.
                                        </small></p>
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse5">
                                        <strong>5. Simpan dan Selesaikan Penilaian</strong>
                                    </button>
                                </h2>
                                <div id="collapse5" class="accordion-collapse collapse" data-bs-parent="#accordionPenilaian">
                                    <div class="accordion-body">
                                        <p>Setelah semua aspek selesai dinilai:</p>
                                        <ol>
                                            <li>Klik tombol <strong>"Simpan Penilaian"</strong> untuk menyimpan sementara</li>
                                            <li>Klik tombol <strong>"Selesaikan Penilaian"</strong> untuk menyelesaikan dan mengunci penilaian</li>
                                            <li>Setelah selesai, skor akan dihitung otomatis dan kategori akan ditentukan:
                                                <ul class="mt-2">
                                                    <li><span class="badge bg-warning text-dark">Emas</span> - Baik Sekali (Skor: 85-100)</li>
                                                    <li><span class="badge bg-secondary">Perak</span> - Baik (Skor: 70-84)</li>
                                                    <li><span class="badge bg-danger">Perunggu</span> - Cukup (Skor: 55-69)</li>
                                                    <li><span class="badge bg-dark">Kurang</span> - Kurang (Skor: 0-54)</li>
                                                </ul>
                                            </li>
                                        </ol>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- Navigasi di Mobile -->
                    <section class="mb-5">
                        <h5 class="text-primary mb-3">
                            <i class="fas fa-mobile-alt me-2"></i>Navigasi di Perangkat Mobile
                        </h5>
                        <div class="alert alert-info">
                            <h6 class="alert-heading">
                                <i class="fas fa-info-circle me-2"></i>Tips untuk Penggunaan di Smartphone
                            </h6>
                            <ul class="mb-0">
                                <li>Aplikasi memiliki <strong>navigasi bawah</strong> khusus untuk perangkat mobile</li>
                                <li>Gunakan tombol <strong>"Sebelumnya"</strong> dan <strong>"Selanjutnya"</strong> untuk berpindah antar aspek dalam form penilaian</li>
                                <li>Pada layar kecil, data ditampilkan dalam bentuk <strong>card</strong> untuk memudahkan pembacaan</li>
                                <li>Pastikan koneksi internet stabil saat mengunggah dokumen/foto</li>
                            </ul>
                        </div>
                    </section>

                    <!-- Menghasilkan Laporan -->
                    <section class="mb-5">
                        <h5 class="text-primary mb-3">
                            <i class="fas fa-file-download me-2"></i>Menghasilkan Laporan
                        </h5>
                        <ol>
                            <li>Masuk ke menu <strong>Laporan</strong></li>
                            <li>Pilih penilaian yang ingin diunduh laporannya</li>
                            <li>Klik tombol <strong>"Unduh PDF"</strong></li>
                            <li>Laporan PDF akan berisi:
                                <ul>
                                    <li>Data objek wisata</li>
                                    <li>Detail penilaian per aspek dan elemen</li>
                                    <li>Skor akhir dan kategori</li>
                                    <li>Dokumen referensi yang diunggah</li>
                                    <li>Tanda tangan penilai dan Kasat Pamobvit</li>
                                </ul>
                            </li>
                        </ol>
                    </section>

                    <!-- Export/Import Data -->
                    <section class="mb-5">
                        <h5 class="text-primary mb-3">
                            <i class="fas fa-file-export me-2"></i>Export/Import Data
                        </h5>
                        <p><strong>Hanya untuk Admin:</strong></p>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card border-success mb-3">
                                    <div class="card-header bg-success text-white">
                                        <strong>Export Data</strong>
                                    </div>
                                    <div class="card-body">
                                        <p class="mb-0">Ekspor data objek wisata ke file CSV untuk backup atau analisis di Excel.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card border-info mb-3">
                                    <div class="card-header bg-info text-white">
                                        <strong>Import Data</strong>
                                    </div>
                                    <div class="card-body">
                                        <p class="mb-0">Impor data objek wisata dari file CSV. Pastikan format file sesuai dengan template yang tersedia.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- Tips dan Trik -->
                    <section class="mb-5">
                        <h5 class="text-primary mb-3">
                            <i class="fas fa-lightbulb me-2"></i>Tips dan Trik
                        </h5>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="card border-warning">
                                    <div class="card-body">
                                        <h6 class="card-title text-warning">
                                            <i class="fas fa-save me-2"></i>Auto-Save
                                        </h6>
                                        <p class="card-text small mb-0">
                                            Form penilaian otomatis tersimpan setiap beberapa detik. 
                                            Anda tidak perlu khawatir kehilangan data jika terjadi gangguan koneksi.
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card border-warning">
                                    <div class="card-body">
                                        <h6 class="card-title text-warning">
                                            <i class="fas fa-search me-2"></i>Pencarian
                                        </h6>
                                        <p class="card-text small mb-0">
                                            Gunakan fitur pencarian untuk menemukan objek wisata atau penilaian 
                                            dengan cepat. Pencarian dapat dilakukan berdasarkan nama, alamat, atau jenis.
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card border-warning">
                                    <div class="card-body">
                                        <h6 class="card-title text-warning">
                                            <i class="fas fa-edit me-2"></i>Edit Penilaian
                                        </h6>
                                        <p class="card-text small mb-0">
                                            Penilaian yang masih dalam status <strong>"Draft"</strong> dapat diedit. 
                                            Setelah status <strong>"Selesai"</strong>, penilaian tidak dapat diubah lagi.
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card border-warning">
                                    <div class="card-body">
                                        <h6 class="card-title text-warning">
                                            <i class="fas fa-filter me-2"></i>Filter dan Statistik
                                        </h6>
                                        <p class="card-text small mb-0">
                                            Gunakan filter di halaman penilaian dan laporan untuk melihat data 
                                            berdasarkan kategori, aspek, atau periode tertentu.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- Bantuan -->
                    <section class="mb-4">
                        <h5 class="text-primary mb-3">
                            <i class="fas fa-question-circle me-2"></i>Butuh Bantuan?
                        </h5>
                        <div class="alert alert-light border">
                            <p class="mb-2">Jika Anda mengalami kesulitan atau memiliki pertanyaan:</p>
                            <ul class="mb-0">
                                <li>Pastikan Anda sudah membaca panduan ini dengan lengkap</li>
                                <li>Periksa koneksi internet Anda</li>
                                <li>Pastikan browser Anda mendukung fitur modern (Chrome, Firefox, Safari, atau Edge terbaru)</li>
                                <li>Hubungi administrator sistem jika masalah masih terjadi</li>
                            </ul>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>

