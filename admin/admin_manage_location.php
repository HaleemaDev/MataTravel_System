<?php
// Note: We don't need to include db.php here because it's already included in admin_dashboard.php

// Handle Delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM locations WHERE id = ?");
    $stmt->execute([$id]);
    // Redirect back to the destination list view within the dashboard
    header("Location: admin_dashboard.php?page=manage_destinations&msg=deleted");
    exit;
}

// Fetch all locations
$stmt = $pdo->query("SELECT * FROM locations ORDER BY id DESC");
$locations = $stmt->fetchAll();
?>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-0">
        <h5 class="mb-0 fw-bold">Destination Management</h5>
        <a href="?page=add_location" class="btn btn-primary btn-sm rounded-pill px-3">
            <i class="bi bi-plus-lg"></i> Add New Destination
        </a>
    </div>

    <?php if (isset($_GET['msg'])): ?>
        <div class="alert alert-success mx-3 mt-3">Action completed successfully!</div>
    <?php endif; ?>

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light">
                <tr>
                    <th class="ps-4">Image</th>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Distance</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($locations as $loc): ?>
                <tr>
                    <td class="ps-4">
                        <img src="../assets/images/<?= $loc['image'] ?>" alt="" style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px;">
                    </td>
                    <td><strong><?= htmlspecialchars($loc['name']) ?></strong></td>
                    <td><span class="badge bg-light text-dark border"><?= htmlspecialchars($loc['category']) ?></span></td>
                    <td><?= $loc['distance'] ?> km</td>
                    <td class="text-center">
                        <a href="?page=add_location&id=<?= $loc['id'] ?>" class="btn btn-sm btn-outline-primary rounded-pill">Edit</a>
                        <a href="?page=manage_destinations&delete=<?= $loc['id'] ?>" class="btn btn-sm btn-outline-danger rounded-pill" onclick="return confirm('Delete this destination?')">Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>