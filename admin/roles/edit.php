<?php
require_once '../../config/db.php';
require_once '../../includes/session.php';
require_once '../../includes/header.php';
checkRole(['admin']);

// Validate and sanitize the ID parameter
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    header("Location: index.php");
    exit;
}

try {
    // Fetch the role details
    $query = "SELECT * FROM roles WHERE id = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$id]);
    $role = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$role) {
        header("Location: index.php");
        exit;
    }

    // Handle form submission
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $role_name = trim($_POST['role_name']);

        // Update the role in the database
        $update_query = "UPDATE roles SET role_name = ? WHERE id = ?";
        $update_stmt = $pdo->prepare($update_query);
        $update_stmt->execute([$role_name, $id]);
        header("Location: index.php");
        exit;
    }
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>

<div class="container">
    <h2>Edit Role</h2>
    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Role Name</label>
            <input type="text" name="role_name" value="<?= htmlspecialchars($role['role_name']) ?>" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
        <a href="index.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>

<?php include '../../includes/footer.php'; ?>
