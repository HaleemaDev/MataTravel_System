<?php
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    exit('Unauthorized Access');
}

$message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role']; // 'admin' or 'traveler'

    try {
        if ($role === 'admin') {
            // Insert into ADMINS table
            $stmt = $pdo->prepare("INSERT INTO admins (username, password) VALUES (?, ?)");
            $stmt->execute([$username, $password]);
        } else {
            // Insert into USERS table
            $email = $_POST['email'];
            $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            $stmt->execute([$username, $email, $password]);
        }
        $message = "<div class='alert alert-success'>New $role added successfully!</div>";
    } catch (PDOException $e) {
        $message = "<div class='alert alert-danger'>Error: " . $e->getMessage() . "</div>";
    }
}
?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm" style="border-radius: 15px;">
            <div class="card-header bg-white border-0 pt-4 ps-4">
                <h4 class="fw-bold"><i class="bi bi-person-plus me-2 text-primary"></i>Register New Account</h4>
            </div>
            <div class="card-body p-4">
                <?= $message ?>
                <form method="POST" action="">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Account Category</label>
                        <select name="role" id="roleSelect" class="form-select" required onchange="toggleEmailField()">
                            <option value="traveler">Traveler (Standard User)</option>
                            <option value="admin">System Administrator</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Username</label>
                        <input type="text" name="username" class="form-control" placeholder="Enter username" required>
                    </div>

                    <div class="mb-3" id="emailGroup">
                        <label class="form-label fw-semibold">Email Address</label>
                        <input type="email" name="email" class="form-control" placeholder="user@example.com">
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Temporary Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary py-2 rounded-3 fw-bold">Create Account</button>
                        <a href="?page=manage_users" class="btn btn-light py-2 rounded-3">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function toggleEmailField() {
    const role = document.getElementById('roleSelect').value;
    const emailGroup = document.getElementById('emailGroup');
    const emailInput = emailGroup.querySelector('input');
    
    if (role === 'admin') {
        emailGroup.style.display = 'none';
        emailInput.required = false;
    } else {
        emailGroup.style.display = 'block';
        emailInput.required = true;
    }
}
// Run once on load
toggleEmailField();
</script>