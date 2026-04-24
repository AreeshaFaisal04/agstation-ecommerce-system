<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Redirect to login if the customer is not logged in (for customer pages)
function requireCustomerLogin() {
    if (!isset($_SESSION['customer_id'])) {
        redirectToLogin();
    }
}

// Redirect to login if the user is not logged in (for admin/staff)
function isLoggedIn() {
    if (!isset($_SESSION['user_id'])) {
        redirectToLogin();
    }
}

// Check if the logged-in user has the required role(s) (by role name or role id)
function checkRole($allowedRoles = []) {
    if (!isset($_SESSION['user_id'])) {
        redirectToLogin();
    }
    if (isset($_SESSION['role_name']) && in_array($_SESSION['role_name'], $allowedRoles)) {
        return true;
    }
    if (isset($_SESSION['role_id']) && in_array($_SESSION['role_id'], $allowedRoles)) {
        return true;
    }
    showAccessDenied();
}

// Destroy session and log out the user
function logout() {
    if (session_status() === PHP_SESSION_ACTIVE) {
        session_unset();
        session_destroy();
    }
    redirectToLogin();
}

// Helper function for safe redirect
function redirectToLogin() {
    if (!headers_sent()) {
        header('Location: /auth/login.php');
        exit;
    } else {
        echo '<script>window.location.href = "/auth/login.php";</script>';
        exit;
    }
}

// Show access denied message and exit
function showAccessDenied() {
    if (!headers_sent()) {
        http_response_code(403);
    }
    echo '<div style="margin:2rem auto;max-width:500px;padding:2rem;background:#fff3cd;border:1px solid #ffeeba;border-radius:8px;text-align:center;color:#856404;font-size:1.2rem;">
        <strong>Access Denied:</strong> You do not have permission to access this page.
        <br><a href="/admin/dashboard.php" style="color:#3D52A0;text-decoration:underline;">Return to Dashboard</a>
    </div>';
    if (file_exists(__DIR__ . '/footer.php')) {
        include __DIR__ . '/footer.php';
    }
    exit;
}
?>
