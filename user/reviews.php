<?php
include_once('../includes/session.php');
include_once('../includes/header.php');
require_once('../config/db.php');

// Use customer_id from session for WebsiteCustomers table
$customer_id = $_SESSION['customer_id'] ?? null;

if (!$customer_id) {
    echo "<div class='alert alert-danger'>Session expired. Please log in again.</div>";
    include_once('../includes/footer.php');
    exit;
}

try {
    $reviews = [];
    // Fetch reviews for products this customer has ordered (Website orders only)
    $stmt = $pdo->prepare("
        SELECT pr.*, p.name AS product_name
        FROM ProductReviews pr
        JOIN Products p ON pr.product_id = p.asin
        JOIN OrderItems oi ON oi.product_id = pr.product_id
        JOIN WebsiteOrders o ON o.order_id = oi.order_id
        WHERE pr.source = 'Website' AND o.customer_id = ?
        GROUP BY pr.review_id
        ORDER BY pr.created_at DESC
    ");
    $stmt->execute([$customer_id]);
    $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    echo "<div class='alert alert-danger'>Error: " . htmlspecialchars($e->getMessage()) . "</div>";
    include_once('../includes/footer.php');
    exit;
}
?>

<div class="container mt-5">
    <h2 class="text-center mb-4">My Reviews</h2>
    <?php if (!empty($reviews)): ?>
        <div class="list-group">
            <?php foreach ($reviews as $review): ?>
                <div class="list-group-item">
                    <h5 class="mb-1"><?= htmlspecialchars($review['product_name']) ?></h5>
                    <p class="mb-1">Rating: <?= htmlspecialchars($review['rating']) ?>/5</p>
                    <p class="mb-1">Review: <?= nl2br(htmlspecialchars($review['review_text'])) ?></p>
                    <small class="text-muted">Date: <?= htmlspecialchars($review['created_at']) ?></small>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="alert alert-warning text-center">No reviews found.</div>
    <?php endif; ?>
</div>

<?php include_once('../includes/footer.php'); ?>
