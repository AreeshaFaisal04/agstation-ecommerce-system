<?php
require_once '../../config/db.php';
require_once '../../includes/session.php';
require_once '../../includes/header.php';
checkRole(['admin']);

// Validate and sanitize the ID parameter
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    echo "Warehouse ID is missing or invalid.";
    exit;
}

try {
    // Fetch the warehouse details
    $query = "SELECT * FROM Warehouse WHERE warehouse_id = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$id]);
    $warehouse = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$warehouse) {
        echo "Warehouse not found.";
        exit;
    }

    // Handle form submission
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $name = trim($_POST['name']);
        $type = trim($_POST['type']);
        $address = trim($_POST['address']);
        $contact_person = trim($_POST['contact_person']);
        $phone = trim($_POST['phone']);

        // Update the warehouse in the database
        $update_query = "UPDATE Warehouse SET name = ?, type = ?, address = ?, contact_person = ?, phone = ? WHERE warehouse_id = ?";
        $update_stmt = $pdo->prepare($update_query);
        $update_stmt->execute([$name, $type, $address, $contact_person, $phone, $id]);
        header("Location: index.php");
        exit;
    }
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>

<div class="container mt-4">
    <h2>Edit Warehouse</h2>
    <form method="post">
        <div class="form-group">
            <label>Warehouse Name</label>
            <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($warehouse['name']) ?>" required>
        </div>

        <div class="form-group">
            <label>Type</label>
            <select name="type" class="form-control" required>
                <option value="Amazon FBA" <?= $warehouse['type'] == 'Amazon FBA' ? 'selected' : '' ?>>Amazon FBA</option>
                <option value="Prep Center" <?= $warehouse['type'] == 'Prep Center' ? 'selected' : '' ?>>Prep Center</option>
            </select>
        </div>

        <div class="form-group">
            <label>Address</label>
            <textarea name="address" class="form-control" required><?= htmlspecialchars($warehouse['address']) ?></textarea>
        </div>

        <div class="form-group">
            <label>Contact Person</label>
            <input type="text" name="contact_person" class="form-control" value="<?= htmlspecialchars($warehouse['contact_person']) ?>" required>
        </div>

        <div class="form-group">
            <label>Phone</label>
            <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($warehouse['phone']) ?>" required>
        </div>

        <button type="submit" class="btn btn-success">Update Warehouse</button>
        <a href="index.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>

<?php require_once '../../includes/footer.php'; ?>
