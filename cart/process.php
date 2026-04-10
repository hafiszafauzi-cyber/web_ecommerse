<?php
require_once '../config/database.php';

header('Content-Type: application/json');

// Cek login
if(!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Please login first']);
    exit;
}

$user_id = $_SESSION['user_id'];
$action = $_POST['action'] ?? '';

switch($action) {
    case 'add':
        $product_id = $_POST['product_id'] ?? 0;
        
        // Cek apakah produk sudah ada di cart
        $check = $pdo->prepare("SELECT id, quantity FROM carts WHERE user_id = ? AND product_id = ?");
        $check->execute([$user_id, $product_id]);
        $existing = $check->fetch();
        
        if($existing) {
            // Update quantity
            $stmt = $pdo->prepare("UPDATE carts SET quantity = quantity + 1 WHERE id = ?");
            $stmt->execute([$existing['id']]);
        } else {
            // Tambah baru
            $stmt = $pdo->prepare("INSERT INTO carts (user_id, product_id) VALUES (?, ?)");
            $stmt->execute([$user_id, $product_id]);
        }
        
        // Hitung total cart items
        $count = $pdo->prepare("SELECT COUNT(*) FROM carts WHERE user_id = ?");
        $count->execute([$user_id]);
        $cart_count = $count->fetchColumn();
        
        echo json_encode(['success' => true, 'cart_count' => $cart_count]);
        break;
        
    case 'update':
        $cart_id = $_POST['cart_id'] ?? 0;
        $quantity = $_POST['quantity'] ?? 1;
        
        $stmt = $pdo->prepare("UPDATE carts SET quantity = ? WHERE id = ? AND user_id = ?");
        $stmt->execute([$quantity, $cart_id, $user_id]);
        
        echo json_encode(['success' => $stmt->rowCount() > 0]);
        break;
        
    case 'remove':
        $cart_id = $_POST['cart_id'] ?? 0;
        
        $stmt = $pdo->prepare("DELETE FROM carts WHERE id = ? AND user_id = ?");
        $stmt->execute([$cart_id, $user_id]);
        
        echo json_encode(['success' => $stmt->rowCount() > 0]);
        break;
        
    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
}
?>