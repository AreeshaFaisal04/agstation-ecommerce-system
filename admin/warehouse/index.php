<?php
require_once '../../config/db.php';
require_once '../../includes/session.php';
require_once '../../includes/header.php';
checkRole(['admin']);

try {
    // Fetch all warehouses
    $query = "SELECT * FROM Warehouse ORDER BY warehouse_id ASC";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $warehouses = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>

<div class="container mt-4">
    <h2>Warehouse List</h2>
    <a href="create.php" class="btn btn-primary mb-2">Add New Warehouse</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Type</th>
                <th>Address</th>
                <th>Contact Person</th>
                <th>Phone</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($warehouses as $row): ?>
                <tr>
                    <td><?= htmlspecialchars($row['warehouse_id']) ?></td>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td><?= htmlspecialchars($row['type']) ?></td>
                    <td><?= htmlspecialchars($row['address']) ?></td>
                    <td><?= htmlspecialchars($row['contact_person']) ?></td>
                    <td><?= htmlspecialchars($row['phone']) ?></td>
                    <td>
                        <a href="edit.php?id=<?= htmlspecialchars($row['warehouse_id']) ?>" class="btn btn-sm btn-warning">Edit</a>
                        <a href="delete.php?id=<?= htmlspecialchars($row['warehouse_id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure to delete this warehouse?')">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php require_once '../../includes/footer.php'; ?>
