<?php
require_once '../config/db.php';
require_once '../includes/header.php';
require_once '../includes/navbar.php';
require_once '../includes/session.php';

$asin = $_GET['asin'] ?? '';
if (!$asin) {
    echo "<div class='container mt-4'><div class='alert alert-danger text-center'>Product not found.</div></div>";
    require_once '../includes/footer.php';
    exit;
}

try {
    // Fetch product details with brand and distributor names
    $sql = "SELECT p.*, b.name AS brand_name, d.name AS distributor_name
            FROM Products p
            LEFT JOIN Brands b ON p.brand_id = b.brand_id
            LEFT JOIN Distributors d ON p.distributor_id = d.distributor_id
            WHERE p.asin = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$asin]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$product) {
        echo "<div class='container mt-4'><div class='alert alert-danger text-center'>Product not found.</div></div>";
        require_once '../includes/footer.php';
        exit;
    }
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($product['name']) ?> - AGSTATION</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .product-detail-flex {
            background: #fff;
            border-radius: 18px;
            box-shadow: 0 2px 12px rgba(59,91,219,0.08);
            padding: 40px 32px;
            margin-top: 40px;
            display: flex;
            gap: 48px;
            align-items: flex-start;
            max-width: 1100px;
            margin-left: auto;
            margin-right: auto;
        }
        .product-detail-img-col {
            flex: 0 0 400px;
            max-width: 400px;
        }
        .main-product-img {
            width: 100%;
            height: 400px;
            object-fit: contain;
            background: #fff;
            border-radius: 18px;
            box-shadow: 0 2px 12px rgba(59,91,219,0.08);
            padding: 18px;
        }
        .product-detail-info-col {
            flex: 1 1 0;
        }
        .product-detail-title {
            color: #222;
            font-weight: 700;
            font-size: 2.2rem;
            margin-bottom: 18px;
            line-height: 1.2;
            text-align: left;
        }
        .product-meta-label {
            font-weight: 600;
            color: #3b5bdb;
            min-width: 110px;
            display: inline-block;
        }
        .product-meta-value {
            color: #222;
        }
        .product-detail-price {
            color: #222; /* Changed to black */
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 18px;
            text-align: left;
        }
        .product-detail-desc-label {
            font-weight: 600;
            color: #3b5bdb;
            margin-bottom: 6px;
            display: block;
        }
        .product-detail-desc {
            color: #444;
            font-size: 1.08rem;
            text-align: left;
        }
        .add-to-cart-section {
            margin-top: 32px;
            text-align: left;
        }
        .input-group .form-control {
            max-width: 70px;
            text-align: center;
        }
        @media (max-width: 991px) {
            .product-detail-flex {
                flex-direction: column;
                padding: 18px;
                gap: 24px;
            }
            .product-detail-img-col {
                max-width: 100%;
            }
            .main-product-img {
                height: 220px;
            }
        }
        @media (max-width: 767px) {
            .product-detail-title { font-size: 1.4rem; }
            .product-detail-price { font-size: 1.3rem; }
        }
    </style>
</head>
<body>
<div class="container-fluid" style="background:#f4f6fb;min-height:100vh;">
    <div class="product-detail-flex">
        <!-- Product Image -->
        <div class="product-detail-img-col">
            <img src="../uploads/product_images/<?= htmlspecialchars($product['image_url']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="main-product-img">
        </div>
        <!-- Product Details -->
        <div class="product-detail-info-col">
            <h1 class="product-detail-title"><?= htmlspecialchars($product['name']) ?></h1>
            <div class="product-detail-price mb-3" style="color:#222;">
                Price: $ <?= number_format($product['retail_price'], 2) ?>
            </div>
            <div class="mb-2">
                <?php if ($product['brand_name']): ?>
                    <span class="product-meta-label">Brand:</span>
                    <span class="product-meta-value"><?= htmlspecialchars($product['brand_name']) ?></span>
                <?php elseif ($product['distributor_name']): ?>
                    <span class="product-meta-label">Distributor:</span>
                    <span class="product-meta-value"><?= htmlspecialchars($product['distributor_name']) ?></span>
                <?php endif; ?>
            </div>
            <div class="mb-2">
                <span class="product-meta-label">Category:</span>
                <span class="product-meta-value"><?= htmlspecialchars($product['category']) ?></span>
            </div>
            <div class="mb-2">
                <span class="product-meta-label">ASIN:</span>
                <span class="product-meta-value"><?= htmlspecialchars($product['asin']) ?></span>
            </div>
            <div class="mb-2">
                <span class="product-meta-label">UPC:</span>
                <span class="product-meta-value"><?= htmlspecialchars($product['upc']) ?></span>
            </div>
            <div class="mb-2">
                <span class="product-meta-label">Status:</span>
                <span class="product-meta-value"><?= htmlspecialchars($product['status']) ?></span>
            </div>
            <div class="mb-3 mt-3">
                <span class="product-detail-desc-label">Description:</span>
                <span class="product-detail-desc"><?= nl2br(htmlspecialchars($product['description'])) ?></span>
            </div>
            <form action="cart.php" method="post" class="add-to-cart-section">
                <input type="hidden" name="asin" value="<?= htmlspecialchars($product['asin']) ?>">
                <div class="input-group mb-3" style="max-width: 180px;">
                    <input type="number" name="quantity" class="form-control" value="1" min="1" max="99">
                    <button type="submit" name="add_to_cart" class="btn btn-primary">Add to Cart</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
