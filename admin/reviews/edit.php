<?php
require_once '../../config/db.php';
require_once '../../includes/session.php';
require_once '../../includes/header.php';
checkRole(['admin']);

// Validate and sanitize the ID parameter
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    header("Location: index.php");
    exit;
}

try {
    // Fetch the review details
    $query = "SELECT * FROM ProductReviews WHERE review_id = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$id]);
    $data = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$data) {
        header("Location: index.php");
        exit;
    }

    // Handle form submission
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $rating = trim($_POST['rating']);
        $review_text = trim($_POST['review_text']);

        // Update the review in the database
        $update_query = "UPDATE ProductReviews SET rating = ?, review_text = ? WHERE review_id = ?";
        $update_stmt = $pdo->prepare($update_query);
        $update_stmt->execute([$rating, $review_text, $id]);
        header("Location: index.php");
        exit;
    }
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>

<div class="container">
    <h2>Edit Review</h2>
    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Rating (1-5)</label>
            <input type="number" name="rating" min="1" max="5" value="<?= htmlspecialchars($data['rating']) ?>" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Review Text</label>
            <textarea name="review_text" class="form-control"><?= htmlspecialchars($data['review_text']) ?></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>

<?php include '../../includes/footer.php'; ?>
