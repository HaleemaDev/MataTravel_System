<?php 
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
session_start(); 
include 'includes/db.php';
include 'includes/header.php';

$category_filter = isset($_GET['category']) ? $_GET['category'] : '';
$search_query = isset($_GET['search']) ? $_GET['search'] : '';

$sql = "SELECT * FROM locations WHERE 1=1";
$params = [];

if (!empty($category_filter)) {
    $sql .= " AND category = ?";
    $params[] = $category_filter;
}

if (!empty($search_query)) {
    $sql .= " AND (name LIKE ? OR description LIKE ?)";
    $params[] = "%$search_query%";
    $params[] = "%$search_query%";
}

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$locations = $stmt->fetchAll();

$cat_stmt = $pdo->query("SELECT DISTINCT category FROM locations");
$categories = $cat_stmt->fetchAll(PDO::FETCH_COLUMN);
?>

<div class="container my-5">
    <h2 class="mb-4 fw-bold">Explore Destinations</h2>

    <form action="explore.php" method="GET" class="row g-3 mb-5">
        <div class="col-md-4">
            <select name="category" class="form-select">
                <option value="">All Categories</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?php echo $cat; ?>" <?php echo ($category_filter == $cat) ? 'selected' : ''; ?>>
                        <?php echo $cat; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-3">
            <button type="submit" class="btn btn-primary w-100">Search</button>
        </div>
    </form>

    <div class="row g-4">
        <?php if (count($locations) > 0): ?>
            <?php foreach ($locations as $loc): ?>
            <div class="col-md-4">
                <div class="card h-100 shadow-sm">
                    <img src="assets/images/<?php echo $loc['image']; ?>" class="card-img-top" alt="...">
                    <div class="card-body">
                        <span class="badge bg-info text-dark mb-2"><?php echo $loc['category']; ?></span>
                        <h5 class="card-title"><?php echo $loc['name']; ?></h5>
                        
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <a href="explore_details.php?id=<?php echo $loc['id']; ?>" class="btn btn-outline-primary btn-sm w-100">
                                View Details
                            </a>
                        <?php else: ?>
                            <a href="login.php?redirect=explore_details.php?id=<?php echo $loc['id']; ?>" class="btn btn-outline-primary btn-sm w-100">
                                View Details
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12 text-center">
                <p class="text-muted">No destinations found matching your criteria.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>