<?php
// cart/index.php
require_once '../config/database.php';
require_once '../config/constants.php';
require_once '../includes/functions.php';

if (!isset($_SESSION['user_id'])) {
    redirect('../auth/login.php');
}

$pdo = getDBConnection();
$user_id = $_SESSION['user_id'];

// Get cart items
$stmt = $pdo->prepare("SELECT c.*, p.name, p.price, p.main_image, p.stock_quantity 
                      FROM carts c 
                      JOIN products p ON c.product_id = p.id 
                      WHERE c.user_id = ?");
$stmt->execute([$user_id]);
$cart_items = $stmt->fetchAll();

// Calculate total
$total = 0;
foreach ($cart_items as $item) {
    $total += $item['price'] * $item['quantity'];
}
?>

<?php include '../includes/header.php'; ?>

<h2>Shopping Cart</h2>

<?php if (count($cart_items) == 0): ?>
    <div class="alert alert-info">
        Your cart is empty. <a href="../products/">Continue shopping</a>
    </div>
<?php else: ?>
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <?php foreach ($cart_items as $item): ?>
                    <div class="row mb-3 border-bottom pb-3">
                        <div class="col-md-2">
                            <img src="https://via.placeholder.com/100x100" class="img-fluid" alt="<?php echo htmlspecialchars($item['name']); ?>">
                        </div>
                        <div class="col-md-6">
                            <h5><?php echo htmlspecialchars($item['name']); ?></h5>
                            <p class="text-muted"><?php echo formatPrice($item['price']); ?></p>
                            <p>Stock: <?php echo $item['stock_quantity']; ?></p>
                        </div>
                        <div class="col-md-2">
                            <input type="number" class="form-control quantity-input" 
                                   value="<?php echo $item['quantity']; ?>" 
                                   min="1" max="<?php echo $item['stock_quantity']; ?>"
                                   data-cart-id="<?php echo $item['id']; ?>">
                        </div>
                        <div class="col-md-2 text-end">
                            <p class="fw-bold"><?php echo formatPrice($item['price'] * $item['quantity']); ?></p>
                            <button class="btn btn-danger btn-sm remove-item" data-cart-id="<?php echo $item['id']; ?>">Remove</button>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5>Order Summary</h5>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal</span>
                        <span><?php echo formatPrice($total); ?></span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Shipping</span>
                        <span>Rp 20,000</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between mb-3">
                        <strong>Total</strong>
                        <strong><?php echo formatPrice($total + 20000); ?></strong>
                    </div>
                    <div class="d-grid">
                        <a href="../checkout/" class="btn btn-primary btn-lg">Proceed to Checkout</a>
                    </div>
                    <div class="mt-3 text-center">
                        <a href="../products/" class="text-decoration-none">Continue shopping</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php include '../includes/footer.php'; ?>

<script>
    
$(document).ready(function() {
    // Update quantity
    $('.quantity-input').change(function() {
        const cartId = $(this).data('cart-id');
        const quantity = $(this).val();
        
        $.ajax({
            url: '../ajax/update-cart.php',
            method: 'POST',
            data: { cart_id: cartId, quantity: quantity },
            success: function(response) {
                location.reload();
            }
        });
    });
    
    // Remove item
    $('.remove-item').click(function() {
        const cartId = $(this).data('cart-id');
        
        if (confirm('Remove this item from cart?')) {
            $.ajax({
                url: '../ajax/remove-from-cart.php',
                method: 'POST',
                data: { cart_id: cartId },
                success: function(response) {
                    location.reload();
                }
            });
        }
    });
});

// FIX REMOVE CART (Tambahan tanpa ubah kode lama)
$(document).on("click",".remove-item",function(){

const cartId = $(this).data("cart-id");

if(confirm('Remove this item from cart?')){

$.ajax({
url: '../ajax/remove-from-cart.php',
method: 'POST',
data: { cart_id: cartId },
success: function(response){

console.log(response); // debug
location.reload();

},
error:function(){
alert("Remove gagal");
}

});

}

});

</script>