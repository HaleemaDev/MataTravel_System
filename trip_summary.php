<?php
include 'includes/db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * LOGIC: Handle Removing/Clearing 
 */
if (isset($_GET['remove']) && is_numeric($_GET['remove'])) {
    $remove_id = (int)$_GET['remove'];
    if (isset($_SESSION['trip_list'])) {
        $key = array_search($remove_id, $_SESSION['trip_list']);
        if ($key !== false) {
            unset($_SESSION['trip_list'][$key]);
            $_SESSION['trip_list'] = array_values($_SESSION['trip_list']);
        }
    }
    header("Location: trip_summary.php?type=bulk");
    exit;
}

if (isset($_GET['clear']) && $_GET['clear'] == '1') {
    $_SESSION['trip_list'] = [];
    header("Location: trip_summary.php?type=bulk");
    exit;
}

include 'includes/header.php';

$items = [];
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $stmt = $pdo->prepare("SELECT * FROM locations WHERE id = ?");
    $stmt->execute([$_GET['id']]);
    $res = $stmt->fetch();
    if ($res) $items[] = $res;
} elseif (isset($_GET['type']) && $_GET['type'] == 'bulk') {
    if (!empty($_SESSION['trip_list'])) {
        $ids = $_SESSION['trip_list'];
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $stmt = $pdo->prepare("SELECT * FROM locations WHERE id IN ($placeholders)");
        $stmt->execute($ids);
        $items = $stmt->fetchAll();
    }
}
?>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

<div class="container my-5" id="printable-content">
    <?php if (empty($items)): ?>
        <div class="text-center py-5">
            <i class="bi bi-geo-alt text-muted display-1"></i>
            <h2 class="mt-3">Your trip list is empty</h2>
            <a href="explore.php" class="btn btn-primary mt-3">Browse Destinations</a>
        </div>
    <?php else: ?>
        
        <div class="d-flex justify-content-between align-items-center mb-4 d-print-none">
            <h2><i class="bi bi-map"></i> Trip Summary</h2>
            <div>
                <button onclick="window.print()" class="btn btn-outline-dark me-2">
                    <i class="bi bi-printer"></i> Print
                </button>
                <button onclick="window.print()" class="btn btn-success me-2">
                    <i class="bi bi-file-earmark-pdf"></i> Save as PDF
                </button>
                <a href="trip_summary.php?clear=1" class="btn btn-link text-danger" onclick="return confirm('Are you sure want to clear list?')">Clear All</a>
            </div>
        </div>

        <div class="row g-3 d-flex align-items-stretch">
            <div class="col-md-5 d-flex flex-column">
                <div class="card shadow-sm h-100 border-0 border-start border-primary border-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Your Itinerary</h5>
                    </div>
                    <ul class="list-group list-group-flush flex-grow-1">
                        <?php $total_dist = 0; foreach ($items as $index => $item): $total_dist += $item['distance']; ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>
                                    <span class="badge bg-secondary me-2"><?php echo $index + 1; ?></span>
                                    <strong><?php echo htmlspecialchars($item['name']); ?></strong>
                                </span>
                                <a href="trip_summary.php?remove=<?php echo $item['id']; ?>" class="text-danger d-print-none text-decoration-none" title="Remove">&times;</a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                    <div class="card-footer fw-bold bg-light d-flex justify-content-between border-top">
                        <span>Total Distance:</span>
                        <span class="text-primary"><?php echo $total_dist; ?> km</span>
                    </div>
                </div>
            </div>

            <div class="col-md-7 d-flex flex-column">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-dark text-white">Route Map View</div>
                    <div class="card-body p-0 flex-grow-1">
                        <div id="map" style="height: 100%; min-height: 400px; width: 100%;"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-5">
            <div class="col-12">
                <h4 class="border-bottom pb-2 mb-4"><i class="bi bi-info-circle"></i> Destination Details</h4>
                <?php foreach ($items as $item): ?>
                    <div class="mb-4 destination-block">
                        <h5 class="text-primary"><?php echo htmlspecialchars($item['name']); ?></h5>
                        <p class="text-secondary" style="text-align: justify;">
                            <?php echo nl2br(htmlspecialchars($item['description'])); ?>
                        </p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    <?php if (!empty($items)): ?>
        const locations = <?php echo json_encode($items); ?>;
        const map = L.map('map');

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        const latlngs = [];

        locations.forEach((loc, index) => {
            if (loc.latitude && loc.longitude) {
                const coords = [parseFloat(loc.latitude), parseFloat(loc.longitude)];
                latlngs.push(coords);
                L.marker(coords).addTo(map)
                    .bindPopup(`<b>${index + 1}. ${loc.name}</b>`);
            }
        });

        if (latlngs.length > 0) {
            const polyline = L.polyline(latlngs, {color: '#0056B3', weight: 4, opacity: 0.8}).addTo(map);
            map.fitBounds(polyline.getBounds(), {padding: [50, 50]});
        } else {
            map.setView([0, 0], 2);
            document.getElementById('map').innerHTML = "<div class='p-5 text-center text-muted'>Map coordinates unavailable.</div>";
        }

        // Fix Leaflet gray tiles issue when printing or resizing
        window.addEventListener('resize', () => map.invalidateSize());
    <?php endif; ?>
});
</script>

<style>
/* General UI Tweaks */
#map { z-index: 1; border-radius: 0 0 4px 4px; }
.list-group-item { border-left: 0; border-right: 0; }
.card { border-radius: 8px; overflow: hidden; }

/* PRINT OPTIMIZATION */
@media print {
    /* Hide everything except content */
    header, .navbar, footer, .d-print-none, .btn, .breadcrumb { 
        display: none !important; 
        position: absolute !important;
        visibility: hidden !important;
    }

    body, html {
        margin: 0 !important;
        padding: 0 !important;
        background: #fff !important;
    }

    .container {
        width: 100% !important;
        max-width: 100% !important;
        margin: 0 !important;
        padding: 5mm !important;
    }

    /* Force row to stay side-by-side on paper */
    .row {
        display: flex !important;
        flex-direction: row !important;
        flex-wrap: nowrap !important;
    }

    .col-md-5 { width: 35% !important; flex: 0 0 35% !important; }
    .col-md-7 { width: 65% !important; flex: 0 0 65% !important; }

    /* Map dimensions for PDF */
    #map { 
        height: 380px !important; 
        width: 100% !important;
        border: 1px solid #000 !important;
        -webkit-print-color-adjust: exact;
    }

    /* Keep colors in PDF */
    .bg-primary { background-color: #0056B3 !important; color: white !important; -webkit-print-color-adjust: exact; }
    .bg-dark { background-color: #212529 !important; color: white !important; -webkit-print-color-adjust: exact; }
    .badge { border: 1px solid #6c757d !important; color: #000 !important; -webkit-print-color-adjust: exact; }

    /* Prevent text from cutting across pages */
    .destination-block {
        page-break-inside: avoid;
        break-inside: avoid;
    }

    h2, h4 { margin-top: 0 !important; }
}
</style>

<?php include 'includes/footer.php'; ?>