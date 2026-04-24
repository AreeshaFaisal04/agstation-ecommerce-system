<?php
require_once '../config/db.php';
require_once '../includes/session.php';
require_once '../includes/header.php';

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = trim($_POST['name']);
    $email    = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($name) || empty($email) || empty($password)) {
        $errors[] = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    } else {
        try {
            // Check if registering as admin/staff or customer
            $isAdmin = (stripos($email, '@allgoodsstation.com') !== false);

            if ($isAdmin) {
                // Admin/Staff registration
                $stmt = $pdo->prepare("SELECT COUNT(*) FROM Users WHERE email = ?");
                $stmt->execute([$email]);
                if ($stmt->fetchColumn() > 0) {
                    $errors[] = "Email already registered as admin/staff.";
                } else {
                    // Get or create Admin role
                    $roleStmt = $pdo->prepare("SELECT role_id FROM Roles WHERE role_name = 'Admin'");
                    $roleStmt->execute();
                    $role_id = $roleStmt->fetchColumn();
                    if (!$role_id) {
                        $pdo->prepare("INSERT INTO Roles (role_name) VALUES ('Admin')")->execute();
                        $role_id = $pdo->lastInsertId();
                    }
                    $password_hash = password_hash($password, PASSWORD_DEFAULT);
                    $stmt = $pdo->prepare("INSERT INTO Users (name, email, password_hash, role_id, status) VALUES (?, ?, ?, ?, 'Active')");
                    $stmt->execute([$name, $email, $password_hash, $role_id]);
                    $success = "Admin registration successful. <a href='../auth/login.php'>Login here</a>.";
                }
            } else {
                // Customer registration
                $stmt = $pdo->prepare("SELECT COUNT(*) FROM WebsiteCustomers WHERE email = ?");
                $stmt->execute([$email]);
                if ($stmt->fetchColumn() > 0) {
                    $errors[] = "Email already registered as customer.";
                } else {
                    $password_hash = password_hash($password, PASSWORD_DEFAULT);
                    $stmt = $pdo->prepare("INSERT INTO WebsiteCustomers (name, email, password_hash) VALUES (?, ?, ?)");
                    $stmt->execute([$name, $email, $password_hash]);
                    $success = "Customer registration successful. <a href='../auth/login.php'>Login here</a>.";
                }
            }
        } catch (PDOException $e) {
            $errors[] = "Database error: " . $e->getMessage();
        }
    }
}
?>

<div class="container mt-5">
    <h2 class="text-center mb-4">Register</h2>

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
            <?= $success ?>
        </div>
    <?php else: ?>
        <div class="card p-4 shadow">
            <form method="POST" action="">
                <div class="mb-3">
                    <label for="name" class="form-label">Name:</label>
                    <input type="text" name="name" id="name" class="form-control" required value="<?= isset($name) ? htmlspecialchars($name) : '' ?>">
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email:</label>
                    <input type="email" name="email" id="email" class="form-control" required value="<?= isset($email) ? htmlspecialchars($email) : '' ?>">
                    <small class="form-text text-muted">
                        Use your @allgoodsstation.com email to register as admin/staff. Others will be registered as customers.
                    </small>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password:</label>
                    <input type="password" name="password" id="password" class="form-control" required>
                </div>
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">Register</button>
                </div>
            </form>
            <div class="mt-3 text-center">
                <p>Already have an account? <a href="../auth/login.php">Login here</a></p>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php require_once '../includes/footer.php'; ?>