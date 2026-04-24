<?php
include('../../includes/session.php');
include('../../config/db.php');

// Validate and sanitize the ID parameter
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    header("Location: index.php");
    exit;
}

try {
    // Fetch the brand details
    $stmt = $pdo->prepare("SELECT * FROM Brands WHERE brand_id = ?");
    $stmt->execute([$id]);
    $brand = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$brand) {
        header("Location: index.php");
        exit;
    }

    // Handle form submission
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $name = trim($_POST['name']);
        $contact_email = trim($_POST['contact_email']);
        $phone_number = trim($_POST['phone_number']);
        $website_url = trim($_POST['website_url']);
        $status = trim($_POST['status']);

        // Validate required fields
        if (!empty($name) && in_array($status, ['Active', 'Pending', 'Blacklisted'])) {
            // Update the brand details
            $stmt = $pdo->prepare(
                "UPDATE Brands 
                 SET name = ?, contact_email = ?, phone_number = ?, website_url = ?, status = ? 
                 WHERE brand_id = ?"
            );
            $stmt->execute([$name, $contact_email, $phone_number, $website_url, $status, $id]);
            header("Location: index.php");
            exit;
        } else {
            $error = "Brand name and status are required, and status must be valid.";
        }
    }
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}

// Now include header and output HTML
include('../../includes/header.php');
?>

<div class="container mt-4">
    <h2>Edit Brand</h2>
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <form method="POST">
        <div class="mb-3">
            <label for="name" class="form-label">Brand Name</label>
            <input type="text" class="form-control" name="name" value="<?= htmlspecialchars($brand['name'] ?? '') ?>" required>
        </div>
        <div class="mb-3">
            <label for="contact_email" class="form-label">Contact Email</label>
            <input type="email" class="form-control" name="contact_email" value="<?= htmlspecialchars($brand['contact_email'] ?? '') ?>">
        </div>
        <div class="mb-3">
            <label for="phone_number" class="form-label">Phone Number</label>
            <input type="text" class="form-control" name="phone_number" value="<?= htmlspecialchars($brand['phone_number'] ?? '') ?>">
        </div>
        <div class="mb-3">
            <label for="website_url" class="form-label">Website URL</label>
            <input type="url" class="form-control" name="website_url" value="<?= htmlspecialchars($brand['website_url'] ?? '') ?>">
        </div>
        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select class="form-control" name="status" required>
                <option value="Active" <?= ($brand['status'] ?? '') === 'Active' ? 'selected' : '' ?>>Active</option>
                <option value="Pending" <?= ($brand['status'] ?? '') === 'Pending' ? 'selected' : '' ?>>Pending</option>
                <option value="Blacklisted" <?= ($brand['status'] ?? '') === 'Blacklisted' ? 'selected' : '' ?>>Blacklisted</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
        <a href="index.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>

<?php include('../../includes/footer.php'); ?>
