<?php
// db.php is already included via dashboard
$id = $_GET['id'] ?? null;
$location = null;

if ($id) {
    $stmt = $pdo->prepare("SELECT * FROM locations WHERE id = ?");
    $stmt->execute([$id]);
    $location = $stmt->fetch();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $category = $_POST['category'];
    $distance = $_POST['distance'];
    $description = $_POST['description'];
    $lat = $_POST['latitude'];
    $lng = $_POST['longitude'];
    
    $image_name = $_POST['existing_image'] ?? 'default.jpg';
    if (!empty($_FILES['image']['name'])) {
        $image_name = time() . '_' . $_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'], "../assets/images/" . $image_name);
    }

    if ($id) {
        $sql = "UPDATE locations SET name=?, category=?, distance=?, description=?, image=?, latitude=?, longitude=? WHERE id=?";
        $pdo->prepare($sql)->execute([$name, $category, $distance, $description, $image_name, $lat, $lng, $id]);
    } else {
        $sql = "INSERT INTO locations (name, category, distance, description, image, latitude, longitude) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $pdo->prepare($sql)->execute([$name, $category, $distance, $description, $image_name, $lat, $lng]);
    }
    // Redirect back to the LIST view
    echo "<script>window.location.href='admin_dashboard.php?page=manage_destinations&msg=success';</script>";
    exit;
}
?>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-header bg-white py-3 border-0">
        <h5 class="mb-0 fw-bold"><?= $id ? 'Edit Destination' : 'Create New Destination' ?></h5>
    </div>
    <div class="card-body">
        <form method="POST" enctype="multipart/form-data" action="?page=add_location<?= $id ? '&id='.$id : '' ?>">
            <input type="hidden" name="existing_image" value="<?= $location['image'] ?? '' ?>">
            
            <div class="row">
                <div class="col-md-8 mb-3">
                    <label class="form-label small fw-bold">Destination Name</label>
                    <input type="text" name="name" class="form-control rounded-3" value="<?= $location['name'] ?? '' ?>" required>
                </div>
               <?php
                    // Fetch unique categories for the suggestions list
                    try {
                        // Replace 'locations' with your actual table name
                        $catStmt = $pdo->query("SELECT DISTINCT category FROM locations WHERE category IS NOT NULL AND category != '' ORDER BY category ASC");
                        $existingCategories = $catStmt->fetchAll(PDO::FETCH_COLUMN);
                    } catch (PDOException $e) {
                        $existingCategories = [];
                    }
                ?>
              <div class="col-md-4 mb-3">
                <label class="form-label small fw-bold">Category</label>
                <select name="category" id="categoryInput" class="form-select rounded-3" required>
                    <option value="" disabled <?= !isset($location['category']) ? 'selected' : '' ?>>Select a category</option>
                    
                    <?php foreach ($existingCategories as $cat): ?>
                        <option value="<?= htmlspecialchars($cat) ?>" 
                            <?= (isset($location['category']) && $location['category'] == $cat) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cat) ?>
                        </option>
                    <?php endforeach; ?>

                    <?php 
                    // Logic to show the current category if it's not in the unique list (edge case)
                    if (isset($location['category']) && !in_array($location['category'], $existingCategories)): ?>
                        <option value="<?= htmlspecialchars($location['category']) ?>" selected>
                            <?= htmlspecialchars($location['category']) ?>
                        </option>
                    <?php endif; ?>
                </select>
            </div>
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label small fw-bold">Distance (km)</label>
                    <input type="number" step="0.1" name="distance" class="form-control rounded-3" value="<?= $location['distance'] ?? '' ?>" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label small fw-bold">Latitude</label>
                    <input type="text" name="latitude" class="form-control rounded-3" value="<?= $location['latitude'] ?? '' ?>">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label small fw-bold">Longitude</label>
                    <input type="text" name="longitude" class="form-control rounded-3" value="<?= $location['longitude'] ?? '' ?>">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label small fw-bold">Description</label>
                <textarea name="description" class="form-control rounded-3" rows="4" required><?= $location['description'] ?? '' ?></textarea>
            </div>

            <div class="mb-4">
                <label class="form-label small fw-bold d-block">Cover Image</label>
                
                <?php if (!empty($location['image'])): ?>
                    <div class="mb-2">
                        <img src="../assets/images/<?= htmlspecialchars($location['image']) ?>" 
                            alt="Current Image" 
                            class="img-thumbnail" 
                            style="height: 100px; width: 150px; object-fit: cover;">
                        <p class="text-muted small">Current: <?= htmlspecialchars($location['image']) ?></p>
                    </div>
                <?php endif; ?>

                <input type="file" name="image" class="form-control rounded-3">
                <div class="form-text">Leave blank to keep the current image.</div>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary px-4 rounded-pill">Save Destination</button>
                <a href="?page=manage_destinations" class="btn btn-light px-4 rounded-pill border">Cancel</a>
            </div>
        </form>
    </div>
</div>