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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const messageDiv = document.getElementById('sweet-alert-message');
            if (messageDiv) {
                const message = messageDiv.getAttribute('data-message');
                const type = messageDiv.getAttribute('data-type');
                
                // Check if SweetAlert2 is loaded
                const showAlert = function() {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            title: type === 'success' ? 'Success!' : 'Error!',
                            text: message,
                            icon: type,
                            confirmButtonText: 'OK'
                        });
                    } else {
                        // If SweetAlert2 isn't loaded yet, wait and try again
                        setTimeout(showAlert, 100);
                    }
                };
                
                showAlert();
            }
        });
    </script>

    <!-- Bootstrap alert (fallback) -->
    <div class="alert alert-<?= ($type === 'success') ? 'success' : 'danger' ?> alert-dismissible fade show" role="alert">
        <strong><?= ($type === 'success') ? 'Success!' : 'Error!' ?></strong> <?= $message; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>

<?php 
    unset($_SESSION['message']);
    endif;
?>
