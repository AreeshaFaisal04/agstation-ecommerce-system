<?php
require_once '../../config/db.php';
require_once '../../includes/session.php';
require_once '../../includes/header.php';
checkRole(['admin']);

// Validate and sanitize the ID parameter
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    echo "Service ID is required.";
    exit;
}

try {
    // Fetch the service details
    $query = "SELECT * FROM ThirdPartyServices WHERE id = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$id]);
    $service = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$service) {
        echo "Service not found.";
        exit;
    }

    // Handle form submission
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $name = trim($_POST['name']);
        $service_type = trim($_POST['service_type']);
        $contact_info = trim($_POST['contact_info']);
        $contract_details = trim($_POST['contract_details']);

        // Update the service in the database
        $update_query = "UPDATE ThirdPartyServices SET name = ?, service_type = ?, contact_info = ?, contract_details = ? WHERE id = ?";
        $update_stmt = $pdo->prepare($update_query);
        $update_stmt->execute([$name, $service_type, $contact_info, $contract_details, $id]);
        header("Location: index.php");
        exit;
    }
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>

<div class="container mt-4">
    <h2>Edit Third Party Service</h2>
    <form method="post">
        <div class="form-group">
            <label>Service Name</label>
            <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($service['name']) ?>" required>
        </div>
        <div class="form-group">
            <label>Service Type</label>
            <input type="text" name="service_type" class="form-control" value="<?= htmlspecialchars($service['service_type']) ?>" required>
        </div>
        <div class="form-group">
            <label>Contact Info</label>
            <input type="text" name="contact_info" class="form-control" value="<?= htmlspecialchars($service['contact_info']) ?>" required>
        </div>
        <div class="form-group">
            <label>Contract Details</label>
            <textarea name="contract_details" class="form-control" required><?= htmlspecialchars($service['contract_details']) ?></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Update Service</button>
        <a href="index.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>

<?php require_once '../../includes/footer.php'; ?>
