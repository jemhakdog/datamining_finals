</div> <!-- End of main-content -->
    </div> <!-- End of wrapper -->

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="sweet-alert.js"></script>
    <script>
        // Mobile sidebar toggle
        document.querySelector('.navbar-toggler').addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('show');
        });

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(event) {
            const sidebar = document.querySelector('.sidebar');
            const toggler = document.querySelector('.navbar-toggler');
            if (!sidebar.contains(event.target) && !toggler.contains(event.target)) {
                sidebar.classList.remove('show');
            }
        });
    </script>
</body>
</html>