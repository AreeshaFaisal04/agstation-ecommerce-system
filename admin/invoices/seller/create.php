<?php
include '../../../includes/session.php';
require_once '../../../config/db.php';
checkRole(['Admin', 'admin', 1]);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $brand_id = !empty($_POST['brand_id']) ? $_POST['brand_id'] : null;
    $distributor_id = !empty($_POST['distributor_id']) ? $_POST['distributor_id'] : null;
    $service_id = !empty($_POST['service_id']) ? $_POST['service_id'] : null;
    $payment_id = !empty($_POST['payment_id']) ? $_POST['payment_id'] : null;
    $issue_date = trim($_POST['issue_date']);
    $due_date = trim($_POST['due_date']);
    $total_amount = trim($_POST['total_amount']);
    $status = trim($_POST['status']);
    $items = trim($_POST['items']);

    // At least one entity must be selected
    if (!$brand_id && !$distributor_id && !$service_id) {
        $error = "Please select at least one: Brand, Distributor, or Third Party Service.";
    } else {
        try {
            $query = "INSERT INTO SellerInvoices 
                (brand_id, distributor_id, service_id, payment_id, issue_date, due_date, total_amount, status, items)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($query);
            $stmt->execute([
                $brand_id ?: null,
                $distributor_id ?: null,
                $service_id ?: null,
                $payment_id ?: null,
                $issue_date,
                $due_date,
                $total_amount,
                $status,
                $items
            ]);
            header('Location: index.php');
            exit;
        } catch (PDOException $e) {
            $error = "Error adding seller invoice: " . $e->getMessage();
        }
    }
}

// Fetch dropdown data
try {
    $brands = $pdo->query("SELECT brand_id, name FROM Brands WHERE status = 'Active'")->fetchAll(PDO::FETCH_ASSOC);
    $distributors = $pdo->query("SELECT distributor_id, name FROM Distributors WHERE status = 'Active'")->fetchAll(PDO::FETCH_ASSOC);
    $services = $pdo->query("SELECT service_id, name FROM ThirdPartyServices WHERE status = 'Active'")->fetchAll(PDO::FETCH_ASSOC);
    $payments = $pdo->query("SELECT payment_id, amount FROM SupplierPayments WHERE status = 'Processed'")->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}

include '../../../includes/header.php';
?>

<div class="container mt-4">
    <h2>Add Seller Invoice</h2>
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <form method="POST">
        <div class="mb-3">
            <label for="brand_id" class="form-label">Brand (optional)</label>
            <select name="brand_id" class="form-select">
                <option value="">-- None --</option>
                <?php foreach ($brands as $brand): ?>
                    <option value="<?= htmlspecialchars($brand['brand_id']) ?>"><?= htmlspecialchars($brand['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="distributor_id" class="form-label">Distributor (optional)</label>
            <select name="distributor_id" class="form-select">
                <option value="">-- None --</option>
                <?php foreach ($distributors as $distributor): ?>
                    <option value="<?= htmlspecialchars($distributor['distributor_id']) ?>"><?= htmlspecialchars($distributor['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="service_id" class="form-label">Third Party Service (optional)</label>
            <select name="service_id" class="form-select">
                <option value="">-- None --</option>
                <?php foreach ($services as $service): ?>
                    <option value="<?= htmlspecialchars($service['service_id']) ?>"><?= htmlspecialchars($service['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="payment_id" class="form-label">Supplier Payment (optional)</label>
            <select name="payment_id" class="form-select">
                <option value="">-- None --</option>
                <?php foreach ($payments as $payment): ?>
                    <option value="<?= htmlspecialchars($payment['payment_id']) ?>">
                        <?= htmlspecialchars($payment['payment_id']) ?> (<?= htmlspecialchars($payment['amount']) ?>)
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="issue_date" class="form-label">Issue Date</label>
            <input type="date" name="issue_date" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="due_date" class="form-label">Due Date</label>
            <input type="date" name="due_date" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="total_amount" class="form-label">Total Amount</label>
            <input type="number" step="0.01" name="total_amount" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select name="status" class="form-select" required>
                <option value="Unpaid">Unpaid</option>
                <option value="Paid">Paid</option>
                <option value="Overdue">Overdue</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="items" class="form-label">Items</label>
            <textarea name="items" class="form-control" rows="4"></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Save</button>
    </form>
</div>

<?php include '../../../includes/footer.php'; ?>