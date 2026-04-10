<?php
session_start();
require 'includes/db.php';

$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $identity = trim($_POST['login_identity']);
    $password = $_POST['password'];

    if (!empty($identity) && !empty($password)) {

        try {
            // =========================
            // 1. CHECK ADMINS
            // =========================
            $stmt = $pdo->prepare("SELECT id, username, password FROM admins WHERE username = :identity LIMIT 1");
            $stmt->execute(['identity' => $identity]);
            $admin = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($admin) {
                // Works for BOTH hashed and plain passwords
                if (password_verify($password, $admin['password']) || $password === $admin['password']) {

                    $_SESSION['admin_id'] = $admin['id'];
                    $_SESSION['username'] = $admin['username'];
                    $_SESSION['role'] = 'admin';

                    header("Location: admin/admin_dashboard.php");
                    exit();
                }
            }

            // =========================
            // 2. CHECK USERS
            // =========================
            // (Remove email if your table doesn't have it)
            $stmt = $pdo->prepare("SELECT id, username, password FROM users WHERE username = :identity OR email = :identity LIMIT 1");
            $stmt->execute(['identity' => $identity]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                // Works for BOTH hashed and plain passwords
                if (password_verify($password, $user['password']) || $password === $user['password']) {

                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['role'] = 'user';

                    header("Location: explore.php");
                    exit();
                }
            }

            // =========================
            // 3. NO MATCH
            // =========================
            $error_message = "Invalid credentials. Please try again.";

        } catch (PDOException $e) {
            $error_message = "Database error: " . $e->getMessage();
        }

    } else {
        $error_message = "Please fill in all fields.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MataTravel | Login</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body, html { margin: 0; padding: 0; height: 100%; overflow: hidden; font-family: 'Segoe UI', sans-serif; }
        .video-background { position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: -1; }
        #bg-video { width: 100%; height: 100%; object-fit: cover; }
        .glass-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.5);
            color: white;
        }
        .custom-input {
            background: rgba(255, 255, 255, 0.1) !important;
            border: 1px solid rgba(255, 255, 255, 0.2) !important;
            color: white !important;
        }
        .custom-input::placeholder { color: rgba(255, 255, 255, 0.6); }
        .btn-glow {
            background: linear-gradient(45deg, #ff00bd, #4444ff);
            border: none; color: white; font-weight: bold; padding: 10px; transition: 0.3s ease-in-out;
        }
        .btn-glow:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(255, 0, 189, 0.4); color: white; }
    </style>
</head>

<body>

<div class="video-background">
    <video autoplay muted loop playsinline id="bg-video">
        <source src="assets/login_page.mp4" type="video/mp4">
    </video>
</div>

<div class="container d-flex align-items-center justify-content-center" style="min-height: 100vh;">
    <div class="row justify-content-center w-100">
        <div class="col-md-4">
            <div class="card glass-card p-4">

                <h3 class="text-center mb-4">MataTravel Login</h3>

                <?php if (!empty($error_message)): ?>
                    <div class="alert alert-danger py-2 text-center" style="background: rgba(255,0,0,0.2); color: #ffcccc; border: none; font-size: 0.9rem;">
                        <?= htmlspecialchars($error_message) ?>
                    </div>
                <?php endif; ?>

                <form method="POST">

                    <div class="mb-3">
                        <label class="form-label">Email or Username</label>
                        <input type="text" name="login_identity" class="form-control custom-input" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control custom-input" required>
                    </div>

                    <button type="submit" class="btn btn-glow w-100 mt-2">Sign In</button>

                    <div class="text-center mt-4">
                        <span class="small text-white-50">Don't have an account?</span>
                        <a href="register.php" class="text-info small fw-bold" style="text-decoration: none;"> Register</a>
                    </div>

                </form>

            </div>
        </div>
    </div>
</div>

</body>
</html>