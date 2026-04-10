<?php
require_once '../config/database.php';
require_once '../config/constants.php';
require_once '../includes/functions.php';

$pdo = getDBConnection();

// Validasi ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: ../index.php");
    exit;
}

$product_id = (int) $_GET['id'];

// Ambil detail produk
$stmt = $pdo->prepare("
    SELECT p.*, c.name AS category_name
    FROM products p
    LEFT JOIN categories c ON p.category_id = c.id
    WHERE p.id = ?
    LIMIT 1
");
$stmt->execute([$product_id]);
$product = $stmt->fetch();

if (!$product) {
    echo "Produk tidak ditemukan";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($product['name']); ?> - <?php echo SITE_NAME; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo ASSETS_URL; ?>css/style.css">
     <link rel="icon" type="image/png" href="public/logo.png">
</head>
<body>

<?php include '../includes/header.php'; ?>

<div class="container py-5">
    <div class="row">
        <!-- Gambar Produk -->
        <div class="col-md-5 mb-4">
           <img src="<?php echo ASSETS_URL; ?>uploads/products/<?php 
echo !empty($product['main_image']) 
? htmlspecialchars($product['main_image']) 
: 'iphone14.jpeg'; 
?>" 
class="card-img-top" 
                 class="img-fluid rounded shadow"
                 alt="<?php echo htmlspecialchars($product['name']); ?>">
        </div>

        <!-- Detail Produk -->
        <div class="col-md-7">
            <h2 class="fw-bold"><?php echo htmlspecialchars($product['name']); ?></h2>

            <?php if (!empty($product['category_name'])): ?>
                <p class="text-muted">Kategori: <?php echo htmlspecialchars($product['category_name']); ?></p>
            <?php endif; ?>

            <h4 class="text-primary fw-bold mb-3">
                <?php echo formatPrice($product['price']); ?>
            </h4>

            <p class="mb-3">
                <?php echo nl2br(htmlspecialchars($product['description'] ?? '')); ?>
            </p>

            <p class="fw-semibold <?php echo $product['stock_quantity'] > 0 ? 'text-success' : 'text-danger'; ?>">
                <?php echo $product['stock_quantity'] > 0 ? '✔ Stok tersedia' : '✖ Stok habis'; ?>
            </p>

            <?php if ($product['stock_quantity'] > 0): ?>
                <button class="btn btn-success add-to-cart"
                        data-id="<?php echo $product['id']; ?>">
                    Add to Cart
                </button>
            <?php endif; ?>

            <a href="../home.php" class="btn btn-outline-secondary ms-2">
                ← Kembali
            </a>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$('.add-to-cart').click(function() {
    const id = $(this).data('id');

    $.post('../ajax/add-to-cart.php', { product_id: id }, function(res) {
        if (res.success) {
            alert('Produk berhasil ditambahkan ke cart');
            $('.cart-count').text(res.cart_count);
        } else {
            alert(res.message);
        }
    }, 'json');
});
</script>

</body>
</html>
