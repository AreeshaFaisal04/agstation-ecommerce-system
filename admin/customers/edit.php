<?php
// filepath: d:\agstation\admin\customers\edit.php
include '../../includes/session.php'; 
include '../../config/db.php'; 

// Ensure the user has admin privileges
checkRole(['Admin', 'admin', 1]);

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    header("Location: index.php");
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT * FROM WebsiteCustomers WHERE customer_id = ?");
    $stmt->execute([$id]);
    $customer = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$customer) {
        header("Location: index.php");
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name = trim($_POST['name']);
        $email = trim($_POST['email']);
        $phone = trim($_POST['phone']);

        $stmt = $pdo->prepare("UPDATE WebsiteCustomers SET name = ?, email = ?, phone = ? WHERE customer_id = ?");
        $stmt->execute([$name, $email, $phone, $id]);
        header("Location: index.php");
        exit;
    }
} catch (PDOException $e) {
    $error = "Error updating customer: " . $e->getMessage();
}

include '../../includes/header.php';
?>

<div class="container mt-4">
    <h2>Edit Customer</h2>
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <form method="POST">
        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" class="form-control" name="name" value="<?= htmlspecialchars($customer['name'] ?? '') ?>" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" name="email" value="<?= htmlspecialchars($customer['email'] ?? '') ?>" required>
        </div>
        <div class="mb-3">
            <label for="phone" class="form-label">Phone</label>
            <input type="text" class="form-control" name="phone" value="<?= htmlspecialchars($customer['phone'] ?? '') ?>">
        </div>
        <button type="submit" class="btn btn-primary">Update Customer</button>
        <a href="index.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>

<?php include '../../includes/footer.php'; ?>