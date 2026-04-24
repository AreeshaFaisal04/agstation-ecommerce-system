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
    // Fetch the return details
    $query = "SELECT * FROM Returns WHERE return_id = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$id]);
    $data = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$data) {
        header("Location: index.php");
        exit;
    }

    // Handle form submission
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $status = trim($_POST['status']);
        $resolution = trim($_POST['resolution']);
        $refund_amount = trim($_POST['refund_amount']);

        // Update the return record
        $update_query = "UPDATE Returns SET status = ?, resolution = ?, refund_amount = ? WHERE return_id = ?";
        $update_stmt = $pdo->prepare($update_query);
        $update_stmt->execute([$status, $resolution, $refund_amount, $id]);
        header("Location: index.php");
        exit;
    }
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>

<div class="container">
    <h2>Edit Return</h2>
    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Status</label>
            <select name="status" class="form-select">
                <option value="Requested" <?= $data['status'] == 'Requested' ? 'selected' : '' ?>>Requested</option>
                <option value="Approved" <?= $data['status'] == 'Approved' ? 'selected' : '' ?>>Approved</option>
                <option value="Rejected" <?= $data['status'] == 'Rejected' ? 'selected' : '' ?>>Rejected</option>
                <option value="Refunded" <?= $data['status'] == 'Refunded' ? 'selected' : '' ?>>Refunded</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Resolution</label>
            <select name="resolution" class="form-select">
                <option value="Refund" <?= $data['resolution'] == 'Refund' ? 'selected' : '' ?>>Refund</option>
                <option value="Replacement" <?= $data['resolution'] == 'Replacement' ? 'selected' : '' ?>>Replacement</option>
                <option value="Store Credit" <?= $data['resolution'] == 'Store Credit' ? 'selected' : '' ?>>Store Credit</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Refund Amount</label>
            <input type="number" step="0.01" name="refund_amount" value="<?= htmlspecialchars($data['refund_amount']) ?>" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>

<?php include '../../includes/footer.php'; ?>
