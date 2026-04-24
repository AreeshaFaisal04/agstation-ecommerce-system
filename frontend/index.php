<?php
require_once '../config/db.php';
require_once '../includes/functions.php';
require_once '../includes/session.php';
require_once '../includes/header.php';

try {
    $sql = "SELECT * FROM Products WHERE status = 'Active' ORDER BY created_at DESC LIMIT 8";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database query failed: " . $e->getMessage());
}
?>

<!-- Hero Section -->
<div class="container-fluid bg-light text-center py-5">
    <h1 class="display-4">Welcome to AGSTATION</h1>
    <p class="lead">Your one-stop shop for all goods</p>
    <a href="products.php" class="btn btn-primary btn-lg">Shop Now</a>
</div>

<!-- Product Section -->
<div class="container mt-5">
    <h2 class="text-center mb-4">Featured Products</h2>
    <div class="row">
        <?php foreach ($products as $row): ?>
            <div class="col-md-3">
                <div class="card mb-4 shadow-sm">
                    <a href="product_detail.php?asin=<?= htmlspecialchars($row['asin']) ?>">
                        <img src="../uploads/product_images/<?= htmlspecialchars($row['image_url']) ?>" class="card-img-top" alt="<?= htmlspecialchars($row['name']) ?>" style="height: 220px; object-fit: contain; background: #fff; padding: 12px;">
                    </a>
                    <div class="card-body text-center">
                        <h5 class="card-title"><?= htmlspecialchars($row['name']) ?></h5>
                        <p class="card-text text-success fw-bold">$ <?= number_format($row['retail_price'], 2) ?></p>
                        <a href="product_detail.php?asin=<?= htmlspecialchars($row['asin']) ?>" class="btn btn-outline-primary btn-sm">View Details</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Footer -->
<?php require_once '../includes/footer.php'; ?>
</body>
</html>