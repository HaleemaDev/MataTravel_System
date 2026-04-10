<?php
include 'C:/xampp/htdocs/MataTravel_System/includes/db.php';
include 'C:/xampp/htdocs/MataTravel_System/includes/header.php'; 

try {
    // I changed 'u.name' to 'u.username'. 
    // If your column is 'fullname' or 'email', change 'u.username' below to match.
    $sql = "SELECT i.*, u.username as user_name 
            FROM itineraries i 
            JOIN users u ON i.user_id = u.id 
            ORDER BY i.created_at DESC";
            
    $stmt = $pdo->query($sql);
    $logs = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "<div class='alert alert-danger'>Database Error: " . $e->getMessage() . "</div>";
    $logs = []; // Prevent foreach error if query fails
}
?>

<div class="container my-5">
    <h2 class="fw-bold mb-4">User Travel Logs</h2>
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <table class="table table-striped table-hover mb-0">
                <thead class="table-primary">
                    <tr>
                        <th>Date</th>
                        <th>User</th>
                        <th>Location ID(s)</th>
                        <th>Estimated Time</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($logs)): ?>
                        <?php foreach ($logs as $log): ?>
                        <tr>
                            <td><?= date('M d, Y', strtotime($log['created_at'])) ?></td>
                            <td>
                                <strong><?= htmlspecialchars($log['user_name']) ?></strong>
                            </td>
                            <td>
                                <span class="badge bg-secondary">IDs: <?= htmlspecialchars($log['location_ids']) ?></span>
                            </td>
                            <td><?= htmlspecialchars($log['total_time']) ?> mins</td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="text-center py-4">No travel logs found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include 'C:/xampp/htdocs/MataTravel_System/includes/footer.php'; ?>