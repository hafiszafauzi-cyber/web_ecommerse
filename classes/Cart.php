<?php
class Cart {
    private $pdo;
    private $userId;
    private $sessionId;
    
    public function __construct($pdo, $userId = null) {
        $this->pdo = $pdo;
        $this->userId = $userId;
        $this->sessionId = session_id();
    }
    
    public function addItem($productId, $quantity = 1, $attributes = []) {
        // Check if product exists and has stock
        $product = $this->getProduct($productId);
        if (!$product) {
            return ['success' => false, 'message' => 'Product not found'];
        }
        
        if ($product['stock_quantity'] < $quantity) {
            return ['success' => false, 'message' => 'Insufficient stock'];
        }
        
        // Check if item already in cart
        $existing = $this->getCartItem($productId, $attributes);
        
        if ($existing) {
            // Update quantity
            $newQuantity = $existing['quantity'] + $quantity;
            if ($newQuantity > $product['stock_quantity']) {
                return ['success' => false, 'message' => 'Exceeds available stock'];
            }
            
            $sql = "UPDATE carts SET quantity = ?, updated_at = NOW() WHERE id = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$newQuantity, $existing['id']]);
        } else {
            // Add new item
            $sql = "INSERT INTO carts (user_id, session_id, product_id, quantity, attributes) 
                    VALUES (?, ?, ?, ?, ?)";
            $stmt = $this->pdo->prepare($sql);
            $attributesJson = !empty($attributes) ? json_encode($attributes) : null;
            $stmt->execute([$this->userId, $this->sessionId, $productId, $quantity, $attributesJson]);
        }
        
        return ['success' => true, 'cart_count' => $this->getTotalItems()];
    }
    
    public function updateItem($cartId, $quantity) {
        // Implementation
    }
    
    public function removeItem($cartId) {
        // Implementation
    }
    
    public function getCartItems() {
        // Implementation
    }
    
    public function getTotal() {
        // Implementation
    }
    
    public function clearCart() {
        // Implementation
    }
    
    public function mergeGuestCart($userId) {
        // Merge guest cart with user cart after login
    }
}
?>