<?php
require_once '../../config/db.php';
require_once '../../includes/session.php';
require_once '../../includes/header.php';
checkRole(['admin']);

try {
    // Fetch third-party services
    $query = "
        SELECT s.*, w.name AS warehouse_name
        FROM ThirdPartyServices s
        LEFT JOIN Warehouse w ON s.warehouse_id = w.warehouse_id
        ORDER BY s.service_id DESC
    ";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $services = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>

<div class="container mt-4">
    <h2>Third Party Services</h2>
    <a href="create.php" class="btn btn-primary mb-2">Add Service</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Type</th>
                <th>Warehouse</th>
                <th>Contact Email</th>
                <th>Status</th>
                <th>Billing Cycle</th>
                <th>Subscription Cost</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($services as $row): ?>
            <tr>
                <td><?= htmlspecialchars($row['service_id']) ?></td>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td><?= htmlspecialchars($row['type']) ?></td>
                <td><?= htmlspecialchars($row['warehouse_name']) ?></td>
                <td><?= htmlspecialchars($row['contact_email']) ?></td>
                <td><?= htmlspecialchars($row['status']) ?></td>
                <td><?= htmlspecialchars($row['billing_cycle']) ?></td>
                <td><?= htmlspecialchars($row['subscription_cost']) ?></td>
                <td>
                    <a href="edit.php?id=<?= htmlspecialchars($row['service_id']) ?>" class="btn btn-sm btn-warning">Edit</a>
                    <a href="delete.php?id=<?= htmlspecialchars($row['service_id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include_once '../../includes/footer.php'; ?>
