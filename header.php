<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- SweetAlert2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">

    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #34495e;
            --accent-color: #3498db;
            --sidebar-width: 280px;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            background-color: #f8f9fa;
            min-height: 100vh;
        }

        /* Layout */
        .wrapper {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar Styles */
        .sidebar {
            width: var(--sidebar-width);
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            position: fixed;
            height: 100vh;
            left: 0;
            top: 0;
            overflow-y: auto;
            transition: transform 0.3s ease;
            z-index: 1000;
            display: flex;
            flex-direction: column;
        }

        .sidebar-header {
            padding: 2rem 1.5rem;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar-header h1 {
            font-size: 1.5rem;
            margin: 0;
            color: white;
        }

        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8) !important;
            padding: 1rem 1.5rem !important;
            display: flex !important;
            align-items: center;
            gap: 0.75rem;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
        }

        .sidebar .nav-link:hover {
            color: white !important;
            background-color: rgba(255, 255, 255, 0.1);
            border-left-color: var(--accent-color);
        }

        .sidebar .nav-link.active {
            color: white !important;
            background-color: rgba(255, 255, 255, 0.1);
            border-left-color: var(--accent-color);
        }

        .sidebar .nav-link i {
            font-size: 1.2rem;
            width: 24px;
            text-align: center;
        }

        /* Main Content */
        .main-content {
            flex: 1;
            margin-left: var(--sidebar-width);
            padding: 2rem;
            transition: margin-left 0.3s ease;
            min-height: 100vh;
            background-color: #f8f9fa;
        }

        /* User Info */
        .user-info {
            padding: 1rem 1.5rem;
            background-color: rgba(255, 255, 255, 0.1);
            margin-top: auto;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .user-info .badge {
            font-size: 0.8rem;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .navbar-toggler {
                display: block;
                position: fixed;
                top: 1rem;
                left: 1rem;
                z-index: 1001;
                background-color: white;
            }
        }

        /* Card Styles */
        .card {
            border: none;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }

        .card:hover {
            transform: translateY(-2px);
        }

        .card-header {
            background-color: white;
            border-bottom: 2px solid #f0f0f0;
            padding: 1.5rem;
        }

        .card-header h4 {
            margin: 0;
            font-weight: 600;
            color: var(--primary-color);
        }

        /* Table Styles */
        .table {
            margin-bottom: 0;
        }

        .table th {
            font-weight: 600;
            color: var(--primary-color);
            border-bottom: 2px solid #f0f0f0;
        }

        .table td {
            vertical-align: middle;
        }

        /* Button Styles */
        .btn {
            padding: 0.5rem 1rem;
            font-weight: 500;
            border-radius: 6px;
        }

        .btn-primary {
            background-color: var(--accent-color);
            border-color: var(--accent-color);
        }

        .btn-primary:hover {
            background-color: #2980b9;
            border-color: #2980b9;
        }

        /* Utility Classes */
        .mb-4 {
            margin-bottom: 1.5rem !important;
        }

        .py-4 {
            padding-top: 1.5rem !important;
            padding-bottom: 1.5rem !important;
        }
    </style>

    <title><?= $page_title ?? 'School Management System' ?></title>
</head>
<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <nav class="sidebar">
            <div class="sidebar-header">
                <h1>School Management System</h1>
            </div>
            
            <!-- Navigation Links -->
            <ul class="navbar-nav flex-grow-1">
                <?php if ($_SESSION['role'] === 'admin'): ?>
                    <!-- Admin Navigation -->
                    <li class="nav-item">
                        <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'home.php' ? 'active' : '' ?>" href="home.php">
                            <i class="bi bi-mortarboard-fill"></i> Students
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'teachers.php' ? 'active' : '' ?>" href="teachers.php">
                            <i class="bi bi-person-workspace"></i> Teachers
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'subjects.php' ? 'active' : '' ?>" href="subjects.php">
                            <i class="bi bi-book-fill"></i> Subjects
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'class.php' ? 'active' : '' ?>" href="class.php">
                            <i class="bi bi-grid-3x3-gap-fill"></i> Class Access
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'grades.php' ? 'active' : '' ?>" href="grades.php">
                            <i class="bi bi-award-fill"></i> Grades
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'schedules.php' ? 'active' : '' ?>" href="schedules.php">
                            <i class="bi bi-calendar3"></i> Schedules
                        </a>
                    </li>
                <?php elseif ($_SESSION['role'] === 'teacher'): ?>
                    <!-- Teacher Navigation -->
                    <li class="nav-item">
                        <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'teacher_dashboard.php' ? 'active' : '' ?>" href="teacher_dashboard.php">
                            <i class="bi bi-speedometer2"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'my_subjects.php' ? 'active' : '' ?>" href="my_subjects.php">
                            <i class="bi bi-book-fill"></i> My Subjects
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'my_students.php' ? 'active' : '' ?>" href="my_students.php">
                            <i class="bi bi-people-fill"></i> My Students
                        </a>
                    </li>
                <?php else: ?>
                    <!-- Student Navigation -->
                    <li class="nav-item">
                        <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'student_dashboard.php' ? 'active' : '' ?>" href="student_dashboard.php">
                            <i class="bi bi-speedometer2"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'enrolled_subjects.php' ? 'active' : '' ?>" href="student_dashboard.php#enrolled">
                            <i class="bi bi-journal-check"></i> My Subjects
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'available_subjects.php' ? 'active' : '' ?>" href="student_dashboard.php#available">
                            <i class="bi bi-journal-plus"></i> Available Subjects
                        </a>
                    </li>
                <?php endif; ?>
            </ul>

            <!-- User Info -->
            <div class="user-info">
                <div class="d-flex flex-column">
                    <span class="mb-2">
                        <i class="bi bi-person-circle me-1"></i>
                        <?= htmlspecialchars($_SESSION['name']) ?>
                    </span>
                    <?php if ($_SESSION['role'] === 'teacher'): ?>
                        <span class="badge bg-primary">Teacher</span>
                    <?php elseif ($_SESSION['role'] === 'student'): ?>
                        <span class="badge bg-info">Student</span>
                    <?php else: ?>
                        <span class="badge bg-danger">Admin</span>
                    <?php endif; ?>
                    <a href="logout.php" class="btn btn-outline-light mt-3">
                        <i class="bi bi-box-arrow-right"></i> Logout
                    </a>
                </div>
            </div>
        </nav>

        <!-- Mobile Toggle Button -->
        <button class="navbar-toggler d-md-none" type="button">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Main Content -->
        <div class="main-content">