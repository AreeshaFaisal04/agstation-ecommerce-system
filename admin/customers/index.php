<?php 
include '../../includes/header.php'; 
include '../../includes/session.php'; 
include '../../config/db.php'; 


// Ensure the user has admin privileges
checkRole(['Admin', 'admin', 1]); // Add 1 if your admin role_id is 1

try {
    // Fetch all customers from the database
    $stmt = $pdo->prepare("SELECT customer_id, name, email, phone, created_at FROM WebsiteCustomers ORDER BY customer_id DESC");
    $stmt->execute();
    $customers = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>

<div class="container mt-4">
    <h2>Website Customers</h2>
    <a href="create.php" class="btn btn-primary mb-3">Add New Customer</a>
    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Registered At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($customers as $customer): ?>
                <tr>
                    <td><?= htmlspecialchars($customer['customer_id']) ?></td>
                    <td><?= htmlspecialchars($customer['name']) ?></td>
                    <td><?= htmlspecialchars($customer['email']) ?></td>
                    <td><?= htmlspecialchars($customer['phone']) ?></td>
                    <td><?= htmlspecialchars($customer['created_at']) ?></td>
                    <td>
                        <a href="edit.php?id=<?= htmlspecialchars($customer['customer_id']) ?>" class="btn btn-sm btn-warning">Edit</a>
                        <a href="delete.php?id=<?= htmlspecialchars($customer['customer_id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>


<?php include '../../includes/footer.php'; ?>
