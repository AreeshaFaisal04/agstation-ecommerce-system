<?php
// admin/brands/index.php
include_once '../../includes/session.php';

// Check if the user is logged in and has admin privileges
if (!isset($_SESSION['user_id']) || ($_SESSION['role_id'] ?? null) != 1) {
    header("Location: /auth/login.php");
    exit;
}
checkRole(['Admin', 'admin', 1]); // Add 1 if your admin role_id is 1

require_once '../../config/db.php';
include_once '../../includes/header.php';

try {
    // Fetch all brands from the database
    $stmt = $pdo->prepare("SELECT brand_id, name, status FROM Brands ORDER BY brand_id DESC");
    $stmt->execute();
    $brands = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>

<div class="container mt-5">
  <h2 class="mb-4">Brand Management</h2>
  <a href="create.php" class="btn btn-primary mb-3">Add New Brand</a>
  <table class="table table-bordered">
    <thead class="table-dark">
      <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Status</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($brands as $row): ?>
      <tr>
        <td><?= htmlspecialchars($row['brand_id']) ?></td>
        <td><?= htmlspecialchars($row['name']) ?></td>
        <td><?= htmlspecialchars($row['status']) ?></td>
        <td>
          <a href="edit.php?id=<?= htmlspecialchars($row['brand_id']) ?>" class="btn btn-sm btn-warning">Edit</a>
          <a href="delete.php?id=<?= htmlspecialchars($row['brand_id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

<?php include_once '../../includes/footer.php'; ?>