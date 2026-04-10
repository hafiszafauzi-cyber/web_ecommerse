<?php
// ajax/add-to-cart.php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Please login first']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    exit;
}

$product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
$user_id = $_SESSION['user_id'];

if ($product_id <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid product']);
    exit;
}

// Database connection
try {
    $pdo = new PDO('mysql:host=localhost;dbname=ecommerce_db', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error']);
    exit;
}

// Check product
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ? AND is_active = 1");
$stmt->execute([$product_id]);
$product = $stmt->fetch();

if (!$product) {
    echo json_encode(['success' => false, 'message' => 'Product not found']);
    exit;
}

if ($product['stock_quantity'] <= 0) {
    echo json_encode(['success' => false, 'message' => 'Product out of stock']);
    exit;
}

// Check if already in cart
$check_stmt = $pdo->prepare("SELECT * FROM carts WHERE user_id = ? AND product_id = ?");
$check_stmt->execute([$user_id, $product_id]);
$existing = $check_stmt->fetch();

if ($existing) {
    // Update quantity
    $new_quantity = $existing['quantity'] + 1;
    if ($new_quantity > $product['stock_quantity']) {
        echo json_encode(['success' => false, 'message' => 'Cannot add more than available stock']);
        exit;
    }
    
    $update_stmt = $pdo->prepare("UPDATE carts SET quantity = ? WHERE id = ?");
    $update_stmt->execute([$new_quantity, $existing['id']]);
} else {
    // Add new item
    $insert_stmt = $pdo->prepare("INSERT INTO carts (user_id, product_id, quantity) VALUES (?, ?, 1)");
    $insert_stmt->execute([$user_id, $product_id]);
}

// Get cart count
$count_stmt = $pdo->prepare("SELECT SUM(quantity) as total FROM carts WHERE user_id = ?");
$count_stmt->execute([$user_id]);
$cart_count = $count_stmt->fetchColumn() ?: 0;

echo json_encode([
    'success' => true,
    'message' => 'Product added to cart',
    'cart_count' => $cart_count
]);
?>