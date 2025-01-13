<?php
    if(isset($_SESSION['message'])) :
        $message = $_SESSION['message'];
        $type = (strpos(strtolower($message), 'successfully') !== false) ? 'success' : 'error';
?>
    <!-- Hidden element for SweetAlert2 -->
    <div id="sweet-alert-message" 
         data-message="<?= htmlspecialchars($message); ?>" 
         data-type="<?= $type; ?>" 
         style="display: none;"></div>

    <!-- Bootstrap alert (fallback) -->
    <div class="alert alert-<?= ($type === 'success') ? 'success' : 'danger' ?> alert-dismissible fade show" role="alert">
        <strong><?= ($type === 'success') ? 'Success!' : 'Error!' ?></strong> <?= $message; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>

<?php 
    unset($_SESSION['message']);
    endif;
?>