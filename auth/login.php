<?php
require_once '../config/db.php';
require_once '../includes/session.php';

$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email    = trim($_POST['email']);
    $password = trim($_POST['password']);

    try {
        // Try logging in as an admin/staff (Users table)
        $stmt = $pdo->prepare("SELECT * FROM Users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password_hash'])) {
            session_regenerate_id(true);
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['role_id'] = $user['role_id'];
            $_SESSION['name']    = $user['name'];
            if ($user['role_id'] == 1) {
                header("Location: /admin/dashboard.php");
            } else {
                header("Location: /frontend/index.php");
            }
            exit();
        }

        // If not found in Users, try WebsiteCustomers (for customer login)
        $stmt = $pdo->prepare("SELECT * FROM WebsiteCustomers WHERE email = ?");
        $stmt->execute([$email]);
        $customer = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($customer && password_verify($password, $customer['password_hash'])) {
            session_regenerate_id(true);
            $_SESSION['customer_id'] = $customer['customer_id'];
            $_SESSION['name'] = $customer['name'];
            header("Location: /frontend/index.php");
            exit();
        }

        $error = "Invalid email or password.";
    } catch (PDOException $e) {
        $error = "Database error: " . $e->getMessage();
    }
}

require_once '../includes/header.php';
?>

<div class="container mt-5">
    <h2 class="text-center mb-4">Login</h2>

    <?php if ($error): ?>
        <div class="alert alert-danger text-center"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" class="card p-4 shadow">
        <div class="mb-3">
            <label for="email" class="form-label">Email:</label>
            <input type="email" name="email" id="email" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password:</label>
            <input type="password" name="password" id="password" class="form-control" required>
        </div>
        <div class="d-grid">
            <button class="btn btn-primary" type="submit">Login</button>
        </div>
    </form>

    <div class="text-center mt-3">
        <p>Don't have an account? <a href="../frontend/register.php">Register here</a></p>
    </div>
</div>

<?php include_once('../includes/footer.php'); ?>
