<?php
/**
 * Setup Page untuk Railway
 * Halaman ini membantu setup database di Railway
 */

// Disable session untuk halaman setup
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$page_title = 'Setup Database Railway';
include __DIR__ . '/../includes/header.php';
?>

<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="fas fa-database me-2"></i>Setup Database untuk Railway</h4>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <h5><i class="fas fa-info-circle me-2"></i>Instruksi Setup Database di Railway</h5>
                        <p>Aplikasi ini memerlukan database untuk berfungsi. Ikuti langkah-langkah berikut:</p>
                    </div>

                    <h5 class="mt-4">Langkah 1: Tambahkan Database Service di Railway</h5>
                    <ol>
                        <li>Buka <strong>Railway Dashboard</strong>: <a href="https://railway.app" target="_blank">https://railway.app</a></li>
                        <li>Pilih <strong>Project</strong> Anda</li>
                        <li>Klik <strong>"New +"</strong> → <strong>"Database"</strong></li>
                        <li>Pilih <strong>"Add MySQL"</strong> (atau <strong>"Add PostgreSQL"</strong> jika prefer PostgreSQL)</li>
                        <li>Railway akan otomatis membuat database service</li>
                    </ol>

                    <h5 class="mt-4">Langkah 2: Link Database ke Web Service</h5>
                    <ol>
                        <li>Klik <strong>Database service</strong> yang baru dibuat</li>
                        <li>Klik tab <strong>"Variables"</strong></li>
                        <li>Catat semua environment variables yang tersedia:
                            <ul>
                                <li><code>MYSQL_HOST</code> (atau <code>PGHOST</code> untuk PostgreSQL)</li>
                                <li><code>MYSQL_PORT</code> (atau <code>PGPORT</code>)</li>
                                <li><code>MYSQL_USER</code> (atau <code>PGUSER</code>)</li>
                                <li><code>MYSQL_PASSWORD</code> (atau <code>PGPASSWORD</code>)</li>
                                <li><code>MYSQL_DATABASE</code> (atau <code>PGDATABASE</code>)</li>
                            </ul>
                        </li>
                        <li>Klik <strong>Web Service</strong> → tab <strong>"Variables"</strong></li>
                        <li>Railway biasanya otomatis link, tapi jika tidak, tambahkan manual dengan format:
                            <ul>
                                <li><code>MYSQL_HOST=${{MySQL.MYSQLHOST}}</code></li>
                                <li><code>MYSQL_PORT=${{MySQL.MYSQLPORT}}</code></li>
                                <li><code>MYSQL_USER=${{MySQL.MYSQLUSER}}</code></li>
                                <li><code>MYSQL_PASSWORD=${{MySQL.MYSQLPASSWORD}}</code></li>
                                <li><code>MYSQL_DATABASE=${{MySQL.MYSQLDATABASE}}</code></li>
                            </ul>
                        </li>
                    </ol>

                    <h5 class="mt-4">Langkah 3: Set BASE_URL</h5>
                    <ol>
                        <li>Di <strong>Web Service</strong> → tab <strong>"Variables"</strong></li>
                        <li>Tambahkan:
                            <ul>
                                <li><code>BASE_URL=https://your-app-name.up.railway.app/</code></li>
                                <li><code>APP_ENV=production</code></li>
                            </ul>
                        </li>
                        <li><strong>Ganti</strong> <code>your-app-name</code> dengan nama service Anda</li>
                    </ol>

                    <h5 class="mt-4">Langkah 4: Import Database Schema</h5>
                    <ol>
                        <li>Klik <strong>Database service</strong> → tab <strong>"Data"</strong></li>
                        <li>Klik <strong>"Query"</strong> untuk membuka SQL editor</li>
                        <li>Copy isi file <code>sql/database.sql</code> dari repository</li>
                        <li>Paste dan execute di SQL editor</li>
                        <li>Ulangi untuk <code>sql/master_data.sql</code> dan <code>sql/data_personil.sql</code></li>
                    </ol>

                    <h5 class="mt-4">Langkah 5: Restart Web Service</h5>
                    <ol>
                        <li>Klik <strong>Web Service</strong> → tab <strong>"Deployments"</strong></li>
                        <li>Klik <strong>"Redeploy"</strong> untuk restart service</li>
                        <li>Tunggu sampai deploy selesai (2-3 menit)</li>
                    </ol>

                    <div class="alert alert-warning mt-4">
                        <h5><i class="fas fa-exclamation-triangle me-2"></i>Catatan Penting</h5>
                        <ul>
                            <li>Pastikan database service sudah <strong>di-link</strong> ke web service</li>
                            <li>Environment variables harus di-set di <strong>Web Service</strong>, bukan di Database service</li>
                            <li>Jika menggunakan PostgreSQL, pastikan extension <code>pdo_pgsql</code> sudah terinstall (sudah ada di Dockerfile)</li>
                        </ul>
                    </div>

                    <div class="mt-4">
                        <a href="<?php echo BASE_URL; ?>debug.php" class="btn btn-info me-2">
                            <i class="fas fa-bug me-1"></i>Cek Debug Info
                        </a>
                        <a href="<?php echo BASE_URL; ?>" class="btn btn-primary">
                            <i class="fas fa-home me-1"></i>Kembali ke Home
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>

