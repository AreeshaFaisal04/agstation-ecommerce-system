<?php
session_start();
require_once '../config/db.php';

// Handle add to cart logic and redirect BEFORE any HTML or includes
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['asin'])) {
    $asin = $_POST['asin'];
    $_SESSION['cart'][$asin] = ($_SESSION['cart'][$asin] ?? 0) + 1;
    header("Location: cart.php");
    exit;
}

require_once '../includes/header.php';

$cart = $_SESSION['cart'] ?? [];
$total = 0;
?>

<div class="cart-main-wrap" style="max-width:1100px; margin:40px auto 32px auto; display:flex; gap:32px; align-items:flex-start; background:none; box-shadow:none; padding:0;">
    <div style="flex:2; min-width:320px;">
        <table class="cart-table-modern w-100">
            <thead>
                <tr>
                    <th style="width:40px;"></th>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($cart as $asin => $qty):
                    $stmt = $pdo->prepare("SELECT name, retail_price, image_url FROM Products WHERE asin = ?");
                    $stmt->execute([$asin]);
                    $product = $stmt->fetch(PDO::FETCH_ASSOC);
                    $subtotal = $product['retail_price'] * $qty;
                    $total += $subtotal;
                ?>
                <tr>
                    <td>
                        <form method="post" action="remove_from_cart.php" style="display:inline;">
                            <input type="hidden" name="asin" value="<?= htmlspecialchars($asin) ?>">
                            <button type="submit" class="cart-remove-btn" title="Remove">&times;</button>
                        </form>
                    </td>
                    <td>
                        <div class="cart-product-info">
                            <img src="../uploads/product_images/<?= htmlspecialchars($product['image_url']) ?>" class="cart-product-img" alt="<?= htmlspecialchars($product['name']) ?>">
                            <span><?= htmlspecialchars($product['name']) ?></span>
                        </div>
                    </td>
                    <td style="white-space:nowrap;">$<?= number_format($product['retail_price'], 2) ?></td>
                    <td>
                        <div class="cart-qty-wrap">
                            <form method="post" action="update_cart.php" style="display:inline-flex;">
                                <input type="hidden" name="asin" value="<?= htmlspecialchars($asin) ?>">
                                <button type="submit" name="decrease" value="1" class="cart-qty-btn" <?= $qty <= 1 ? 'disabled' : '' ?>>-</button>
                                <input type="number" name="quantity" value="<?= htmlspecialchars($qty) ?>" min="1" class="cart-qty-input" onchange="this.form.submit()">
                                <button type="submit" name="increase" value="1" class="cart-qty-btn">+</button>
                            </form>
                        </div>
                    </td>
                    <td style="white-space:nowrap;">$<?= number_format($subtotal, 2) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div style="flex:1; min-width:290px;">
        <div class="cart-summary-box agstation-cart-summary">
            <div class="cart-summary-title">Address</div>
            <div class="cart-summary-row" style="font-size:1rem;">
                <span>Shipping to <b>CA.</b></span>
                <span><a href="#" style="color:#3B5BDB;text-decoration:underline;">Change address</a></span>
            </div>
            <div class="cart-summary-title" style="margin-top:24px;">Total</div>
            <div class="cart-summary-total" style="font-size:2rem;">
                $<?= number_format($total + 15, 2) ?>
            </div>
            <a href="checkout.php" class="cart-checkout-btn" style="margin-top:18px;">PROCEED TO CHECKOUT</a>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>

<!-- Close wrappers opened in header.php -->
        </div> <!-- .container mt-4 from header.php -->
    </div> <!-- .main-content -->
</div> <!-- .layout-wrapper -->
</body>
</html>
