<?php 
require_once '../../config/db.php';
require_once '../../includes/session.php';

checkRole(['admin', 'Admin', 1]);

// Validate and sanitize the ASIN parameter
$asin = isset($_GET['asin']) ? trim($_GET['asin']) : '';
if (!$asin) {
    header("Location: index.php");
    exit;
}

// Fetch brands and distributors for dropdowns
$brands = $pdo->query("SELECT brand_id, name FROM Brands WHERE status='Active'")->fetchAll(PDO::FETCH_ASSOC);
$distributors = $pdo->query("SELECT distributor_id, name FROM Distributors WHERE status='Active'")->fetchAll(PDO::FETCH_ASSOC);

// Fetch the product details BEFORE output
$query = "SELECT * FROM Products WHERE asin = ?";
$stmt = $pdo->prepare($query);
$stmt->execute([$asin]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    header("Location: index.php");
    exit;
}

// Handle form submission BEFORE any HTML output
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $upc = trim($_POST['upc']);
    $brand_id = !empty($_POST['brand_id']) ? $_POST['brand_id'] : null;
    $distributor_id = !empty($_POST['distributor_id']) ? $_POST['distributor_id'] : null;
    $category = trim($_POST['category']);
    $cost_price = trim($_POST['cost_price']);
    $retail_price = trim($_POST['retail_price']);
    $description = trim($_POST['description']);
    $status = trim($_POST['status']);

    // Handle file upload
    $image_url = $product['image_url'];
    if (!empty($_FILES['image']['name'])) {
        $uploadDir = '../../uploads/product_images/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        $imageName = uniqid('prod_') . '_' . basename($_FILES['image']['name']);
        $imagePath = $uploadDir . $imageName;
        if (move_uploaded_file($_FILES['image']['tmp_name'], $imagePath)) {
            $image_url = $imageName;
        } else {
            $error = "Failed to upload the product image.";
        }
    }

    if (!isset($error)) {
        try {
            $update_query = "UPDATE Products SET 
                name = ?, upc = ?, brand_id = ?, distributor_id = ?, category = ?, cost_price = ?, retail_price = ?, description = ?, image_url = ?, status = ?
                WHERE asin = ?";
            $update_stmt = $pdo->prepare($update_query);
            $update_stmt->execute([
                $name,
                $upc,
                $brand_id ?: null,
                $distributor_id ?: null,
                $category,
                $cost_price,
                $retail_price,
                $description,
                $image_url,
                $status,
                $asin
            ]);
            // Redirect BEFORE any HTML output
            header("Location: index.php");
            exit;
        } catch (PDOException $e) {
            $error = "Database error: " . $e->getMessage();
        }
    }
}

// Now include header after all redirects and before HTML output
include_once '../../includes/header.php';
?>

<div class="container">
    <h2>Edit Product</h2>
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label class="form-label">ASIN</label>
            <input type="text" name="asin" value="<?= htmlspecialchars($product['asin']) ?>" class="form-control" readonly>
        </div>
        <div class="mb-3">
            <label class="form-label">Product Name</label>
            <input type="text" name="name" value="<?= htmlspecialchars($product['name']) ?>" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">UPC</label>
            <input type="text" name="upc" value="<?= htmlspecialchars($product['upc']) ?>" class="form-control">
        </div>
        <div class="mb-3">
            <label class="form-label">Brand</label>
            <select name="brand_id" class="form-select">
                <option value="">None</option>
                <?php foreach ($brands as $brand): ?>
                    <option value="<?= $brand['brand_id'] ?>" <?= ($product['brand_id'] == $brand['brand_id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($brand['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Distributor</label>
            <select name="distributor_id" class="form-select">
                <option value="">None</option>
                <?php foreach ($distributors as $distributor): ?>
                    <option value="<?= $distributor['distributor_id'] ?>" <?= ($product['distributor_id'] == $distributor['distributor_id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($distributor['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Category</label>
            <input type="text" name="category" value="<?= htmlspecialchars($product['category']) ?>" class="form-control">
        </div>
        <div class="mb-3">
            <label class="form-label">Cost Price</label>
            <input type="number" step="0.01" name="cost_price" value="<?= htmlspecialchars($product['cost_price']) ?>" class="form-control">
        </div>
        <div class="mb-3">
            <label class="form-label">Retail Price</label>
            <input type="number" step="0.01" name="retail_price" value="<?= htmlspecialchars($product['retail_price']) ?>" class="form-control">
        </div>
        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control"><?= htmlspecialchars($product['description']) ?></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Product Image</label><br>
            <?php if (!empty($product['image_url'])): ?>
                <img src="../../uploads/product_images/<?= htmlspecialchars($product['image_url']) ?>" width="80"><br>
            <?php endif; ?>
            <input type="file" name="image" class="form-control mt-2">
        </div>
        <div class="mb-3">
            <label class="form-label">Status</label>
            <select name="status" class="form-select">
                <option value="Active" <?= $product['status'] == 'Active' ? 'selected' : '' ?>>Active</option>
                <option value="Inactive" <?= $product['status'] == 'Inactive' ? 'selected' : '' ?>>Inactive</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Update Product</button>
        <a href="index.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>

<?php include '../../includes/footer.php'; ?>
