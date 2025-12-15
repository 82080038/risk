    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- jQuery (MUST be loaded first) -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    
    <!-- Define BASE_URL untuk JavaScript -->
    <script>
        // Define BASE_URL untuk JavaScript
        const BASE_URL = '<?php echo BASE_URL; ?>';
        
        // Ensure jQuery is available as $
        if (typeof jQuery !== 'undefined' && typeof $ === 'undefined') {
            window.$ = jQuery;
        }
    </script>
    
    <!-- Custom JS (loaded after jQuery) -->
    <script src="<?php echo BASE_URL; ?>assets/js/app.js"></script>
    <script src="<?php echo BASE_URL; ?>assets/js/api.js"></script>
    <script src="<?php echo BASE_URL; ?>assets/js/dynamic.js"></script>
    
    <?php if (isset($additional_js)): ?>
        <?php foreach ($additional_js as $js): ?>
            <script src="<?php echo (strpos($js, 'http') === 0 || strpos($js, '//') === 0) ? $js : BASE_URL . $js; ?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>
    
    <!-- Copyright Footer -->
    <footer class="footer-copyright mt-4 py-3 bg-light border-top">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 text-center">
                    <p class="mb-1 small text-muted">
                        <i class="fas fa-copyright me-1"></i>
                        Diciptakan oleh <strong>AIPDA PATRI SIHALOHO, S.H., CPM</strong>
                    </p>
                    <p class="mb-0 small text-muted">
                        <i class="fas fa-phone me-1"></i>
                        Phone: <a href="tel:081265511982" class="text-decoration-none">081265511982</a>
                        <span class="mx-2">|</span>
                        <span>&copy; 2024</span>
                    </p>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>

