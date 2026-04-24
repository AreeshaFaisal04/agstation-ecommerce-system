<?php
// filepath: d:\agstation\admin\distributors\create.php
include_once '../../includes/session.php';
require_once '../../config/db.php';
include_once '../../includes/functions.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $contact_person = trim($_POST['contact_person']);
    $email = trim($_POST['email']);
    $phone_number = trim($_POST['phone_number']);
    $address = trim($_POST['address']);
    $country = trim($_POST['country']);
    $website_url = trim($_POST['website_url']);
    $business_license_no = trim($_POST['business_license_no']);
    $status = trim($_POST['status']);
    $notes = trim($_POST['notes']);

    try {
        // Insert distributor into the database
        $query = "INSERT INTO Distributors (name, contact_person, email, phone_number, address, country, website_url, business_license_no, status, notes)
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($query);
        $stmt->execute([
            $name,
            $contact_person,
            $email,
            $phone_number,
            $address,
            $country,
            $website_url,
            $business_license_no,
            $status,
            $notes
        ]);
        header('Location: index.php');
        exit;
    } catch (PDOException $e) {
        $error = "Error adding distributor: " . $e->getMessage();
    }
}

include_once '../../includes/header.php';
include_once '../../includes/navbar.php';
include_once '../../includes/sidebar.php';
?>

<div class="container mt-4">
    <h2>Add Distributor</h2>
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <form method="POST">
        <div class="mb-2"><label>Name*</label><input type="text" name="name" class="form-control" required></div>
        <div class="mb-2"><label>Contact Person</label><input type="text" name="contact_person" class="form-control"></div>
        <div class="mb-2"><label>Email</label><input type="email" name="email" class="form-control"></div>
        <div class="mb-2"><label>Phone Number</label><input type="text" name="phone_number" class="form-control"></div>
        <div class="mb-2"><label>Address</label><textarea name="address" class="form-control"></textarea></div>
        <div class="mb-2"><label>Country</label><input type="text" name="country" class="form-control"></div>
        <div class="mb-2"><label>Website URL</label><input type="text" name="website_url" class="form-control"></div>
        <div class="mb-2"><label>Business License No</label><input type="text" name="business_license_no" class="form-control"></div>
        <div class="mb-2">
            <label>Status</label>
            <select name="status" class="form-select">
                <option value="Active">Active</option>
                <option value="Inactive">Inactive</option>
                <option value="Blacklisted">Blacklisted</option>
            </select>
        </div>
        <div class="mb-2"><label>Notes</label><textarea name="notes" class="form-control"></textarea></div>
        <button type="submit" class="btn btn-primary">Add Distributor</button>
        <a href="index.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>

<?php include_once '../../includes/footer.php'; ?>
