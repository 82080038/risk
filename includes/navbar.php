<?php
$current_user = getCurrentUser();
$current_page = basename($_SERVER['PHP_SELF']);
?>
<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold" href="<?php echo BASE_URL; ?>pages/dashboard.php">
            <i class="fas fa-shield-alt me-2 text-primary"></i>
            <span class="d-none d-sm-inline">Risk Assessment</span>
            <span class="d-sm-none">RA</span>
        </a>
        
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link <?php echo ($current_page == 'dashboard.php') ? 'active fw-bold' : ''; ?>" 
                       href="<?php echo BASE_URL; ?>pages/dashboard.php">
                        <i class="fas fa-home me-1"></i> <span class="d-none d-md-inline">Dashboard</span>
                    </a>
                </li>
                
                <?php if ($current_user && $current_user['role'] == 'admin'): ?>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($current_page == 'objek_wisata.php') ? 'active fw-bold' : ''; ?>" 
                       href="<?php echo BASE_URL; ?>pages/objek_wisata.php">
                        <i class="fas fa-map-marker-alt me-1"></i> <span class="d-none d-md-inline">Objek Wisata</span>
                    </a>
                </li>
                <?php endif; ?>
                
                <li class="nav-item">
                    <a class="nav-link <?php echo (in_array($current_page, ['penilaian.php', 'penilaian_form.php', 'penilaian_list.php', 'penilaian_detail.php'])) ? 'active fw-bold' : ''; ?>" 
                       href="<?php echo BASE_URL; ?>pages/penilaian_list.php">
                        <i class="fas fa-clipboard-check me-1"></i> <span class="d-none d-md-inline">Penilaian</span>
                    </a>
                </li>
                
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle <?php echo (in_array($current_page, ['laporan.php', 'laporan_statistik.php'])) ? 'active fw-bold' : ''; ?>" 
                       href="#" id="laporanDropdown" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-file-pdf me-1"></i> <span class="d-none d-md-inline">Laporan</span>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>pages/laporan.php">
                            <i class="fas fa-file-pdf me-2"></i> Daftar Laporan
                        </a></li>
                        <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>pages/laporan_statistik.php">
                            <i class="fas fa-chart-line me-2"></i> Statistik Detail
                        </a></li>
                    </ul>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link <?php echo ($current_page == 'panduan.php') ? 'active fw-bold' : ''; ?>" 
                       href="<?php echo BASE_URL; ?>pages/panduan.php">
                        <i class="fas fa-book me-1"></i> <span class="d-none d-md-inline">Panduan</span>
                    </a>
                </li>
                
                <?php if ($current_user && $current_user['role'] == 'admin'): ?>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-cog me-1"></i> Pengaturan
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>pages/export_import.php">
                            <i class="fas fa-file-export me-2"></i> Export/Import Data
                        </a></li>
                        <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>pages/personil.php">
                            <i class="fas fa-users me-2"></i> Data Personil
                        </a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>pages/profile.php">
                            <i class="fas fa-user me-2"></i> Profile
                        </a></li>
                    </ul>
                </li>
                <?php endif; ?>
            </ul>
            
            <ul class="navbar-nav">
                <?php if ($current_user): ?>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-user-circle me-1"></i>
                        <span class="d-none d-md-inline"><?php echo htmlspecialchars($current_user['nama']); ?></span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>pages/profile.php">
                            <i class="fas fa-user me-2"></i> Profile
                        </a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="<?php echo BASE_URL; ?>logout.php">
                            <i class="fas fa-sign-out-alt me-2"></i> Logout
                        </a></li>
                    </ul>
                </li>
                <?php else: ?>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo BASE_URL; ?>pages/login.php">
                        <i class="fas fa-sign-in-alt me-1"></i> <span class="d-none d-md-inline">Login</span>
                    </a>
                </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<!-- Bottom Navigation - Mobile Only -->
<nav class="bottom-nav d-lg-none">
    <ul class="nav">
        <li class="nav-item">
            <a class="nav-link <?php echo ($current_page == 'dashboard.php') ? 'active' : ''; ?>" 
               href="<?php echo BASE_URL; ?>pages/dashboard.php">
                <i class="fas fa-home"></i>
                <span>Home</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php echo (in_array($current_page, ['penilaian.php', 'penilaian_form.php', 'penilaian_list.php', 'penilaian_detail.php'])) ? 'active' : ''; ?>" 
               href="<?php echo BASE_URL; ?>pages/penilaian_list.php">
                <i class="fas fa-clipboard-check"></i>
                <span>Penilaian</span>
            </a>
        </li>
        <?php if ($current_user && $current_user['role'] == 'admin'): ?>
        <li class="nav-item">
            <a class="nav-link <?php echo ($current_page == 'objek_wisata.php') ? 'active' : ''; ?>" 
               href="<?php echo BASE_URL; ?>pages/objek_wisata.php">
                <i class="fas fa-map-marker-alt"></i>
                <span>Objek</span>
            </a>
        </li>
        <?php endif; ?>
        <li class="nav-item">
            <a class="nav-link <?php echo (in_array($current_page, ['laporan.php', 'laporan_statistik.php'])) ? 'active' : ''; ?>" 
               href="<?php echo BASE_URL; ?>pages/laporan.php">
                <i class="fas fa-file-pdf"></i>
                <span>Laporan</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php echo ($current_page == 'panduan.php') ? 'active' : ''; ?>" 
               href="<?php echo BASE_URL; ?>pages/panduan.php">
                <i class="fas fa-book"></i>
                <span>Panduan</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="<?php echo BASE_URL; ?>logout.php">
                <i class="fas fa-sign-out-alt"></i>
                <span>Logout</span>
            </a>
        </li>
    </ul>
</nav>

