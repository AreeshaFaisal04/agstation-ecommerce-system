<?php
require_once '../includes/session.php';
require_once '../includes/header.php';
require_once '../config/db.php';

$counts = [];
$tables = ['Users', 'WebsiteCustomers', 'Products', 'WebsiteOrders', 'Payments', 'Returns', 'ProductReviews'];

try {
    foreach ($tables as $table) {
        $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM $table");
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $counts[$table] = $row['count'];
    }
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>
<div class="container mt-4">
    <h1>Admin Dashboard</h1>
    <div class="row">
        <?php foreach ($counts as $table => $count): ?>
            <div class="col-md-4">
                <div class="card text-center mb-3">
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($table) ?></h5>
                        <p class="card-text">Total: <?= htmlspecialchars($count) ?></p>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<?php require_once '../includes/footer.php'; ?>