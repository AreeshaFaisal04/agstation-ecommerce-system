<?php
// Redirect to a specific URL
function redirect($url) {
    if (!headers_sent()) {
        header("Location: $url");
        exit();
    } else {
        echo '<script>window.location.href="' . addslashes($url) . '";</script>';
        exit();
    }
}

// Sanitize user input to prevent XSS attacks
function sanitize($data) {
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

// Check if the logged-in user is an admin
function isAdmin() {
    return isset($_SESSION['role_id']) && $_SESSION['role_id'] == 1;
}

// Check if the logged-in user is a customer
function isCustomer() {
    // Check for customer_id session (since customers are in WebsiteCustomers)
    return isset($_SESSION['customer_id']);
}

// Check if the logged-in user is a staff member
function isStaff() {
    return isset($_SESSION['role_id']) && $_SESSION['role_id'] == 2;
}

// Generate a CSRF token for form submissions
function generateCsrfToken() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

// Verify the CSRF token
function verifyCsrfToken($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}
?>