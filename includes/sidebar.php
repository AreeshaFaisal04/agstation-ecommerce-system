<!-- filepath: d:\agstation\includes\sidebar.php -->
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<div id="sidebar" class="sidebar d-flex flex-column flex-shrink-0">
    <button class="sidebar-toggle-btn" id="sidebarToggle" title="Toggle Sidebar">
        <i class="fas fa-bars"></i>
    </button>
    <div class="sidebar-content">
        <?php if (isset($_SESSION['role_id']) && $_SESSION['role_id'] == 1): ?>
            <ul class="nav nav-pills flex-column mb-auto">
                <li><a href="/admin/dashboard.php" class="nav-link" title="Dashboard"><i class="fas fa-tachometer-alt"></i><span class="sidebar-label">Dashboard</span></a></li>
                <li><a href="/admin/brands/index.php" class="nav-link" title="Brands"><i class="fas fa-tags"></i><span class="sidebar-label">Brands</span></a></li>
                <li><a href="/admin/customers/index.php" class="nav-link" title="Customers"><i class="fas fa-user-friends"></i><span class="sidebar-label">Customers</span></a></li>
                <li><a href="/admin/distributors/index.php" class="nav-link" title="Distributors"><i class="fas fa-truck-moving"></i><span class="sidebar-label">Distributors</span></a></li>
                <li><a href="/admin/expenses/index.php" class="nav-link" title="Expenses"><i class="fas fa-money-bill-wave"></i><span class="sidebar-label">Expenses</span></a></li>
                <li><a href="/admin/inventory/index.php" class="nav-link" title="Inventory"><i class="fas fa-boxes"></i><span class="sidebar-label">Inventory</span></a></li>
                <li>
                    <!-- Invoice Dropdown Button -->
                    <div class="dropdown">
                        <a href="#" class="nav-link dropdown-toggle" id="invoiceDropdown" data-bs-toggle="dropdown" aria-expanded="false" title="Invoices">
                            <i class="fas fa-file-invoice"></i>
                            <span class="sidebar-label">Invoices</span>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="invoiceDropdown">
                            <li>
                                <a class="dropdown-item" href="/admin/invoices/customer/index.php">Customer Invoices</a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="/admin/invoices/seller/index.php">Seller Invoices</a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li><a href="/admin/orders/index.php" class="nav-link" title="Orders"><i class="fas fa-clipboard-list"></i><span class="sidebar-label">Orders</span></a></li>
                <li><a href="/admin/payments/index.php" class="nav-link" title="Payments"><i class="fas fa-credit-card"></i><span class="sidebar-label">Payments</span></a></li>
                <li><a href="/admin/products/index.php" class="nav-link" title="Products"><i class="fas fa-seedling"></i><span class="sidebar-label">Products</span></a></li>
                <li><a href="/admin/returns/index.php" class="nav-link" title="Returns"><i class="fas fa-undo"></i><span class="sidebar-label">Returns</span></a></li>
                <li><a href="/admin/reviews/index.php" class="nav-link" title="Reviews"><i class="fas fa-star"></i><span class="sidebar-label">Reviews</span></a></li>
                <li><a href="/admin/roles/index.php" class="nav-link" title="Roles"><i class="fas fa-user-shield"></i><span class="sidebar-label">Roles</span></a></li>
                <li><a href="/admin/shipments/index.php" class="nav-link" title="Shipments"><i class="fas fa-truck"></i><span class="sidebar-label">Shipments</span></a></li>
                <li><a href="/admin/shipments_from_suppliers/index.php" class="nav-link" title="Shipments From Suppliers"><i class="fas fa-truck-loading"></i><span class="sidebar-label">Shipments From Suppliers</span></a></li>
                <li><a href="/admin/third_party_service/index.php" class="nav-link" title="Third Party Services"><i class="fas fa-briefcase"></i><span class="sidebar-label">Third Party Services</span></a></li>
                <li><a href="/admin/users/index.php" class="nav-link" title="Users"><i class="fas fa-users-cog"></i><span class="sidebar-label">Users</span></a></li>
                <li><a href="/admin/warehouse/index.php" class="nav-link" title="Warehouse"><i class="fas fa-warehouse"></i><span class="sidebar-label">Warehouse</span></a></li>
                <li><a href="/auth/logout.php" class="nav-link" title="Logout"><i class="fas fa-sign-out-alt"></i><span class="sidebar-label">Logout</span></a></li>
            </ul>
        <?php elseif (isset($_SESSION['customer_id'])): ?>
            <!-- Customer Sidebar -->
            <div class="sidebar-title d-flex align-items-center mb-4">
                <img src="/assets/images/user-avatar.png" alt="User Avatar" class="sidebar-avatar">
                <span class="sidebar-user"><?= htmlspecialchars($_SESSION['name'] ?? 'Customer') ?></span>
            </div>
            <ul class="nav nav-pills flex-column mb-auto">
                <li class="nav-item">
                    <a href="/user/profile.php" class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'profile.php' ? 'active' : '' ?>">
                        <i class="fas fa-user"></i> <span class="ms-2">Profile</span>
                    </a>
                </li>
                <li>
                    <a href="/user/orders.php" class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'orders.php' ? 'active' : '' ?>">
                        <i class="fas fa-box"></i> <span class="ms-2">My Orders</span>
                    </a>
                </li>
                <li>
                    <a href="/user/reviews.php" class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'reviews.php' ? 'active' : '' ?>">
                        <i class="fas fa-star"></i> <span class="ms-2">My Reviews</span>
                    </a>
                </li>
                <li>
                    <a href="/auth/logout.php" class="nav-link">
                        <i class="fas fa-sign-out-alt"></i> <span class="ms-2">Logout</span>
                    </a>
                </li>
            </ul>
        <?php else: ?>
            <!-- Guest Sidebar -->
            <div class="sidebar-title mb-4">
                <i class="fas fa-user-circle"></i> Welcome
            </div>
            <ul class="nav nav-pills flex-column mb-auto">
                <li class="nav-item">
                    <a href="/auth/login.php" class="nav-link">
                        <i class="fas fa-sign-in-alt"></i> <span class="ms-2">Login</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/auth/register.php" class="nav-link">
                        <i class="fas fa-user-plus"></i> <span class="ms-2">Register</span>
                    </a>
                </li>
            </ul>
        <?php endif; ?>
    </div>
</div>
<script>
    const sidebar = document.getElementById('sidebar');
    const toggleBtn = document.getElementById('sidebarToggle');
    const mainContent = document.getElementById('mainContent');
    let collapsed = false;
    toggleBtn.addEventListener('click', function() {
        collapsed = !collapsed;
        if (collapsed) {
            sidebar.classList.add('sidebar-collapsed');
            mainContent.classList.add('main-content-collapsed');
        } else {
            sidebar.classList.remove('sidebar-collapsed');
            mainContent.classList.remove('main-content-collapsed');
        }
    });
</script>