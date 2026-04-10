<?php
session_start();
include 'C:/xampp/htdocs/MataTravel_System/includes/db.php';

// 1. ACCESS CONTROL
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$admin_name = $_SESSION['username'] ?? 'Admin';
$page = $_GET['page'] ?? 'dashboard';

// 2. DYNAMIC DATA FETCHING
try {
    $userCount = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
    $destCount = $pdo->query("SELECT COUNT(*) FROM locations")->fetchColumn();
    $tripCount = $pdo->query("SELECT COUNT(*) FROM itineraries")->fetchColumn();

    $recentActivityStmt = $pdo->query("
        SELECT u.username, 'Created an itinerary' as action, i.created_at 
        FROM itineraries i 
        JOIN users u ON i.user_id = u.id 
        ORDER BY i.created_at DESC 
        LIMIT 5
    ");
    $recentActivities = $recentActivityStmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $userCount = $destCount = $tripCount = 0;
    $recentActivities = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MataTravel | Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        :root { --sidebar-width: 280px; --accent-color: #0b7ace; }
        body { background-color: #f8f9fa; font-family: 'Segoe UI', Tahoma, sans-serif; overflow-x: hidden; }
        #sidebar { width: var(--sidebar-width); height: 100vh; position: fixed; left: 0; top: 0; background: #212529; color: white; z-index: 1000; }
        .sidebar-header { padding: 25px; background: rgba(255, 255, 255, 0.05); text-align: center; border-bottom: 1px solid rgba(255,255,255,0.1); }
        .nav-link { color: rgba(255, 255, 255, 0.7); padding: 15px 25px; border-left: 4px solid transparent; transition: 0.3s; text-decoration: none; display: flex; align-items: center; }
        .nav-link i { margin-right: 12px; font-size: 1.1rem; }
        .nav-link:hover, .nav-link.active { color: white; background: rgba(255, 255, 255, 0.1); border-left-color: var(--accent-color); }
        #main-content { margin-left: var(--sidebar-width); padding: 30px; min-height: 100vh; }
        .top-nav { background: white; padding: 15px 30px; margin-bottom: 30px; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.03); display: flex; justify-content: space-between; align-items: center; }
        .stat-card { border: none; border-radius: 15px; transition: transform 0.3s; box-shadow: 0 4px 10px rgba(0,0,0,0.05); }
        .stat-card:hover { transform: translateY(-5px); }
    </style>
</head>
<body>

<nav id="sidebar">
    <div class="sidebar-header">
        <h4 class="mb-0 fw-bold">Mata<span style="color: var(--accent-color);">Travel</span></h4>
        <small class="text-white-50">Control Panel</small>
    </div>
    <div class="mt-3">
        <a href="?page=dashboard" class="nav-link <?= ($page == 'dashboard') ? 'active' : '' ?>"><i class="bi bi-speedometer2"></i> Dashboard</a>
        <a href="?page=manage_users" class="nav-link <?= ($page == 'manage_users') ? 'active' : '' ?>"><i class="bi bi-people"></i> Manage Users</a>
        <a href="?page=manage_destinations" class="nav-link <?= ($page == 'manage_destinations' || $page == 'add_location') ? 'active' : '' ?>"><i class="bi bi-geo-alt"></i> Manage Destinations</a>
        <a href="?page=view_itineraries" class="nav-link <?= ($page == 'view_itineraries') ? 'active' : '' ?>"><i class="bi bi-journal-text"></i> Itineraries</a>
        <hr class="mx-3 opacity-25">
        <a href="../index.php" class="nav-link" target="_blank"><i class="bi bi-box-arrow-up-right"></i> Live Site</a>
    </div>
</nav>

<div id="main-content">
    <div class="top-nav">
        <h5 class="mb-0 text-secondary">System / <span class="text-dark fw-bold"><?= ucwords(str_replace('_', ' ', $page)) ?></span></h5>
        <div class="d-flex align-items-center">
            <span class="me-3">Hello, <strong><?= htmlspecialchars($admin_name) ?></strong></span>
            <a href="../logout.php" class="btn btn-outline-danger btn-sm px-3 rounded-pill"><i class="bi bi-power"></i> Logout</a>
        </div>
    </div>

    <div class="container-fluid">
        <?php
        switch ($page) {
            case 'manage_destinations':
                include 'admin_manage_location.php';
                break;
            
            case 'add_location':
                include 'add_location.php';
                break;

            case 'manage_users':
                include 'user_management.php';
                break;

            case 'dashboard':
            default:
                // ... (Keep your existing dashboard row g-4 and recent activity table here) ...
                ?>
                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="card stat-card bg-white p-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-1">Total Users</h6>
                                    <h2 class="mb-0 fw-bold"><?= number_format($userCount) ?></h2>
                                </div>
                                <div class="bg-primary bg-opacity-10 p-3 rounded-3"><i class="bi bi-people text-primary fs-3"></i></div>
                            </div>
                        </div>
                    </div>
                    </div>
                <?php
                break;
        }
        ?>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>