<?php
require_once '../../config/db.php';
require_once '../../includes/session.php';
require_once '../../includes/header.php';
checkRole(['admin']);

try {
    // Fetch distributors, brands, and warehouses for dropdowns
    $distributors = $pdo->query("SELECT distributor_id, name FROM Distributors")->fetchAll(PDO::FETCH_ASSOC);
    $brands = $pdo->query("SELECT brand_id, name FROM Brands")->fetchAll(PDO::FETCH_ASSOC);
    $warehouses = $pdo->query("SELECT warehouse_id, name FROM Warehouse")->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $shipment_cost = trim($_POST['shipment_cost']);
    $distributor_id = trim($_POST['distributor_id']);
    $brand_id = trim($_POST['brand_id']);
    $products_sent = trim($_POST['products_sent']);
    $shipment_date = trim($_POST['shipment_date']);
    $warehouse_id = trim($_POST['warehouse_id']);
    $tracking_number = trim($_POST['tracking_number']);
    $status = trim($_POST['status']);

    try {
        // Insert the shipment into the database
        $query = "INSERT INTO ShipmentsFromSuppliers (shipment_cost, distributor_id, brand_id, products_sent, shipment_date, warehouse_id, tracking_number, status) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$shipment_cost, $distributor_id, $brand_id, $products_sent, $shipment_date, $warehouse_id, $tracking_number, $status]);
        header("Location: index.php");
        exit;
    } catch (PDOException $e) {
        $error = "Database error: " . $e->getMessage();
    }
}
?>

<div class="container">
    <h2>Add Shipment from Supplier</h2>
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <form method="post">
        <div class="form-group">
            <label>Shipment Cost</label>
            <input type="number" step="0.01" name="shipment_cost" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Distributor</label>
            <select name="distributor_id" class="form-control" required>
                <?php foreach ($distributors as $d): ?>
                    <option value="<?= htmlspecialchars($d['distributor_id']) ?>"><?= htmlspecialchars($d['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label>Brand</label>
            <select name="brand_id" class="form-control" required>
                <?php foreach ($brands as $b): ?>
                    <option value="<?= htmlspecialchars($b['brand_id']) ?>"><?= htmlspecialchars($b['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label>Products Sent</label>
            <textarea name="products_sent" class="form-control" required></textarea>
        </div>
        <div class="form-group">
            <label>Shipment Date</label>
            <input type="datetime-local" name="shipment_date" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Warehouse</label>
            <select name="warehouse_id" class="form-control" required>
                <?php foreach ($warehouses as $w): ?>
                    <option value="<?= htmlspecialchars($w['warehouse_id']) ?>"><?= htmlspecialchars($w['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label>Tracking Number</label>
            <input type="text" name="tracking_number" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Status</label>
            <select name="status" class="form-control">
                <option value="Pending">Pending</option>
                <option value="Received">Received</option>
                <option value="Delayed">Delayed</option>
            </select>
        </div>
        <button type="submit" class="btn btn-success">Add Shipment</button>
    </form>
</div>

<?php require_once '../../includes/footer.php'; ?>
