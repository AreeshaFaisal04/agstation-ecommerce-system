<?php
require_once '../../config/db.php';
require_once '../../includes/session.php';
require_once '../../includes/header.php';
checkRole(['admin']);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $type = trim($_POST['type']);
    $address = trim($_POST['address']);
    $contact_person = trim($_POST['contact_person']);
    $phone = trim($_POST['phone']);

    try {
        // Insert the warehouse into the database
        $query = "INSERT INTO Warehouse (name, type, address, contact_person, phone) VALUES (?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$name, $type, $address, $contact_person, $phone]);
        header("Location: index.php");
        exit;
    } catch (PDOException $e) {
        $error = "Error adding warehouse: " . $e->getMessage();
    }
}
?>

<div class="container mt-4">
    <h2>Add New Warehouse</h2>
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <form method="post">
        <div class="form-group">
            <label>Warehouse Name</label>
            <input type="text" name="name" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Type</label>
            <select name="type" class="form-control" required>
                <option value="">Select Type</option>
                <option value="Amazon FBA">Amazon FBA</option>
                <option value="Prep Center">Prep Center</option>
            </select>
        </div>

        <div class="form-group">
            <label>Address</label>
            <textarea name="address" class="form-control" required></textarea>
        </div>

        <div class="form-group">
            <label>Contact Person</label>
            <input type="text" name="contact_person" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Phone</label>
            <input type="text" name="phone" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-success">Add Warehouse</button>
        <a href="index.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>

<?php require_once '../../includes/footer.php'; ?>
