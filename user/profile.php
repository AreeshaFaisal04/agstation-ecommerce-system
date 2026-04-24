<?php
session_start();
require_once '../config/db.php';
require_once '../includes/header.php';

// Redirect if not logged in
if (!isset($_SESSION['customer_id'])) {
    header('Location: login.php');
    exit();
}

$customer_id = $_SESSION['customer_id'];
$errors = [];
$success = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone'] ?? '');
    $shipping_address = trim($_POST['shipping_address'] ?? '');
    $billing_address = trim($_POST['billing_address'] ?? '');

    if (empty($name) || empty($email)) {
        $errors[] = "Name and Email are required.";
    } else {
        try {
            $stmt = $pdo->prepare("UPDATE WebsiteCustomers SET name = ?, email = ?, phone = ?, shipping_address = ?, billing_address = ? WHERE customer_id = ?");
            $stmt->execute([$name, $email, $phone, $shipping_address, $billing_address, $customer_id]);
            $success = "Profile updated successfully!";
        } catch (PDOException $e) {
            $errors[] = "Failed to update profile. Try again.";
        }
    }
}

// Fetch current customer data
try {
    $stmt = $pdo->prepare("SELECT * FROM WebsiteCustomers WHERE customer_id = ?");
    $stmt->execute([$customer_id]);
    $customer = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - AGSTATION</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center mb-4">My Profile</h2>

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
            <div class="alert alert-success">
                <?= htmlspecialchars($success) ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="" class="card p-4 shadow">
            <div class="mb-3">
                <label for="name" class="form-label">Name:</label>
                <input type="text" name="name" id="name" class="form-control" value="<?= htmlspecialchars($customer['name'] ?? '') ?>" required>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email:</label>
                <input type="email" name="email" id="email" class="form-control" value="<?= htmlspecialchars($customer['email'] ?? '') ?>" required>
            </div>

            <div class="mb-3">
                <label for="phone" class="form-label">Phone:</label>
                <input type="text" name="phone" id="phone" class="form-control" value="<?= htmlspecialchars($customer['phone'] ?? '') ?>">
            </div>

            <div class="mb-3">
                <label for="shipping_address" class="form-label">Shipping Address:</label>
                <textarea name="shipping_address" id="shipping_address" class="form-control"><?= htmlspecialchars($customer['shipping_address'] ?? '') ?></textarea>
            </div>

            <div class="mb-3">
                <label for="billing_address" class="form-label">Billing Address:</label>
                <textarea name="billing_address" id="billing_address" class="form-control"><?= htmlspecialchars($customer['billing_address'] ?? '') ?></textarea>
            </div>

            <div class="d-grid">
                <button type="submit" class="btn btn-primary">Update Profile</button>
            </div>
        </form>
    </div>

    <!-- Footer -->
    <?php require_once '../includes/footer.php'; ?>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
