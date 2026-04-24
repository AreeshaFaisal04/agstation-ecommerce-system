<?php
include '../../../includes/session.php';
include '../../../includes/header.php';
require_once '../../../config/db.php';
checkRole(['Admin', 'admin', 1]); // Accepts role name or role id

// Validate and sanitize the ID parameter
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    header("Location: index.php");
    exit;
}

try {
    // Fetch the invoice details
    $query = "SELECT * FROM SellerInvoices WHERE invoice_id = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$id]);
    $invoice = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$invoice) {
        header("Location: index.php");
        exit;
    }

    // Fetch related data for dropdowns
    $payments = $pdo->query("SELECT payment_id FROM Payments")->fetchAll(PDO::FETCH_ASSOC);
    $brands = $pdo->query("SELECT brand_id, name FROM Brands")->fetchAll(PDO::FETCH_ASSOC);
    $distributors = $pdo->query("SELECT distributor_id, name FROM Distributors")->fetchAll(PDO::FETCH_ASSOC);
    $services = $pdo->query("SELECT service_id, name FROM ThirdPartyServices")->fetchAll(PDO::FETCH_ASSOC);

    // Handle form submission
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $payment_id = trim($_POST['payment_id']);
        $brand_id = !empty($_POST['brand_id']) ? trim($_POST['brand_id']) : null;
        $distributor_id = !empty($_POST['distributor_id']) ? trim($_POST['distributor_id']) : null;
        $service_id = !empty($_POST['service_id']) ? trim($_POST['service_id']) : null;
        $issue_date = trim($_POST['issue_date']);
        $due_date = trim($_POST['due_date']);
        $total_amount = trim($_POST['total_amount']);
        $status = trim($_POST['status']);
        $items = trim($_POST['items']);

        // Update the seller invoice
        $update_query = "UPDATE SellerInvoices 
                         SET payment_id = ?, brand_id = ?, distributor_id = ?, service_id = ?, 
                             issue_date = ?, due_date = ?, total_amount = ?, status = ?, items = ? 
                         WHERE invoice_id = ?";
        $update_stmt = $pdo->prepare($update_query);
        $update_stmt->execute([$payment_id, $brand_id, $distributor_id, $service_id, $issue_date, $due_date, $total_amount, $status, $items, $id]);
        header("Location: index.php");
        exit;
    }
} catch (PDOException $e) {
    $error = "Database error: " . $e->getMessage();
}
?>

<div class="container mt-4">
    <h2>Edit Seller Invoice</h2>
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <form method="POST">
        <div class="mb-3">
            <label for="payment_id" class="form-label">Payment ID</label>
            <select name="payment_id" class="form-select" required>
                <?php foreach ($payments as $payment): ?>
                    <option value="<?= htmlspecialchars($payment['payment_id']) ?>" <?= ($payment['payment_id'] == $invoice['payment_id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($payment['payment_id']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="brand_id" class="form-label">Brand</label>
            <select name="brand_id" class="form-select">
                <option value="">None</option>
                <?php foreach ($brands as $brand): ?>
                    <option value="<?= htmlspecialchars($brand['brand_id']) ?>" <?= ($brand['brand_id'] == $invoice['brand_id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($brand['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="distributor_id" class="form-label">Distributor</label>
            <select name="distributor_id" class="form-select">
                <option value="">None</option>
                <?php foreach ($distributors as $distributor): ?>
                    <option value="<?= htmlspecialchars($distributor['distributor_id']) ?>" <?= ($distributor['distributor_id'] == $invoice['distributor_id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($distributor['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="service_id" class="form-label">Third Party Service</label>
            <select name="service_id" class="form-select">
                <option value="">None</option>
                <?php foreach ($services as $service): ?>
                    <option value="<?= htmlspecialchars($service['service_id']) ?>" <?= ($service['service_id'] == $invoice['service_id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($service['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="issue_date" class="form-label">Issue Date</label>
            <input type="date" name="issue_date" class="form-control" value="<?= htmlspecialchars($invoice['issue_date']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="due_date" class="form-label">Due Date</label>
            <input type="date" name="due_date" class="form-control" value="<?= htmlspecialchars($invoice['due_date']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="total_amount" class="form-label">Total Amount</label>
            <input type="number" step="0.01" name="total_amount" class="form-control" value="<?= htmlspecialchars($invoice['total_amount']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select name="status" class="form-select" required>
                <?php foreach (['Unpaid', 'Paid', 'Overdue'] as $s): ?>
                    <option value="<?= htmlspecialchars($s) ?>" <?= ($s == $invoice['status']) ? 'selected' : '' ?>><?= htmlspecialchars($s) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="items" class="form-label">Items</label>
            <textarea name="items" class="form-control" rows="4"><?= htmlspecialchars($invoice['items']) ?></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>

<?php include '../../../includes/footer.php'; ?>
