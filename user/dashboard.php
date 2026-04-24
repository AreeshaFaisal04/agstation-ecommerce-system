<?php
include_once('../includes/session.php');
include_once('../includes/header.php');
require_once('../config/db.php');

// Ensure the user is a customer (role_id = 3)
checkRole([3]);

$user_id = $_SESSION['user_id'];

try {
    // Fetch user details
    $stmt = $pdo->prepare("SELECT * FROM Users WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        throw new Exception("User not found.");
    }
} catch (Exception $e) {
    echo "<div class='alert alert-danger'>Error: " . htmlspecialchars($e->getMessage()) . "</div>";
    include_once('../includes/footer.php');
    exit;
}
?>

<div class="container mt-5">
    <h2 class="text-center mb-4">User Dashboard</h2>
    <div class="card p-4 shadow">
        <h4>Welcome, <?php echo htmlspecialchars($user['name']); ?>!</h4>
        <p>Email: <?php echo htmlspecialchars($user['email']); ?></p>
        <p>Role: Customer</p>
    </div>

    <div class="mt-4">
        <h5>Quick Links</h5>
        <ul class="list-group">
            <li class="list-group-item"><a href="profile.php">My Profile</a></li>
            <li class="list-group-item"><a href="orders.php">My Orders</a></li>
            <li class="list-group-item"><a href="reviews.php">My Reviews</a></li>
        </ul>
    </div>
</div>

<?php include_once('../includes/footer.php'); ?>
