<?php
require_once '../../config/db.php';
require_once '../../includes/session.php';
require_once '../../includes/header.php';
checkRole(['admin']);

// Validate and sanitize the ID parameter
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    header("Location: index.php");
    exit;
}

try {
    // Fetch the shipment details
    $query = "SELECT * FROM CustomerShipments WHERE shipment_id = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$id]);
    $shipment = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$shipment) {
        header("Location: index.php");
        exit;
    }

    // Handle form submission
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $carrier = trim($_POST['carrier']);
        $tracking_number = trim($_POST['tracking_number']);
        $status = trim($_POST['status']);

        // Update the shipment in the database
        $update_query = "UPDATE CustomerShipments SET carrier = ?, tracking_number = ?, status = ? WHERE shipment_id = ?";
        $update_stmt = $pdo->prepare($update_query);
        $update_stmt->execute([$carrier, $tracking_number, $status, $id]);
        header("Location: index.php");
        exit;
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

<div class="container">
    <h2>Edit Shipment</h2>
    <form method="post">
        <div class="form-group">
            <label>Carrier</label>
            <input type="text" name="carrier" class="form-control" value="<?= htmlspecialchars($shipment['carrier']) ?>" required>
        </div>
        <div class="form-group">
            <label>Tracking Number</label>
            <input type="text" name="tracking_number" class="form-control" value="<?= htmlspecialchars($shipment['tracking_number']) ?>" required>
        </div>
        <div class="form-group">
            <label>Status</label>
            <select name="status" class="form-control">
                <option <?= $shipment['status'] == 'Shipped' ? 'selected' : '' ?>>Shipped</option>
                <option <?= $shipment['status'] == 'In Transit' ? 'selected' : '' ?>>In Transit</option>
                <option <?= $shipment['status'] == 'Delivered' ? 'selected' : '' ?>>Delivered</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Update Shipment</button>
    </form>
</div>

<?php require_once '../../includes/footer.php'; ?>
