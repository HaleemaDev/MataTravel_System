<?php
include 'includes/db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['location_ids'])) {
    // Assuming user is logged in with ID 1 for this demo
    $user_id = $_SESSION['user_id'] ?? 1; 
    $ids = $_POST['location_ids'];

    $sql = "INSERT INTO itineraries (user_id, location_ids, total_time) VALUES (?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    
    if ($stmt->execute([$user_id, $ids, 120])) { // 120 is a placeholder for total_time
        echo "<script>alert('Trip Saved!'); window.location.href='explore.php';</script>";
    }
}
?>