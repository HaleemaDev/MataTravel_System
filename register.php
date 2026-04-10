<?php 
include 'includes/header.php'; 
include 'includes/db.php'; // This now contains your $pdo connection

$message = "";
$messageClass = "";

// 1. PROCESSING LOGIC
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fname    = trim($_POST['firstname']);
    $lname    = trim($_POST['lastname']);
    $email    = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm  = $_POST['confirm_password'];

    $full_name = $fname . " " . $lname;

    if ($password !== $confirm) {
        $message = "Passwords do not match!";
        $messageClass = "alert-danger";
    } elseif (strlen($password) < 6) {
        $message = "Password must be at least 6 characters.";
        $messageClass = "alert-warning";
    } else {
        try {
            // Check if email exists using PDO Prepared Statement
            $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
            $stmt->execute([$email]);
            
            if ($stmt->rowCount() > 0) {
                $message = "Email is already registered!";
                $messageClass = "alert-warning";
            } else {
                // Hash Password
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                // Insert into Database
                $sql = "INSERT INTO users (name, email, password) VALUES (:name, :email, :password)";
                $insertStmt = $pdo->prepare($sql);
                
                $result = $insertStmt->execute([
                    ':name' => $full_name,
                    ':email' => $email,
                    ':password' => $hashed_password
                ]);

                if ($result) {
                    $message = "Success! <a href='login.php' class='text-dark fw-bold'>Login here</a>";
                    $messageClass = "alert-success";
                }
            }
        } catch (PDOException $e) {
            $message = "Database error: " . $e->getMessage();
            $messageClass = "alert-danger";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MataTravel | Join the Adventure</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="video-background">
    <video autoplay muted loop playsinline id="bg-video">
        <source src="assets/login_page.mp4" type="video/mp4">
    </video>
</div>

<div class="container d-flex align-items-center justify-content-center" style="min-height: 100vh; padding: 50px 0;">
    <div class="row justify-content-center w-100">
        <div class="col-md-5">
            <div class="card glass-card p-4 border-0">
                <h3 class="text-center mb-4 text-white">Create Account</h3>

                <?php if ($message !== ""): ?>
                    <div class="alert <?php echo $messageClass; ?> border-0">
                        <?php echo $message; ?>
                    </div>
                <?php endif; ?>

                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-white">First Name</label>
                            <input type="text" name="firstname" class="form-control custom-input" placeholder="John" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-white">Last Name</label>
                            <input type="text" name="lastname" class="form-control custom-input" placeholder="Doe" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-white">Email Address</label>
                        <input type="email" name="email" class="form-control custom-input" placeholder="name@example.com" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-white">Password</label>
                        <input type="password" name="password" class="form-control custom-input" placeholder="Create a password" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label text-white">Confirm Password</label>
                        <input type="password" name="confirm_password" class="form-control custom-input" placeholder="Repeat password" required>
                    </div>

                    <button type="submit" class="btn btn-glow w-100">Register Now</button>
                    
                    <p class="mt-3 text-center small text-white-50">
                        Already have an account? <a href="login.php" class="text-info fw-bold">Login</a>
                    </p>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
/* Keeping your existing glassmorphism and video styles */
.video-background { position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: -1; overflow: hidden; }
#bg-video { min-width: 100%; min-height: 100%; object-fit: cover; }
.glass-card { 
    background: rgba(255, 255, 255, 0.15); 
    backdrop-filter: blur(15px); 
    border: 1px solid rgba(255, 255, 255, 0.2); 
    border-radius: 20px; 
}
.btn-glow { 
    background: linear-gradient(45deg, #ff00bd, #4444ff); 
    border: none; color: white; font-weight: bold; padding: 12px; 
}
.custom-input { 
    background: rgba(255, 255, 255, 0.2) !important; 
    border: none !important; color: white !important; 
}
.custom-input::placeholder { color: rgba(255, 255, 255, 0.6); }
</style>

<?php include 'includes/footer.php'; ?>