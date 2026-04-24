<?php 
require_once '../../config/db.php';
require_once '../../includes/session.php';
require_once '../../includes/header.php';
checkRole(['admin', 'Admin', 1]);

try {
    // Fetch all products with brand and distributor names
    $query = "SELECT 
                p.*, 
                b.name AS brand_name, 
                d.name AS distributor_name 
              FROM Products p
              LEFT JOIN Brands b ON p.brand_id = b.brand_id
              LEFT JOIN Distributors d ON p.distributor_id = d.distributor_id
              ORDER BY p.asin DESC";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>

<div class="container">
    <h2>All Products</h2>
    <a href="create.php" class="btn btn-success mb-3">Add New Product</a>

    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>ASIN</th>
                <th>Name</th>
                <th>Cost Price</th>
                <th>Retail Price</th>
                <th>Brand</th>
                <th>Distributor</th>
                <th>Image</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($products as $row): ?>
            <tr>
                <td><?= htmlspecialchars($row['asin']) ?></td>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td><?= htmlspecialchars($row['cost_price']) ?></td>
                <td><?= htmlspecialchars($row['retail_price']) ?></td>
                <td><?= $row['brand_name'] ? htmlspecialchars($row['brand_name']) : '<span class="text-muted">None</span>' ?></td>
                <td><?= $row['distributor_name'] ? htmlspecialchars($row['distributor_name']) : '<span class="text-muted">None</span>' ?></td>
                <td>
                    <?php if (!empty($row['image_url'])): ?>
                        <img src="../../uploads/product_images/<?= htmlspecialchars($row['image_url']) ?>" width="60">
                    <?php endif; ?>
                </td>
                <td><?= htmlspecialchars($row['status']) ?></td>
                <td>
                    <a href="edit.php?asin=<?= urlencode($row['asin']) ?>" class="btn btn-primary btn-sm">Edit</a>
                    <a href="delete.php?asin=<?= urlencode($row['asin']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this product?');">Delete</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include '../../includes/footer.php'; ?>
