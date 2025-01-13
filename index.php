<?php 
session_start();
session_unset();
session_destroy();
?>
<!DOCTYPE html>
<html>
<head>
    <title>LOGIN</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- SweetAlert2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #2c3e50, #3498db);
            min-height: 100vh;
            display: flex;
            align-items: center;
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
        }
        .login-container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .login-header {
            background: #2c3e50;
            color: white;
            padding: 2rem;
            text-align: center;
            margin-bottom: 2rem;
        }
        .login-header h2 {
            margin: 0;
            font-weight: 600;
        }
        .login-form {
            padding: 2rem;
        }
        .form-label {
            font-weight: 500;
            color: #2c3e50;
        }
        .form-control {
            padding: 0.75rem 1rem;
            border-radius: 6px;
        }
        .btn-login {
            padding: 0.75rem 1rem;
            font-weight: 500;
            background: #3498db;
            border: none;
        }
        .btn-login:hover {
            background: #2980b9;
        }
        .role-info {
            background: #f8f9fa;
            border-radius: 6px;
            padding: 1rem;
            margin-top: 2rem;
        }
        .role-info h5 {
            color: #2c3e50;
            margin-bottom: 1rem;
        }
        .role-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            margin-bottom: 0.5rem;
            background: white;
            border: 1px solid #dee2e6;
            color: #2c3e50;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="login-container">
                    <div class="login-header">
                        <h2><i class="bi bi-mortarboard-fill me-2"></i>School Management System</h2>
                    </div>
                    
                    <div class="login-form">
                        <form action="login.php" method="post">
                            <?php if (isset($_GET['error'])) { ?>
                                <div id="sweet-alert-message" 
                                     data-message="<?= htmlspecialchars($_GET['error']); ?>" 
                                     data-type="error" 
                                     style="display: none;"></div>
                            <?php } ?>
                            
                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="bi bi-person-fill me-1"></i>Username
                                </label>
                                <input type="text" name="uname" class="form-control" placeholder="Enter username">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="bi bi-lock-fill me-1"></i>Password
                                </label>
                                <input type="password" name="password" class="form-control" placeholder="Enter password">
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-login">
                                    <i class="bi bi-box-arrow-in-right me-1"></i>Login
                                </button>
                            </div>
                        </form>

                        <div class="role-info">
                            <h5><i class="bi bi-info-circle-fill me-1"></i>Available Roles</h5>
                            
                            <div class="d-flex flex-column gap-2">
                                <div class="role-badge">
                                    <i class="bi bi-shield-lock-fill text-primary"></i>
                                    <div>
                                        <strong>Admin:</strong> username: admin, password: admin123
                                    </div>
                                </div>

                                <div class="role-badge">
                                    <i class="bi bi-person-workspace text-success"></i>
                                    <div>
                                        <strong>Teacher:</strong> username: albert, password: albert123
                                    </div>
                                </div>

                                <div class="role-badge">
                                    <i class="bi bi-mortarboard text-info"></i>
                                    <div>
                                        <strong>Student:</strong> username: mark, password: mark123
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="sweet-alert.js"></script>
</body>
</html>
