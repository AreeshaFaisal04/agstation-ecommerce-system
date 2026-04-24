<?php
include('../../includes/session.php');
include('../../config/db.php');
checkRole(['Admin', 'admin', 1]);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $contact_email = trim($_POST['contact_email']);
    $phone_number = trim($_POST['phone_number']);
    $website_url = trim($_POST['website_url']);
    $authorized_reseller = isset($_POST['authorized_reseller']) ? 1 : 0;
    $contract_document_url = trim($_POST['contract_document_url']);
    $category = trim($_POST['category']);
    $status = trim($_POST['status']);
    $notes = trim($_POST['notes']);

    if (!empty($name)) {
        $stmt = $pdo->prepare("INSERT INTO Brands 
            (name, contact_email, phone_number, website_url, authorized_reseller, contract_document_url, category, status, notes) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $name,
            $contact_email,
            $phone_number,
            $website_url,
            $authorized_reseller,
            $contract_document_url,
            $category,
            $status,
            $notes
        ]);
        header("Location: index.php");
        exit;
    } else {
        $error = "Brand name is required.";
    }
}

include('../../includes/header.php');
?>
<div class="container mt-4">
    <h2>Add New Brand</h2>
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <form method="POST" class="mt-3">
        <div class="mb-3">
            <label for="name" class="form-label">Brand Name*</label>
            <input type="text" class="form-control" name="name" id="name" required>
        </div>
        <div class="mb-3">
            <label for="contact_email" class="form-label">Contact Email</label>
            <input type="email" class="form-control" name="contact_email" id="contact_email">
        </div>
        <div class="mb-3">
            <label for="phone_number" class="form-label">Phone Number</label>
            <input type="text" class="form-control" name="phone_number" id="phone_number">
        </div>
        <div class="mb-3">
            <label for="website_url" class="form-label">Website URL</label>
            <input type="url" class="form-control" name="website_url" id="website_url">
        </div>
        <div class="mb-3 form-check">
            <input type="checkbox" class="form-check-input" name="authorized_reseller" id="authorized_reseller" value="1">
            <label class="form-check-label" for="authorized_reseller">Authorized Reseller</label>
        </div>
        <div class="mb-3">
            <label for="contract_document_url" class="form-label">Contract Document URL</label>
            <input type="text" class="form-control" name="contract_document_url" id="contract_document_url">
        </div>
        <div class="mb-3">
            <label for="category" class="form-label">Category</label>
            <input type="text" class="form-control" name="category" id="category">
        </div>
        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select class="form-select" name="status" id="status">
                <option value="Active">Active</option>
                <option value="Pending" selected>Pending</option>
                <option value="Blacklisted">Blacklisted</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="notes" class="form-label">Notes</label>
            <textarea class="form-control" name="notes" id="notes" rows="3"></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Create</button>
        <a href="index.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>
<?php include('../../includes/footer.php'); ?>
