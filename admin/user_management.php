<?php
// Security Check
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    exit('Unauthorized Access');
}

$message = "";

// 1. Handle Deletion
if (isset($_GET['delete_id']) && isset($_GET['type'])) {
    $table = ($_GET['type'] === 'admin') ? 'admins' : 'users';
    $delStmt = $pdo->prepare("DELETE FROM $table WHERE id = ?");
    if ($delStmt->execute([$_GET['delete_id']])) {
        $message = "Record deleted successfully!";
    }
}

// 2. Handle Adding New User/Admin
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_account'])) {
    $username = $_POST['username'];
    $email = $_POST['email'] ?? null;
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $type = $_POST['account_type'];

    try {
        if ($type === 'admin') {
            $stmt = $pdo->prepare("INSERT INTO admins (username, password) VALUES (?, ?)");
            $stmt->execute([$username, $password]);
        } else {
            $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            $stmt->execute([$username, $email, $password]);
        }
        $message = "Account created successfully!";
    } catch (PDOException $e) {
        $message = "Error: " . $e->getMessage();
    }
}

// 3. Handle Updating User/Admin
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_account'])) {
    $id = $_POST['edit_id'];
    $username = $_POST['edit_username'];
    $email = $_POST['edit_email'] ?? null;
    $type = $_POST['edit_account_type'];
    $password = !empty($_POST['edit_password']) ? password_hash($_POST['edit_password'], PASSWORD_DEFAULT) : null;

    try {
        if ($type === 'admin') {
            if ($password) {
                $stmt = $pdo->prepare("UPDATE admins SET username = ?, password = ? WHERE id = ?");
                $stmt->execute([$username, $password, $id]);
            } else {
                $stmt = $pdo->prepare("UPDATE admins SET username = ? WHERE id = ?");
                $stmt->execute([$username, $id]);
            }
        } else {
            if ($password) {
                $stmt = $pdo->prepare("UPDATE users SET username = ?, email = ?, password = ? WHERE id = ?");
                $stmt->execute([$username, $email, $password, $id]);
            } else {
                $stmt = $pdo->prepare("UPDATE users SET username = ?, email = ? WHERE id = ?");
                $stmt->execute([$username, $email, $id]);
            }
        }
        $message = "Account updated successfully!";
    } catch (PDOException $e) {
        $message = "Error updating: " . $e->getMessage();
    }
}

// 4. Fetch Data
$sql = "SELECT id, username, email, created_at, 'user' as role FROM users 
        UNION 
        SELECT id, username, 'N/A' as email, NULL as created_at, 'admin' as role FROM admins 
        ORDER BY role ASC, username ASC";
$stmt = $pdo->query($sql);
$allAccounts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold text-dark mb-0">Account Management</h2>
        <p class="text-muted small">Manage both administrators and standard users</p>
    </div>
    <div class="btn-group">
        <button class="btn btn-dark rounded-pill px-4 me-2" data-bs-toggle="modal" data-bs-target="#addAccountModal">
            <i class="bi bi-plus-lg me-2"></i> Add New
        </button>
        <button class="btn btn-outline-secondary rounded-pill px-4" onclick="window.print()">
            <i class="bi bi-printer me-2"></i> Export
        </button>
    </div>
</div>

<?php if ($message): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= $message ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="card border-0 shadow-sm" style="border-radius: 15px;">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 py-3">Role</th>
                        <th class="py-3">Details</th>
                        <th class="py-3">Email</th>
                        <th class="py-3">Created</th>
                        <th class="py-3 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($allAccounts as $acc): ?>
                    <tr>
                        <td class="ps-4">
                            <span class="badge <?= $acc['role'] == 'admin' ? 'bg-danger' : 'bg-info text-dark' ?> rounded-pill">
                                <?= strtoupper($acc['role']) ?>
                            </span>
                        </td>
                        <td>
                            <div class="fw-semibold text-dark"><?= htmlspecialchars($acc['username']) ?></div>
                            <small class="text-muted">ID: #<?= $acc['id'] ?></small>
                        </td>
                        <td><?= htmlspecialchars($acc['email']) ?></td>
                        <td class="text-muted">
                            <?= $acc['created_at'] ? date('M d, Y', strtotime($acc['created_at'])) : '---' ?>
                        </td>
                        <td class="text-center">
                            <div class="btn-group">
                                <button class="btn btn-sm btn-outline-primary" 
                                        onclick='openEditModal(<?= json_encode($acc) ?>)' 
                                        title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <a href="?page=manage_users&delete_id=<?= $acc['id'] ?>&type=<?= $acc['role'] ?>" 
                                   class="btn btn-sm btn-outline-danger" 
                                   onclick="return confirm('Delete this <?= $acc['role'] ?>?')">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="addAccountModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Add New Account</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Account Type</label>
                        <select name="account_type" class="form-select" id="roleSelect" onchange="toggleEmailField('roleSelect', 'emailField')" required>
                            <option value="user">Standard User</option>
                            <option value="admin">Administrator</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <input type="text" name="username" class="form-control" required>
                    </div>
                    <div class="mb-3" id="emailField">
                        <label class="form-label">Email Address</label>
                        <input type="email" name="email" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" name="add_account" class="btn btn-primary">Create Account</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="editAccountModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Edit Account</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <input type="hidden" name="edit_id" id="edit_id">
                <input type="hidden" name="edit_account_type" id="edit_account_type">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <input type="text" name="edit_username" id="edit_username" class="form-control" required>
                    </div>
                    <div class="mb-3" id="editEmailField">
                        <label class="form-label">Email Address</label>
                        <input type="email" name="edit_email" id="edit_email" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">New Password (leave blank to keep current)</label>
                        <input type="password" name="edit_password" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" name="update_account" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function toggleEmailField(selectId, fieldId) {
    const role = document.getElementById(selectId).value;
    const emailField = document.getElementById(fieldId);
    emailField.style.display = (role === 'admin') ? 'none' : 'block';
}

function openEditModal(data) {
    document.getElementById('edit_id').value = data.id;
    document.getElementById('edit_username').value = data.username;
    document.getElementById('edit_account_type').value = data.role;
    
    if(data.role === 'admin') {
        document.getElementById('editEmailField').style.display = 'none';
        document.getElementById('edit_email').value = '';
    } else {
        document.getElementById('editEmailField').style.display = 'block';
        document.getElementById('edit_email').value = data.email;
    }
    
    var editModal = new bootstrap.Modal(document.getElementById('edit_account_modal_placeholder') || document.getElementById('editAccountModal'));
    editModal.show();
}
</script>

<style>
    .table thead th { font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px; font-weight: 700; color: #6c757d; border-bottom: none; }
    .table tbody td { padding-top: 1rem; padding-bottom: 1rem; }
    .badge { font-size: 0.7rem; padding: 0.4em 0.8em; }
</style>