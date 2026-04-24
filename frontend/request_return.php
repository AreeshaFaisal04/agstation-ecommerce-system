<?php
session_start();
require_once '../config/db.php';
require_once '../includes/header.php';

if (!isset($_SESSION['customer_id'])) {
    header('Location: login.php');
    exit();
}

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_id = trim($_POST['order_id']);
    $product_id = trim($_POST['product_id']);
    $reason = trim($_POST['reason']);
    $resolution = trim($_POST['resolution']);
    $refund_amount = trim($_POST['refund_amount']);

    if (empty($order_id) || empty($product_id) || empty($reason) || empty($resolution)) {
        $errors[] = "All fields are required.";
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO Returns (order_id, product_id, reason, resolution, refund_amount, status, return_date) 
                                   VALUES (?, ?, ?, ?, ?, 'Requested', NOW())");
            $stmt->execute([$order_id, $product_id, $reason, $resolution, $refund_amount]);
            $success = "Your return request has been submitted. Our team will review it shortly.";
        } catch (PDOException $e) {
            $errors[] = "Database error: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request a Return - AGSTATION</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center mb-4">Request a Return</h2>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?= htmlspecialchars($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="alert alert-success text-center">
                <?= htmlspecialchars($success) ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="" class="card p-4 shadow">
            <div class="mb-3">
                <label for="order_id" class="form-label">Order ID:</label>
                <input type="number" name="order_id" id="order_id" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="product_id" class="form-label">Product ID:</label>
                <input type="text" name="product_id" id="product_id" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="reason" class="form-label">Reason:</label>
                <textarea name="reason" id="reason" class="form-control" rows="3" required></textarea>
            </div>

            <div class="mb-3">
                <label for="resolution" class="form-label">Resolution:</label>
                <select name="resolution" id="resolution" class="form-select" required>
                    <option value="Refund">Refund</option>
                    <option value="Replacement">Replacement</option>
                    <option value="Store Credit">Store Credit</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="refund_amount" class="form-label">Estimated Refund Amount:</label>
                <input type="number" name="refund_amount" id="refund_amount" class="form-control" step="0.01">
            </div>

            <div class="d-grid">
                <button type="submit" class="btn btn-primary">Submit Return Request</button>
            </div>
        </form>
    </div>

    <!-- Footer -->
    <?php require_once '../includes/footer.php'; ?>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
