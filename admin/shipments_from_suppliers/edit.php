<?php
require_once '../../config/db.php';
require_once '../../includes/session.php';
require_once '../../includes/header.php';
checkRole(['admin']);

// Validate and sanitize the ID parameter
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    echo "Shipment ID is required.";
    exit;
}

try {
    // Fetch the shipment details
    $query = "SELECT * FROM ShipmentsFromSuppliers WHERE shipment_id = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$id]);
    $shipment = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$shipment) {
        echo "Shipment not found.";
        exit;
    }

    // Fetch distributors, brands, and warehouses for dropdowns
    $distributors = $pdo->query("SELECT distributor_id, name FROM Distributors")->fetchAll(PDO::FETCH_ASSOC);
    $brands = $pdo->query("SELECT brand_id, name FROM Brands")->fetchAll(PDO::FETCH_ASSOC);
    $warehouses = $pdo->query("SELECT warehouse_id, name FROM Warehouse")->fetchAll(PDO::FETCH_ASSOC);

    // Handle form submission
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $shipment_cost = trim($_POST['shipment_cost']);
        $distributor_id = trim($_POST['distributor_id']);
        $brand_id = trim($_POST['brand_id']);
        $products_sent = trim($_POST['products_sent']);
        $shipment_date = trim($_POST['shipment_date']);
        $warehouse_id = trim($_POST['warehouse_id']);
        $tracking_number = trim($_POST['tracking_number']);
        $status = trim($_POST['status']);

        // Update the shipment in the database
        $update_query = "UPDATE ShipmentsFromSuppliers 
                         SET shipment_cost = ?, distributor_id = ?, brand_id = ?, products_sent = ?, shipment_date = ?, warehouse_id = ?, tracking_number = ?, status = ? 
                         WHERE shipment_id = ?";
        $update_stmt = $pdo->prepare($update_query);
        $update_stmt->execute([$shipment_cost, $distributor_id, $brand_id, $products_sent, $shipment_date, $warehouse_id, $tracking_number, $status, $id]);
        header("Location: index.php");
        exit;
    }
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>

<div class="container mt-4">
    <h2>Edit Shipment from Supplier</h2>
    <form method="post">
        <div class="form-group">
            <label>Shipment Cost</label>
            <input type="number" step="0.01" name="shipment_cost" class="form-control" value="<?= htmlspecialchars($shipment['shipment_cost']) ?>" required>
        </div>
        <div class="form-group">
            <label>Distributor</label>
            <select name="distributor_id" class="form-control" required>
                <?php foreach ($distributors as $d): ?>
                    <option value="<?= htmlspecialchars($d['distributor_id']) ?>" <?= $shipment['distributor_id'] == $d['distributor_id'] ? 'selected' : '' ?>><?= htmlspecialchars($d['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label>Brand</label>
            <select name="brand_id" class="form-control" required>
                <?php foreach ($brands as $b): ?>
                    <option value="<?= htmlspecialchars($b['brand_id']) ?>" <?= $shipment['brand_id'] == $b['brand_id'] ? 'selected' : '' ?>><?= htmlspecialchars($b['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label>Products Sent</label>
            <textarea name="products_sent" class="form-control" required><?= htmlspecialchars($shipment['products_sent']) ?></textarea>
        </div>
        <div class="form-group">
            <label>Shipment Date</label>
            <input type="datetime-local" name="shipment_date" class="form-control" value="<?= date('Y-m-d\TH:i', strtotime($shipment['shipment_date'])) ?>" required>
        </div>
        <div class="form-group">
            <label>Warehouse</label>
            <select name="warehouse_id" class="form-control" required>
                <?php foreach ($warehouses as $w): ?>
                    <option value="<?= htmlspecialchars($w['warehouse_id']) ?>" <?= $shipment['warehouse_id'] == $w['warehouse_id'] ? 'selected' : '' ?>><?= htmlspecialchars($w['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label>Tracking Number</label>
            <input type="text" name="tracking_number" class="form-control" value="<?= htmlspecialchars($shipment['tracking_number']) ?>" required>
        </div>
        <div class="form-group">
            <label>Status</label>
            <select name="status" class="form-control">
                <option value="Pending" <?= $shipment['status'] === 'Pending' ? 'selected' : '' ?>>Pending</option>
                <option value="Received" <?= $shipment['status'] === 'Received' ? 'selected' : '' ?>>Received</option>
                <option value="Delayed" <?= $shipment['status'] === 'Delayed' ? 'selected' : '' ?>>Delayed</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Update Shipment</button>
        <a href="index.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>

<?php require_once '../../includes/footer.php'; ?>
