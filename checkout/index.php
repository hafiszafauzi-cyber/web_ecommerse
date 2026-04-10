<?php
require_once '../config/database.php';

// Cek login
if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Ambil data user
$user_stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$user_stmt->execute([$user_id]);
$user = $user_stmt->fetch();

// Ambil cart items
$cart_stmt = $pdo->prepare("
    SELECT c.*, p.name, p.price, p.stock_quantity 
    FROM carts c 
    JOIN products p ON c.product_id = p.id 
    WHERE c.user_id = ?
");
$cart_stmt->execute([$user_id]);
$cart_items = $cart_stmt->fetchAll();

// Hitung total
$subtotal = 0;
foreach ($cart_items as $item) {
    $subtotal += $item['price'] * $item['quantity'];
}
$shipping = 20000;
$total = $subtotal + $shipping;

// Proses checkout
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $shipping_address = $_POST['shipping_address'];
    $payment_method   = $_POST['payment_method'];

    // Validasi stock
    $stock_valid = true;
    foreach ($cart_items as $item) {
        if ($item['quantity'] > $item['stock_quantity']) {
            $stock_valid = false;
            break;
        }
    }

    if (!$stock_valid) {
        $error = "Some items in your cart exceed available stock.";
    } elseif (empty($shipping_address)) {
        $error = "Shipping address is required.";
    } else {
        $pdo->beginTransaction();

        try {
            // Buat order
            $order_stmt = $pdo->prepare("
                INSERT INTO orders (user_id, total_amount, shipping_address, payment_method) 
                VALUES (?, ?, ?, ?)
            ");
            $order_stmt->execute([$user_id, $total, $shipping_address, $payment_method]);
            $order_id = $pdo->lastInsertId();

            // Insert order items dan update stock
            foreach ($cart_items as $item) {
                $order_item_stmt = $pdo->prepare("
                    INSERT INTO order_items (order_id, product_id, quantity, price) 
                    VALUES (?, ?, ?, ?)
                ");
                $order_item_stmt->execute([$order_id, $item['product_id'], $item['quantity'], $item['price']]);

                // Update stock_quantity
                $update_stmt = $pdo->prepare("
                    UPDATE products SET stock_quantity = stock_quantity - ? WHERE id = ?
                ");
                $update_stmt->execute([$item['quantity'], $item['product_id']]);

                $delete_stmt = $pdo->prepare("DELETE FROM carts WHERE id = ?");
                $delete_stmt->execute([$item['id']]);
            }

            $pdo->commit();

            header("Location: success.php?order_id=$order_id");
            exit;

        } catch (Exception $e) {
            $pdo->rollBack();
            $error = "Checkout failed: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Amazon-like Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <?php include '../includes/header.php'; ?>

    <div class="container mt-4">
        <h2>Checkout</h2>

        <?php if (count($cart_items) == 0): ?>
            <div class="alert alert-warning">
                Your cart is empty. <a href="../products/index.php">Shop now</a>
            </div>
        <?php else: ?>
            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <div class="row">
                <div class="col-md-8">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5>Shipping Information</h5>
                        </div>
                        <div class="card-body">
                            <form method="POST" id="checkoutForm">
                                <div class="mb-3">
                                    <label for="full_name" class="form-label">Full Name</label>
                                    <input type="text" class="form-control" id="full_name"
                                           value="<?php echo htmlspecialchars($user['full_name'] ?? ''); ?>" required>
                                </div>

                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email"
                                           value="<?php echo htmlspecialchars($user['email']); ?>" readonly>
                                </div>

                                <div class="mb-3">
                                    <label for="phone" class="form-label">Phone Number</label>
                                    <input type="tel" class="form-control" id="phone"
                                           value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>">
                                </div>

                                <div class="mb-3">
                                    <label for="shipping_address" class="form-label">Shipping Address</label>
                                    <textarea class="form-control" id="shipping_address" name="shipping_address"
                                              rows="3" required><?php echo htmlspecialchars($user['address'] ?? ''); ?></textarea>
                                </div>

                                <div class="mb-3">
                                    <label for="payment_method" class="form-label">Payment Method</label>
                                    <select class="form-select" id="payment_method" name="payment_method" required>
                                        <option value="">Select Payment Method</option>
                                        <option value="credit_card">Credit Card</option>
                                        <option value="debit_card">Debit Card</option>
                                        <option value="bank_transfer">Bank Transfer</option>
                                        <option value="paypal">PayPal</option>
                                        <option value="cash_on_delivery">Cash on Delivery</option>
                                    </select>
                                </div>

                                <!-- Credit Card Form -->
                                <div id="creditCardForm" style="display: none;">
                                    <div class="mb-3">
                                        <label for="card_number" class="form-label">Card Number</label>
                                        <input type="text" class="form-control" id="card_number" placeholder="1234 5678 9012 3456">
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="expiry_date" class="form-label">Expiry Date</label>
                                            <input type="text" class="form-control" id="expiry_date" placeholder="MM/YY">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="cvv" class="form-label">CVV</label>
                                            <input type="text" class="form-control" id="cvv" placeholder="123">
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Order Items -->
                    <div class="card">
                        <div class="card-header">
                            <h5>Order Items</h5>
                        </div>
                        <div class="card-body">
                            <?php foreach ($cart_items as $item): ?>
                            <div class="row mb-2 border-bottom pb-2">
                                <div class="col-md-8">
                                    <h6><?php echo htmlspecialchars($item['name']); ?></h6>
                                    <small>Quantity: <?php echo $item['quantity']; ?></small>
                                </div>
                                <div class="col-md-4 text-end">
                                    <p>Rp <?php echo number_format($item['price'] * $item['quantity'], 0, ',', '.'); ?></p>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Order Summary</h5>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Subtotal</span>
                                <span>Rp <?php echo number_format($subtotal, 0, ',', '.'); ?></span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Shipping</span>
                                <span>Rp <?php echo number_format($shipping, 0, ',', '.'); ?></span>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between mb-3">
                                <strong>Total</strong>
                                <strong>Rp <?php echo number_format($total, 0, ',', '.'); ?></strong>
                            </div>
                            <div class="d-grid">
                                <button type="submit" form="checkoutForm" class="btn btn-primary btn-lg">Place Order</button>
                            </div>
                            <div class="mt-3 text-center">
                                <a href="../cart/" class="text-decoration-none">Back to cart</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <?php include '../includes/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/script.js"></script>
    <script>
        document.getElementById('payment_method').addEventListener('change', function () {
            const creditCardForm = document.getElementById('creditCardForm');
            if (this.value === 'credit_card' || this.value === 'debit_card') {
                creditCardForm.style.display = 'block';
            } else {
                creditCardForm.style.display = 'none';
            }
        });
    </script>
</body>
</html>