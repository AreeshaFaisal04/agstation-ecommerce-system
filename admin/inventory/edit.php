<?php
include '../../includes/session.php';
include '../../includes/header.php';
require_once '../../config/db.php';
include '../../includes/navbar.php';
include '../../includes/sidebar.php';

// Validate and sanitize the ID parameter
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    header("Location: index.php");
    exit;
}

try {
    // Fetch the inventory entry
    $query = "SELECT * FROM Inventory WHERE inventory_id = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$id]);
    $inventory = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$inventory) {
        header("Location: index.php");
        exit;
    }

    // Fetch products and warehouses for dropdowns
    $product_query = "SELECT product_id, name FROM Products";
    $product_stmt = $pdo->prepare($product_query);
    $product_stmt->execute();
    $products = $product_stmt->fetchAll(PDO::FETCH_ASSOC);

    $warehouse_query = "SELECT warehouse_id, name FROM Warehouse";
    $warehouse_stmt = $pdo->prepare($warehouse_query);
    $warehouse_stmt->execute();
    $warehouses = $warehouse_stmt->fetchAll(PDO::FETCH_ASSOC);

    // Handle form submission
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $product_id = trim($_POST['product_id']);
        $warehouse_id = trim($_POST['warehouse_id']);
        $quantity = trim($_POST['quantity']);

        // Update the inventory entry
        $update_query = "UPDATE Inventory 
                         SET product_id = ?, warehouse_id = ?, quantity = ? 
                         WHERE inventory_id = ?";
        $update_stmt = $pdo->prepare($update_query);
        $update_stmt->execute([$product_id, $warehouse_id, $quantity, $id]);
        header("Location: index.php");
        exit;
    }
} catch (PDOException $e) {
    $error = "Database error: " . $e->getMessage();
}
?>

<div class="container mt-4">
    <h2>Edit Inventory Entry</h2>
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <form method="POST">
        <div class="mb-3">
            <label for="product_id" class="form-label">Product</label>
            <select name="product_id" class="form-select" required>
                <?php foreach ($products as $product): ?>
                    <option value="<?= htmlspecialchars($product['product_id']) ?>" <?= ($product['product_id'] == $inventory['product_id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($product['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="warehouse_id" class="form-label">Warehouse</label>
            <select name="warehouse_id" class="form-select" required>
                <?php foreach ($warehouses as $warehouse): ?>
                    <option value="<?= htmlspecialchars($warehouse['warehouse_id']) ?>" <?= ($warehouse['warehouse_id'] == $inventory['warehouse_id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($warehouse['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="quantity" class="form-label">Quantity</label>
            <input type="number" name="quantity" class="form-control" value="<?= htmlspecialchars($inventory['quantity']) ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>

<?php include '../../includes/footer.php'; ?>
