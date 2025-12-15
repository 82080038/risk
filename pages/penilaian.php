<?php
// Start session dulu sebelum require functions
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/functions.php';
requireLogin();

$current_user = getCurrentUser();
$conn = getDBConnection();

$action = $_GET['action'] ?? 'list';
$id = $_GET['id'] ?? null;
$objek_id = $_GET['objek_id'] ?? null;

$page_title = 'Penilaian';
$show_navbar = true;
include __DIR__ . '/../includes/header.php';
?>

<div class="container-fluid mt-4">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="mb-0">
                <i class="fas fa-clipboard-check me-2"></i>Penilaian
            </h2>
            <p class="text-muted">Manajemen penilaian risiko objek wisata</p>
        </div>
    </div>
    
    <?php if ($action == 'list'): ?>
        <!-- Redirect to list page -->
        <?php
        header('Location: ' . BASE_URL . 'pages/penilaian_list.php');
        exit;
        ?>
        
    <?php elseif ($action == 'new'): ?>
        <!-- Redirect to form -->
        <?php
        header('Location: ' . BASE_URL . 'pages/penilaian_form.php?action=new' . ($objek_id ? '&objek_id=' . $objek_id : ''));
        exit;
        ?>
        
    <?php else: ?>
        <!-- Detail Penilaian -->
        <div class="card">
            <div class="card-body">
                <p class="text-muted">Detail penilaian sedang dalam pengembangan</p>
                <a href="<?php echo BASE_URL; ?>pages/dashboard.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Kembali ke Dashboard
                </a>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php 
$conn->close();
include __DIR__ . '/../includes/footer.php'; 
?>

