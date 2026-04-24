<?php
require_once '../../config/db.php';
require_once '../../includes/session.php';
require_once '../../includes/header.php';
checkRole(['admin']);

try {
    // Fetch all orders for the dropdown
    $query = "SELECT order_id FROM WebsiteOrders";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $order_id = trim($_POST['order_id']);
    $carrier = trim($_POST['carrier']);
    $tracking_number = trim($_POST['tracking_number']);
    $status = trim($_POST['status']);

    try {
        // Insert the shipment into the database
        $query = "INSERT INTO CustomerShipments (order_id, carrier, tracking_number, status) VALUES (?, ?, ?, ?)";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$order_id, $carrier, $tracking_number, $status]);
        header("Location: index.php");
        exit;
    } catch (PDOException $e) {
        $error = "Database error: " . $e->getMessage();
    }
}
?>

<div class="container">
    <h2>Add Shipment</h2>
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <form method="post">
        <div class="form-group">
            <label>Order</label>
            <select name="order_id" class="form-control" required>
                <?php foreach ($orders as $o): ?>
                    <option value="<?= htmlspecialchars($o['order_id']) ?>"><?= htmlspecialchars($o['order_id']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label>Carrier</label>
            <input type="text" name="carrier" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Tracking Number</label>
            <input type="text" name="tracking_number" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Status</label>
            <select name="status" class="form-control">
                <option value="Shipped">Shipped</option>
                <option value="In Transit">In Transit</option>
                <option value="Delivered">Delivered</option>
            </select>
        </div>
        <button type="submit" class="btn btn-success">Add Shipment</button>
    </form>
</div>

<?php require_once '../../includes/footer.php'; ?>
