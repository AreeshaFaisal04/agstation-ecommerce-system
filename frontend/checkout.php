<?php
session_start();
require_once '../config/db.php';
require_once '../includes/header.php';

if (!isset($_SESSION['customer_id'])) {
    header("Location: login.php");
    exit;
}

$cart = $_SESSION['cart'] ?? [];
if (empty($cart)) {
    echo "<div class='container mt-4'><div class='alert alert-warning text-center'>Your cart is empty.</div></div>";
    require_once '../includes/footer.php';
    exit;
}

$customer_id = $_SESSION['customer_id'];
$total = 0;
$order_items = [];
$customer = null;

// Fetch customer info
$stmt = $pdo->prepare("SELECT name, email, phone, shipping_address FROM WebsiteCustomers WHERE customer_id = ?");
$stmt->execute([$customer_id]);
$customer = $stmt->fetch(PDO::FETCH_ASSOC);

// Fetch cart product details
foreach ($cart as $asin => $qty) {
    $stmt = $pdo->prepare("SELECT asin, name, retail_price, image_url FROM Products WHERE asin = ?");
    $stmt->execute([$asin]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($product) {
        $product['quantity'] = $qty;
        $product['subtotal'] = $product['retail_price'] * $qty;
        $order_items[] = $product;
        $total += $product['subtotal'];
    }
}

// Handle order placement
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $payment_method = $_POST['payment_method'] ?? 'Card';
    $shipping_address = trim($_POST['shipping_address'] ?? $customer['shipping_address']);
    $payment_status = ($payment_method === 'PayPal') ? 'Paid' : 'Paid'; // Both methods set as 'Paid'

    // Insert order
    $stmt = $pdo->prepare("INSERT INTO WebsiteOrders (customer_id, total_amount, payment_status, status) VALUES (?, ?, ?, ?)");
    $stmt->execute([$customer_id, $total, $payment_status, 'Pending']);
    $order_id = $pdo->lastInsertId();

    // Insert order items
    foreach ($order_items as $item) {
        $stmt = $pdo->prepare("INSERT INTO OrderItems (order_id, product_id, quantity, price_per_unit) VALUES (?, ?, ?, ?)");
        $stmt->execute([$order_id, $item['asin'], $item['quantity'], $item['retail_price']]);
    }

    // Store card or PayPal details if selected
    if ($payment_method === 'Card') {
        $card_holder = $_POST['card_holder'] ?? '';
        $card_number = $_POST['card_number'] ?? '';
        $card_expiry = $_POST['card_expiry'] ?? '';
        $card_cvv = $_POST['card_cvv'] ?? '';
        $stmt = $pdo->prepare("INSERT INTO CustomerCards (customer_id, card_holder, card_number, card_expiry, card_cvv) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$customer_id, $card_holder, $card_number, $card_expiry, $card_cvv]);
    } elseif ($payment_method === 'PayPal') {
        $paypal_email = $_POST['paypal_email'] ?? '';
        // You may want to create a CustomerPayPal table for storing PayPal emails
        $stmt = $pdo->prepare("INSERT INTO CustomerPayPal (customer_id, paypal_email) VALUES (?, ?)");
        $stmt->execute([$customer_id, $paypal_email]);
    }

    // Insert payment record (only allowed values)
    $allowed_methods = ['Card', 'PayPal'];
    if (!in_array($payment_method, $allowed_methods)) {
        $payment_method = 'Card';
    }
    $stmt = $pdo->prepare("INSERT INTO Payments (order_id, method, amount, status) VALUES (?, ?, ?, ?)");
    $stmt->execute([$order_id, $payment_method, $total, $payment_status]);

    // Update shipping address if changed
    if ($shipping_address && $shipping_address !== $customer['shipping_address']) {
        $stmt = $pdo->prepare("UPDATE WebsiteCustomers SET shipping_address = ? WHERE customer_id = ?");
        $stmt->execute([$shipping_address, $customer_id]);
    }

    // Clear cart
    unset($_SESSION['cart']);

    // Show order completion and bill
    ?>
    <div class="container mt-5 mb-5">
        <div class="alert alert-success text-center">
            <h3>Thank you for your order!</h3>
            <p>Your order ID is <strong>#<?= htmlspecialchars($order_id) ?></strong>.</p>
        </div>
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-primary text-white fw-bold">Order Bill / Receipt</div>
            <div class="card-body">
                <p><strong>Name:</strong> <?= htmlspecialchars($customer['name']) ?></p>
                <p><strong>Email:</strong> <?= htmlspecialchars($customer['email']) ?></p>
                <p><strong>Shipping Address:</strong> <?= nl2br(htmlspecialchars($shipping_address)) ?></p>
                <p><strong>Payment Method:</strong> <?= htmlspecialchars($payment_method === 'COD' ? 'Cash on Delivery' : $payment_method) ?></p>
                <hr>
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Unit Price</th>
                            <th>Qty</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($order_items as $item): ?>
                            <tr>
                                <td><?= htmlspecialchars($item['name']) ?></td>
                                <td>$<?= number_format($item['retail_price'], 2) ?></td>
                                <td><?= $item['quantity'] ?></td>
                                <td>$<?= number_format($item['subtotal'], 2) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="3" class="text-end">Total:</th>
                            <th>$<?= number_format($total, 2) ?></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        <div class="text-center">
            <a href="orders.php" class="btn btn-outline-primary">View My Orders</a>
            <a href="products.php" class="btn btn-outline-secondary">Continue Shopping</a>
        </div>
    </div>
    <?php
    require_once '../includes/footer.php';
    exit;
}
?>

<div class="container mt-4 mb-5">
    <h2 class="text-center mb-4" style="color:#3D52A0;font-weight:700;">Checkout</h2>
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white fw-bold">Order Summary</div>
                <div class="card-body">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Unit Price</th>
                                <th>Qty</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($order_items as $item): ?>
                                <tr>
                                    <td>
                                        <img src="../uploads/product_images/<?= htmlspecialchars($item['image_url']) ?>" alt="<?= htmlspecialchars($item['name']) ?>" style="width:48px;height:48px;object-fit:contain;border-radius:6px;margin-right:8px;">
                                        <?= htmlspecialchars($item['name']) ?>
                                    </td>
                                    <td>$<?= number_format($item['retail_price'], 2) ?></td>
                                    <td><?= $item['quantity'] ?></td>
                                    <td>$<?= number_format($item['subtotal'], 2) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="3" class="text-end">Total:</th>
                                <th>$<?= number_format($total, 2) ?></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <form method="post" class="card shadow-sm p-4" id="checkoutForm" autocomplete="off">
                <h5 class="mb-3 fw-bold" style="color:#3D52A0;">Shipping Details</h5>
                <div class="mb-3">
                    <label for="shipping_address" class="form-label">Shipping Address</label>
                    <textarea name="shipping_address" id="shipping_address" class="form-control" rows="2" required><?= htmlspecialchars($customer['shipping_address'] ?? '') ?></textarea>
                </div>
                <h5 class="mb-3 fw-bold" style="color:#3D52A0;">Payment Method</h5>
                <div class="mb-3">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="payment_method" id="payCard" value="Card" checked onclick="toggleCardFields(true)">
                        <label class="form-check-label" for="payCard">Credit/Debit Card</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="payment_method" id="payPaypal" value="PayPal" onclick="toggleCardFields(false)">
                        <label class="form-check-label" for="payPaypal">PayPal</label>
                    </div>
                </div>
                <div id="cardFields">
                    <div class="mb-3">
                        <label for="card_holder" class="form-label">Card Holder Name</label>
                        <input type="text" name="card_holder" id="card_holder" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="card_number" class="form-label">Card Number</label>
                        <input type="text" name="card_number" id="card_number" class="form-control" maxlength="32">
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="card_expiry" class="form-label">Expiry (MM/YY)</label>
                            <input type="text" name="card_expiry" id="card_expiry" class="form-control" maxlength="7">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="card_cvv" class="form-label">CVV</label>
                            <input type="text" name="card_cvv" id="card_cvv" class="form-control" maxlength="4">
                        </div>
                    </div>
                </div>
                <div class="mb-3" id="paypalFields" style="display:none;">
                    <label for="paypal_email" class="form-label">PayPal Email</label>
                    <input type="email" name="paypal_email" id="paypal_email" class="form-control">
                </div>
                <button type="submit" class="btn btn-primary w-100 fw-bold">Place Order</button>
            </form>
        </div>
    </div>
</div>

<script>
function toggleCardFields(show) {
    document.getElementById('cardFields').style.display = show ? 'block' : 'none';
    document.getElementById('paypalFields').style.display = (!show && document.getElementById('payPaypal').checked) ? 'block' : 'none';
}
// Set initial state
document.addEventListener('DOMContentLoaded', function() {
    toggleCardFields(document.getElementById('payCard').checked);
    document.querySelectorAll('input[name="payment_method"]').forEach(function(el) {
        el.addEventListener('change', function() {
            toggleCardFields(document.getElementById('payCard').checked);
        });
    });
});
</script>

<?php require_once '../includes/footer.php'; ?>

<!-- Close wrappers opened in header.php -->
        </div> <!-- .container mt-4 from header.php -->
    </div> <!-- .main-content -->
</div> <!-- .layout-wrapper -->
</body>
</html>
