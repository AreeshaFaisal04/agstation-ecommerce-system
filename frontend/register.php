<?php
// filepath: d:\agstation\frontend\register.php
require_once '../config/db.php';
require_once '../includes/header.php';

$admin_errors = [];
$admin_success = '';
$customer_errors = [];
$customer_success = '';

// Handle Admin Registration
if (isset($_POST['register_admin'])) {
    $admin_name = trim($_POST['admin_name']);
    $admin_email = trim($_POST['admin_email']);
    $admin_password = trim($_POST['admin_password']);

    if (empty($admin_name) || empty($admin_email) || empty($admin_password)) {
        $admin_errors[] = "All fields are required.";
    } elseif (!filter_var($admin_email, FILTER_VALIDATE_EMAIL)) {
        $admin_errors[] = "Invalid email format.";
    } else {
        try {
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM Users WHERE email = ?");
            $stmt->execute([$admin_email]);
            if ($stmt->fetchColumn() > 0) {
                $admin_errors[] = "Email is already registered.";
            } else {
                // Get or create Admin role
                $roleStmt = $pdo->prepare("SELECT role_id FROM Roles WHERE role_name = 'Admin'");
                $roleStmt->execute();
                $roleId = $roleStmt->fetchColumn();
                if (!$roleId) {
                    $createRoleStmt = $pdo->prepare("INSERT INTO Roles (role_name) VALUES ('Admin')");
                    $createRoleStmt->execute();
                    $roleId = $pdo->lastInsertId();
                }
                $password_hash = password_hash($admin_password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("INSERT INTO Users (name, email, password_hash, role_id) VALUES (?, ?, ?, ?)");
                $stmt->execute([$admin_name, $admin_email, $password_hash, $roleId]);
                $admin_success = "Admin registration successful. <a href='../auth/login.php'>Login here</a>.";
            }
        } catch (PDOException $e) {
            $admin_errors[] = "Database error: " . $e->getMessage();
        }
    }
}

// Handle Customer Registration
if (isset($_POST['register_customer'])) {
    $customer_name = trim($_POST['customer_name']);
    $customer_email = trim($_POST['customer_email']);
    $customer_password = trim($_POST['customer_password']);
    $customer_phone = trim($_POST['customer_phone'] ?? '');
    $customer_shipping_address = trim($_POST['customer_shipping_address'] ?? '');
    $customer_billing_address = trim($_POST['customer_billing_address'] ?? '');

    if (empty($customer_name) || empty($customer_email) || empty($customer_password)) {
        $customer_errors[] = "All fields marked with * are required.";
    } elseif (!filter_var($customer_email, FILTER_VALIDATE_EMAIL)) {
        $customer_errors[] = "Invalid email format.";
    } else {
        try {
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM WebsiteCustomers WHERE email = ?");
            $stmt->execute([$customer_email]);
            if ($stmt->fetchColumn() > 0) {
                $customer_errors[] = "Email is already registered.";
            } else {
                $password_hash = password_hash($customer_password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("INSERT INTO WebsiteCustomers (name, email, password_hash, phone, shipping_address, billing_address) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->execute([$customer_name, $customer_email, $password_hash, $customer_phone, $customer_shipping_address, $customer_billing_address]);
                $customer_success = "Registration successful. <a href='../auth/login.php'>Login here</a>.";
            }
        } catch (PDOException $e) {
            $customer_errors[] = "Database error: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - AGSTATION</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .register-split {
            display: flex;
            flex-wrap: wrap;
            gap: 2rem;
            justify-content: center;
        }
        .register-card {
            flex: 1 1 350px;
            max-width: 450px;
            min-width: 300px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            padding: 2rem;
        }
        @media (max-width: 900px) {
            .register-split { flex-direction: column; align-items: center; }
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center mb-4">Register</h2>
        <div class="register-split">
            <!-- Admin Registration -->
            <div class="register-card">
                <h4 class="mb-3 text-center">Admin Registration</h4>
                <?php if (!empty($admin_errors)): ?>
                    <div class="alert alert-danger">
                        <ul>
                            <?php foreach ($admin_errors as $error): ?>
                                <li><?= htmlspecialchars($error) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
                <?php if ($admin_success): ?>
                    <div class="alert alert-success text-center"><?= $admin_success ?></div>
                <?php else: ?>
                    <form method="POST" autocomplete="off">
                        <div class="mb-3">
                            <label for="admin_name" class="form-label">Name*:</label>
                            <input type="text" name="admin_name" id="admin_name" class="form-control" required value="<?= isset($admin_name) ? htmlspecialchars($admin_name) : '' ?>">
                        </div>
                        <div class="mb-3">
                            <label for="admin_email" class="form-label">Email*:</label>
                            <input type="email" name="admin_email" id="admin_email" class="form-control" required value="<?= isset($admin_email) ? htmlspecialchars($admin_email) : '' ?>">
                        </div>
                        <div class="mb-3">
                            <label for="admin_password" class="form-label">Password*:</label>
                            <input type="password" name="admin_password" id="admin_password" class="form-control" required>
                        </div>
                        <div class="d-grid">
                            <button type="submit" name="register_admin" class="btn btn-primary">Register as Admin</button>
                        </div>
                    </form>
                <?php endif; ?>
            </div>
            <!-- Customer Registration -->
            <div class="register-card">
                <h4 class="mb-3 text-center">Customer Registration</h4>
                <?php if (!empty($customer_errors)): ?>
                    <div class="alert alert-danger">
                        <ul>
                            <?php foreach ($customer_errors as $error): ?>
                                <li><?= htmlspecialchars($error) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
                <?php if ($customer_success): ?>
                    <div class="alert alert-success text-center"><?= $customer_success ?></div>
                <?php else: ?>
                    <form method="POST" autocomplete="off">
                        <div class="mb-3">
                            <label for="customer_name" class="form-label">Name*:</label>
                            <input type="text" name="customer_name" id="customer_name" class="form-control" required value="<?= isset($customer_name) ? htmlspecialchars($customer_name) : '' ?>">
                        </div>
                        <div class="mb-3">
                            <label for="customer_email" class="form-label">Email*:</label>
                            <input type="email" name="customer_email" id="customer_email" class="form-control" required value="<?= isset($customer_email) ? htmlspecialchars($customer_email) : '' ?>">
                        </div>
                        <div class="mb-3">
                            <label for="customer_password" class="form-label">Password*:</label>
                            <input type="password" name="customer_password" id="customer_password" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="customer_phone" class="form-label">Phone:</label>
                            <input type="text" name="customer_phone" id="customer_phone" class="form-control" value="<?= isset($customer_phone) ? htmlspecialchars($customer_phone) : '' ?>">
                        </div>
                        <div class="mb-3">
                            <label for="customer_shipping_address" class="form-label">Shipping Address:</label>
                            <textarea name="customer_shipping_address" id="customer_shipping_address" class="form-control"><?= isset($customer_shipping_address) ? htmlspecialchars($customer_shipping_address) : '' ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="customer_billing_address" class="form-label">Billing Address:</label>
                            <textarea name="customer_billing_address" id="customer_billing_address" class="form-control"><?= isset($customer_billing_address) ? htmlspecialchars($customer_billing_address) : '' ?></textarea>
                        </div>
                        <div class="d-grid">
                            <button type="submit" name="register_customer" class="btn btn-success">Register as Customer</button>
                        </div>
                    </form>
                <?php endif; ?>
            </div>
        </div>
        <div class="mt-4 text-center">
            <p>Already have an account? <a href="../auth/login.php">Login here</a></p>
        </div>
    </div>
    <?php require_once '../includes/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
