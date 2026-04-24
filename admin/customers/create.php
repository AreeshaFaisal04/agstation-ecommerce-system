<?php
// filepath: d:\agstation\admin\customers\create.php
include '../../includes/session.php'; 
include '../../config/db.php'; 

// Ensure the user has admin privileges (case-insensitive)
checkRole(['Admin', 'admin', 1]); // Add 1 if your admin role_id is 1

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $password = password_hash(trim($_POST['password']), PASSWORD_BCRYPT);

    try {
        $stmt = $pdo->prepare("INSERT INTO WebsiteCustomers (name, email, phone, password_hash) VALUES (?, ?, ?, ?)");
        $stmt->execute([$name, $email, $phone, $password]);
        header("Location: index.php");
        exit;
    } catch (PDOException $e) {
        $error = "Error adding customer: " . $e->getMessage();
    }
}

include '../../includes/header.php';
?>

<div class="container mt-4">
    <h2>Add New Customer</h2>
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <form method="POST">
        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" class="form-control" name="name" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" name="email" required>
        </div>
        <div class="mb-3">
            <label for="phone" class="form-label">Phone</label>
            <input type="text" class="form-control" name="phone">
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" name="password" required>
        </div>
        <button type="submit" class="btn btn-primary">Add Customer</button>
        <a href="index.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>

<?php include '../../includes/footer.php'; ?>