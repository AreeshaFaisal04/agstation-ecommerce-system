<?php
include_once '../../includes/session.php';
include_once '../../includes/header.php';
require_once '../../config/db.php';

try {
    // Fetch all distributors from the database
    $query = "SELECT distributor_id, name, contact_person, email, phone_number, status FROM Distributors ORDER BY distributor_id DESC";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $distributors = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>

<div class="container mt-4">
    <h2>Distributors</h2>
    <a href="create.php" class="btn btn-success mb-2">Add Distributor</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Name</th>
                <th>Contact Person</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($distributors as $row): ?>
            <tr>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td><?= htmlspecialchars($row['contact_person']) ?></td>
                <td><?= htmlspecialchars($row['email']) ?></td>
                <td><?= htmlspecialchars($row['phone_number']) ?></td>
                <td><?= htmlspecialchars($row['status']) ?></td>
                <td>
                    <a href="edit.php?id=<?= htmlspecialchars($row['distributor_id']) ?>" class="btn btn-warning btn-sm">Edit</a>
                    <a href="delete.php?id=<?= htmlspecialchars($row['distributor_id']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this distributor?')">Delete</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include_once '../../includes/footer.php'; ?>
