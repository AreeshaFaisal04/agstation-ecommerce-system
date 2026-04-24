<?php
require_once '../../config/db.php';
require_once '../../includes/session.php';
require_once '../../includes/header.php';
checkRole(['admin']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = trim($_POST['product_id']);
    $source = trim($_POST['source']);
    $rating = trim($_POST['rating']);
    $review_text = trim($_POST['review_text']);

    try {
        // Insert the review into the database
        $query = "INSERT INTO ProductReviews (product_id, source, rating, review_text, review_date) 
                  VALUES (?, ?, ?, ?, NOW())";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$product_id, $source, $rating, $review_text]);
        header("Location: index.php");
        exit;
    } catch (PDOException $e) {
        $error = "Database error: " . $e->getMessage();
    }
}
?>

<div class="container">
    <h2>Add Product Review</h2>
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Product ID</label>
            <input type="text" name="product_id" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Source</label>
            <select name="source" class="form-select">
                <option value="Amazon">Amazon</option>
                <option value="Website">Website</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Rating (1-5)</label>
            <input type="number" name="rating" min="1" max="5" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Review Text</label>
            <textarea name="review_text" class="form-control"></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>

<?php include '../../includes/footer.php'; ?>
