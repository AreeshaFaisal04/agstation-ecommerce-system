<!-- filepath: d:\agstation\includes\navbar.php -->
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<nav class="navbar navbar-expand-lg navbar-dark" style="background: linear-gradient(90deg, #3D52A0 0%, #7091E6 100%); font-family: 'Roboto', sans-serif;">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold" href="/frontend/index.php" style="letter-spacing:2px;">
            <i class="fas fa-tractor"></i> AGSTATION
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-center">
                <?php if (isset($_SESSION['role_id']) && $_SESSION['role_id'] == 1): ?>
                    <!-- Admin Navbar -->
                    <li class="nav-item"><a class="nav-link" href="/admin/dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="/admin/users/index.php"><i class="fas fa-users-cog"></i> Users</a></li>
                    <li class="nav-item"><a class="nav-link" href="/admin/products/index.php"><i class="fas fa-boxes"></i> Products</a></li>
                    <li class="nav-item"><a class="nav-link" href="/admin/orders/index.php"><i class="fas fa-clipboard-list"></i> Orders</a></li>
                    <li class="nav-item"><a class="nav-link" href="/admin/warehouse/index.php"><i class="fas fa-warehouse"></i> Warehouse</a></li>
                    <li class="nav-item"><a class="nav-link" href="/admin/shipments/index.php"><i class="fas fa-truck"></i> Shipments</a></li>
                    <li class="nav-item"><a class="nav-link" href="/admin/third_party_service/index.php"><i class="fas fa-briefcase"></i> Third Party Services</a></li>
                    <li class="nav-item"><a class="nav-link" href="/auth/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                <?php elseif (isset($_SESSION['customer_id'])): ?>
                    <!-- Customer Navbar -->
                    <li class="nav-item"><a class="nav-link" href="/frontend/index.php"><i class="fas fa-home"></i> Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="/frontend/product_list.php"><i class="fas fa-seedling"></i> Products</a></li>
                    <li class="nav-item"><a class="nav-link" href="/frontend/about.php"><i class="fas fa-info-circle"></i> About</a></li>
                    <li class="nav-item"><a class="nav-link" href="/frontend/contact.php"><i class="fas fa-envelope"></i> Contact</a></li>
                    <li class="nav-item"><a class="nav-link" href="/frontend/cart.php"><i class="fas fa-shopping-cart"></i> Cart</a></li>
                    <li class="nav-item"><a class="nav-link" href="/user/orders.php"><i class="fas fa-box"></i> My Orders</a></li>
                    <li class="nav-item"><a class="nav-link" href="/user/profile.php"><i class="fas fa-user-circle"></i> My Profile</a></li>
                    <li class="nav-item"><a class="nav-link" href="/frontend/request_return.php"><i class="fas fa-undo"></i> Returns</a></li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="customerDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user"></i> <?= htmlspecialchars($_SESSION['name'] ?? 'Customer') ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="customerDropdown">
                            <li><a class="dropdown-item" href="/user/profile.php"><i class="fas fa-user-circle"></i> My Profile</a></li>
                            <li><a class="dropdown-item" href="/user/orders.php"><i class="fas fa-box"></i> My Orders</a></li>
                            <li><a class="dropdown-item" href="/frontend/request_return.php"><i class="fas fa-undo"></i> Returns</a></li>
                            <li><a class="dropdown-item" href="/auth/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                        </ul>
                    </li>
                <?php else: ?>
                    <!-- Guest Navbar -->
                    <li class="nav-item"><a class="nav-link" href="/frontend/index.php"><i class="fas fa-home"></i> Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="/frontend/product_list.php"><i class="fas fa-seedling"></i> Products</a></li>
                    <li class="nav-item"><a class="nav-link" href="/frontend/about.php"><i class="fas fa-info-circle"></i> About</a></li>
                    <li class="nav-item"><a class="nav-link" href="/frontend/contact.php"><i class="fas fa-envelope"></i> Contact</a></li>
                    <li class="nav-item"><a class="nav-link" href="/frontend/cart.php"><i class="fas fa-shopping-cart"></i> Cart</a></li>
                    <li class="nav-item">
                        <a class="nav-link btn btn-outline-light px-3 py-1 me-2" style="border-radius:20px;" href="/auth/login.php">
                            <i class="fas fa-sign-in-alt"></i> Login
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn btn-warning px-3 py-1" style="border-radius:20px; color:#222 !important; font-weight:600;" href="/auth/register.php">
                            <i class="fas fa-user-plus"></i> Register
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
<!-- Include Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- Include Google Fonts -->
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:400,700">
<!-- Include Font Awesome Icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<!-- Main Project CSS -->
<link rel="stylesheet" href="/assets/css/style.css">

