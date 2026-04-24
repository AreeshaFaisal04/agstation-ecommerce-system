<?php
require_once '../../config/db.php';
require_once '../../includes/session.php';
require_once '../../includes/header.php';
checkRole(['admin']);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $service_type = trim($_POST['service_type']);
    $contact_info = trim($_POST['contact_info']);
    $contract_details = trim($_POST['contract_details']);

    try {
        // Insert the service into the database
        $query = "INSERT INTO ThirdPartyServices (name, service_type, contact_info, contract_details) VALUES (?, ?, ?, ?)";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$name, $service_type, $contact_info, $contract_details]);
        header("Location: index.php");
        exit;
    } catch (PDOException $e) {
        $error = "Database error: " . $e->getMessage();
    }
}
?>

<div class="container mt-4">
    <h2>Add New Third Party Service</h2>
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <form method="post">
        <div class="form-group">
            <label>Service Name</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Service Type</label>
            <input type="text" name="service_type" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Contact Info</label>
            <input type="text" name="contact_info" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Contract Details</label>
            <textarea name="contract_details" class="form-control" required></textarea>
        </div>
        <button type="submit" class="btn btn-success">Add Service</button>
        <a href="index.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>

<?php require_once '../../includes/footer.php'; ?>
