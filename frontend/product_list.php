<?php
require_once '../config/db.php';
require_once '../includes/header.php';
require_once '../includes/navbar.php';

$category = $_GET['category'] ?? '';

try {
    // Fetch products based on category or all active products
    $sql = "SELECT * FROM Products WHERE status = 'Active'";
    if ($category) {
        $sql .= " AND category = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$category]);
    } else {
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
    }
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Catalog - AGSTATION</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center mb-4">Product Catalog</h2>

        <!-- Category Filter -->
        <form method="get" class="mb-4">
            <div class="row">
                <div class="col-md-4 offset-md-4">
                    <select name="category" class="form-select" onchange="this.form.submit()">
                        <option value="">All Categories</option>
                        <option value="Art Craft & Sewing" <?= $category === 'Fertilizers' ? 'selected' : '' ?>>Fertilizers</option>
                        <option value="Seeds" <?= $category === 'Seeds' ? 'selected' : '' ?>>Seeds</option>
                        <option value="Tools" <?= $category === 'Tools' ? 'selected' : '' ?>>Tools</option>
                    </select>
                </div>
            </div>
        </form>

        <!-- Product Grid -->
        <div class="row">
            <?php if (!empty($products)): ?>
                <?php foreach ($products as $product): ?>
                    <div class="col-md-3 d-flex">
                        <div class="card mb-4 shadow-sm w-100">
                            <a href="product_detail.php?asin=<?= htmlspecialchars($product['asin']) ?>">
                                <img src="../uploads/product_images/<?= htmlspecialchars($product['image_url']) ?>" class="card-img-top" alt="<?= htmlspecialchars($product['name']) ?>">
                            </a>
                            <div class="card-body text-center">
                                <h5 class="card-title"><?= htmlspecialchars($product['name']) ?></h5>
                                <p class="card-text text-success fw-bold">$ <?= number_format($product['retail_price'], 2) ?></p>
                                <a href="product_detail.php?asin=<?= htmlspecialchars($product['asin']) ?>" class="btn btn-outline-primary btn-sm">View Details</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="alert alert-warning text-center">No products found in this category.</div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Footer -->
    <?php require_once '../includes/footer.php'; ?>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
