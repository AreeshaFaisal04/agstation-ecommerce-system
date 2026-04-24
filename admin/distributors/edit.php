<?php
// filepath: d:\agstation\admin\distributors\edit.php
include_once '../../includes/session.php';
require_once '../../config/db.php';

// Ensure the user has admin privileges
checkRole(['Admin', 'admin', 1]);

// Validate and sanitize the ID parameter
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    header("Location: index.php");
    exit;
}

try {
    // Fetch the distributor details
    $query = "SELECT * FROM Distributors WHERE distributor_id = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$id]);
    $distributor = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$distributor) {
        header("Location: index.php");
        exit;
    }

    // Handle form submission
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name = trim($_POST['name']);
        $contact = trim($_POST['contact_person']);
        $email = trim($_POST['email']);
        $phone = trim($_POST['phone_number']);
        $address = trim($_POST['address']);
        $country = trim($_POST['country']);
        $website = trim($_POST['website_url']);
        $license = trim($_POST['business_license_no']);
        $status = trim($_POST['status']);
        $notes = trim($_POST['notes']);

        // Update the distributor details
        $update = "UPDATE Distributors 
                   SET name = ?, contact_person = ?, email = ?, phone_number = ?, address = ?, country = ?, 
                       website_url = ?, business_license_no = ?, status = ?, notes = ? 
                   WHERE distributor_id = ?";
        $stmt = $pdo->prepare($update);
        $stmt->execute([$name, $contact, $email, $phone, $address, $country, $website, $license, $status, $notes, $id]);
        header("Location: index.php");
        exit;
    }
} catch (PDOException $e) {
    $error = "Database error: " . $e->getMessage();
}

include_once '../../includes/header.php';
?>

<div class="container mt-4">
    <h2>Edit Distributor</h2>
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <form method="POST">
        <div class="mb-2"><label>Name</label>
            <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($distributor['name'] ?? '') ?>" required>
        </div>
        <div class="mb-2"><label>Contact Person</label>
            <input type="text" name="contact_person" class="form-control" value="<?= htmlspecialchars($distributor['contact_person'] ?? '') ?>">
        </div>
        <div class="mb-2"><label>Email</label>
            <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($distributor['email'] ?? '') ?>">
        </div>
        <div class="mb-2"><label>Phone</label>
            <input type="text" name="phone_number" class="form-control" value="<?= htmlspecialchars($distributor['phone_number'] ?? '') ?>">
        </div>
        <div class="mb-2"><label>Address</label>
            <textarea name="address" class="form-control"><?= htmlspecialchars($distributor['address'] ?? '') ?></textarea>
        </div>
        <div class="mb-2"><label>Country</label>
            <input type="text" name="country" class="form-control" value="<?= htmlspecialchars($distributor['country'] ?? '') ?>">
        </div>
        <div class="mb-2"><label>Website URL</label>
            <input type="text" name="website_url" class="form-control" value="<?= htmlspecialchars($distributor['website_url'] ?? '') ?>">
        </div>
        <div class="mb-2"><label>Business License No</label>
            <input type="text" name="business_license_no" class="form-control" value="<?= htmlspecialchars($distributor['business_license_no'] ?? '') ?>">
        </div>
        <div class="mb-2">
            <label>Status</label>
            <select name="status" class="form-select">
                <option value="Active" <?= ($distributor['status'] ?? '') === 'Active' ? 'selected' : '' ?>>Active</option>
                <option value="Inactive" <?= ($distributor['status'] ?? '') === 'Inactive' ? 'selected' : '' ?>>Inactive</option>
                <option value="Blacklisted" <?= ($distributor['status'] ?? '') === 'Blacklisted' ? 'selected' : '' ?>>Blacklisted</option>
            </select>
        </div>
        <div class="mb-2"><label>Notes</label>
            <textarea name="notes" class="form-control"><?= htmlspecialchars($distributor['notes'] ?? '') ?></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Update Distributor</button>
        <a href="index.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>

<?php include_once '../../includes/footer.php'; ?>
