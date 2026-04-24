<?php
require_once '../../config/db.php';
require_once '../../includes/session.php';
require_once '../../includes/header.php';
checkRole(['admin']);

try {
    // Fetch all reviews from the database
    $query = "SELECT * FROM ProductReviews";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>

<div class="container">
    <h2>Product Reviews</h2>
    <a href="create.php" class="btn btn-success mb-3">Add Review</a>
    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Product ID</th>
                <th>Source</th>
                <th>Rating</th>
                <th>Review</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($reviews as $row): ?>
            <tr>
                <td><?= htmlspecialchars($row['review_id']) ?></td>
                <td><?= htmlspecialchars($row['product_id']) ?></td>
                <td><?= htmlspecialchars($row['source']) ?></td>
                <td><?= htmlspecialchars($row['rating']) ?></td>
                <td><?= htmlspecialchars($row['review_text']) ?></td>
                <td>
                    <a href="edit.php?id=<?= htmlspecialchars($row['review_id']) ?>" class="btn btn-primary btn-sm">Edit</a>
                    <a href="delete.php?id=<?= htmlspecialchars($row['review_id']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include '../../includes/footer.php'; ?>
