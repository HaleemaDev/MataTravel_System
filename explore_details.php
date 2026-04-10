<?php 
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
// ALWAYS START SESSION FIRST
session_start();
include 'includes/db.php';

// 1. STRICT VERIFICATION: Check session AND database record
if (!isset($_SESSION['user_id'])) {
    // If no session, force redirect to login
    $current_page = "explore_details.php?id=" . ($_GET['id'] ?? '');
    header("Location: login.php?redirect=" . urlencode($current_page));
    exit();
} else {
    // Session exists, but is the user actually in the DB?
    $stmt = $pdo->prepare("SELECT id FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $valid_user = $stmt->fetch();

    if (!$valid_user) {
        // Session contains an ID not found in the database
        session_destroy();
        header("Location: login.php");
        exit();
    }
}

// 2. AJAX HANDLER (For "Add to Trip List")
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'save_trip') {
    header('Content-Type: application/json');
    $location_id = $_POST['location_id'] ?? null;
    
    if (!$location_id) {
        echo json_encode(['status' => 'error', 'message' => 'No location selected.']);
        exit;
    }

    if (!isset($_SESSION['trip_list'])) $_SESSION['trip_list'] = [];
    if (!in_array($location_id, $_SESSION['trip_list'])) {
        $_SESSION['trip_list'][] = (int)$location_id;
    }

    try {
        $stmt = $pdo->prepare("INSERT INTO itineraries (user_id, location_ids, total_time) VALUES (?, ?, ?)");
        $stmt->execute([$_SESSION['user_id'], $location_id, 60]);
        echo json_encode(['status' => 'success']);
        exit;
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Database Error']);
        exit;
    }
}

// 3. FETCH LOCATION DATA
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $stmt = $pdo->prepare("SELECT * FROM locations WHERE id = ?");
    $stmt->execute([$_GET['id']]);
    $location = $stmt->fetch();

    if (!$location) {
        include 'includes/header.php';
        echo "<div class='container my-5'><div class='alert alert-danger'>Destination not found.</div></div>";
        include 'includes/footer.php';
        exit;
    }
} else {
    header("Location: explore.php");
    exit;
}

include 'includes/header.php';
?>

<div class="container my-5">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="explore.php">Explore</a></li>
            <li class="breadcrumb-item active"><?php echo htmlspecialchars($location['name']); ?></li>
        </ol>
    </nav>

    <div class="row g-5">
        <div class="col-lg-7">
            <div class="shadow-sm rounded overflow-hidden">
                <img src="assets/images/<?php echo $location['image']; ?>" class="img-fluid w-100" alt="...">
            </div>
        </div>

        <div class="col-lg-5">
            <h1 class="display-5 fw-bold mb-3"><?php echo htmlspecialchars($location['name']); ?></h1>
            <div class="d-flex align-items-center mb-4">
                <div class="me-4">
                    <p class="text-muted mb-0 small">Distance</p>
                    <p class="fw-bold fs-5 text-primary mb-0"><?php echo htmlspecialchars($location['distance']); ?> km</p>
                </div>
                <div>
                    <p class="text-muted mb-0 small">Location Type</p>
                    <p class="fw-bold fs-5 mb-0">Local Destination</p>
                </div>
            </div>
            <hr>
            <h4 class="fw-bold mt-4">Description</h4>
            <p class="text-secondary"><?php echo nl2br(htmlspecialchars($location['description'])); ?></p>

            <div class="mt-4 d-flex gap-2 flex-wrap">
                <a href="trip_summary.php?id=<?php echo $location['id']; ?>" class="btn btn-primary btn-lg">Save My Trip</a>
                <button class="btn btn-warning btn-lg" onclick="saveToTripList(<?php echo $location['id']; ?>)">Add to Trip List</button>      
                <a href="trip_summary.php?type=bulk" class="btn btn-outline-dark btn-lg">View Saved List</a>
            </div>
            <div id="success-alert" class="alert alert-success mt-3 d-none">Destination added!</div>
        </div>
    </div>
</div>

<script>
function saveToTripList(locationId) {
    const formData = new FormData();
    formData.append('action', 'save_trip');
    formData.append('location_id', locationId);

    fetch(window.location.href, { method: 'POST', body: formData })
    .then(r => r.json())
    .then(data => {
        if (data.status === 'success') {
            document.getElementById('success-alert').classList.remove('d-none');
            setTimeout(() => { document.getElementById('success-alert').classList.add('d-none'); }, 3000);
        }
    });
}
</script>

<?php include 'includes/footer.php'; ?>