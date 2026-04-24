<?php
include '../../includes/session.php';
include '../../includes/header.php';
require_once '../../config/db.php';


try {
    // Fetch all expenses from the database
    $query = "SELECT expense_id, type, amount, paid_to, date, notes FROM Expenses ORDER BY expense_id DESC";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $expenses = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>

<div class="container mt-4">
    <h2>Expenses</h2>
    <a href="create.php" class="btn btn-success mb-3">Add Expense</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Type</th>
                <th>Amount</th>
                <th>Paid To</th>
                <th>Date</th>
                <th>Notes</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($expenses as $row): ?>
            <tr>
                <td><?= htmlspecialchars($row['expense_id']) ?></td>
                <td><?= htmlspecialchars($row['type']) ?></td>
                <td><?= htmlspecialchars($row['amount']) ?></td>
                <td><?= htmlspecialchars($row['paid_to']) ?></td>
                <td><?= htmlspecialchars($row['date']) ?></td>
                <td><?= htmlspecialchars($row['notes']) ?></td>
                <td>
                    <a href="edit.php?id=<?= htmlspecialchars($row['expense_id']) ?>" class="btn btn-warning btn-sm">Edit</a>
                    <a href="delete.php?id=<?= htmlspecialchars($row['expense_id']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this expense?')">Delete</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include '../../includes/footer.php'; ?>