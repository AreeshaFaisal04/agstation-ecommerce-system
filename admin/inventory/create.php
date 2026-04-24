<?php
include '../../includes/session.php';
include '../../includes/header.php';
require_once '../../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = trim($_POST['product_id']);
    $warehouse_id = trim($_POST['warehouse_id']);
    $quantity = trim($_POST['quantity']);

    try {
        // Insert the inventory entry into the database
        $query = "INSERT INTO Inventory (product_id, warehouse_id, quantity) VALUES (?, ?, ?)";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$product_id, $warehouse_id, $quantity]);
        header("Location: index.php");
        exit;
    } catch (PDOException $e) {
        $error = "Error adding inventory entry: " . $e->getMessage();
    }
}

try {
    // Fetch products and warehouses for the dropdowns
    $product_query = "SELECT asin, name FROM Products";
    $product_stmt = $pdo->prepare($product_query);
    $product_stmt->execute();
    $products = $product_stmt->fetchAll(PDO::FETCH_ASSOC);

    $warehouse_query = "SELECT warehouse_id, name FROM Warehouse";
    $warehouse_stmt = $pdo->prepare($warehouse_query);
    $warehouse_stmt->execute();
    $warehouses = $warehouse_stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>

<div class="container mt-4">
    <h2>Add Inventory Entry</h2>
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <form method="POST">
        <div class="mb-3">
            <label for="product_id" class="form-label">Product</label>
            <select name="product_id" class="form-select" required>
                <?php foreach ($products as $product): ?>
                    <option value="<?= htmlspecialchars($product['asin']) ?>"><?= htmlspecialchars($product['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="warehouse_id" class="form-label">Warehouse</label>
            <select name="warehouse_id" class="form-select" required>
                <?php foreach ($warehouses as $warehouse): ?>
                    <option value="<?= htmlspecialchars($warehouse['warehouse_id']) ?>"><?= htmlspecialchars($warehouse['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="quantity" class="form-label">Quantity</label>
            <input type="number" name="quantity" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Save</button>
    </form>
</div>

<?php include '../../includes/footer.php'; ?>
