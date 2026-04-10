<?php
// Home.php - FIXED VERSION
require_once 'config/database.php';
require_once 'config/constants.php';
require_once 'includes/functions.php';

$pdo = getDBConnection();

// Get featured products - TANPA kondisi is_active
$products = $pdo->query("SELECT p.*, c.name as category_name 
                        FROM products p 
                        LEFT JOIN categories c ON p.category_id = c.id 
                        ORDER BY p.created_at DESC 
                        LIMIT 8")->fetchAll();
// Get categories
$categories = $pdo->query("SELECT * FROM categories ORDER BY name LIMIT 6")->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo SITE_NAME; ?> - Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?php echo ASSETS_URL; ?>css/style.css">
    <link rel="icon" type="image/png" href="public/logo.png">
</head>
<body> 
    <style>
        /* style.css */
:root {
    --primary: #0d6efd;
    --primary-dark: #0b5ed7;
    --secondary: #6c757d;
    --light: #f8f9fa;
    --dark: #212529;
    --success: #198754;
    --danger: #dc3545;
    --warning: #ffc107;
    --info: #0dcaf0;
    --border-radius: 8px;
    --box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    --transition: all 0.3s ease;
}

/* Global Styles */
body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    color: var(--dark);
    background-color: #fff;
    line-height: 1.6;
}

/* Hero Section */
.hero {
    background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
    border-radius: 0 0 20px 20px;
    margin-bottom: 2rem;
}

.hero h1 {
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
}

.hero .btn-light {
    transition: var(--transition);
    font-weight: 600;
    padding: 12px 30px;
    border-radius: 50px;
    box-shadow: 0 4px 15px rgba(255, 255, 255, 0.2);
}

.hero .btn-light:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(255, 255, 255, 0.3);
}

.hero img {
    animation: float 6s ease-in-out infinite;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
}

@keyframes float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-15px); }
}

/* Categories Section */
.categories .card {
    border: none;
    border-radius: var(--border-radius);
    transition: var(--transition);
    box-shadow: var(--box-shadow);
    height: 100%;
    background: white;
    overflow: hidden;
}

.categories .card:hover {
    transform: translateY(-10px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
    border-color: var(--primary);
}

.categories .card-body {
    padding: 1.5rem 1rem;
}

.categories .card-body i {
    color: var(--primary);
    transition: var(--transition);
}

.categories .card:hover .card-body i {
    transform: scale(1.1);
    color: var(--primary-dark);
}

.categories .card h6 {
    font-weight: 600;
    color: var(--dark);
    margin: 0;
    transition: var(--transition);
}

.categories .card:hover h6 {
    color: var(--primary);
}

/* Products Section */
.products .card {
    border: none;
    border-radius: var(--border-radius);
    overflow: hidden;
    transition: var(--transition);
    box-shadow: var(--box-shadow);
    background: white;
}

.products .card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
}

.product-card .card-img-top {
    height: 200px;
    object-fit: cover;
    transition: var(--transition);
}

.product-card:hover .card-img-top {
    transform: scale(1.05);
}

.product-card .card-title {
    font-weight: 600;
    color: var(--dark);
    font-size: 1.1rem;
    margin-bottom: 0.5rem;
}

.product-card .text-muted {
    font-size: 0.85rem;
}

.product-card .card-text {
    color: #666;
    font-size: 0.9rem;
    margin-bottom: 1rem;
    min-height: 40px;
}

.product-card .fw-bold {
    font-size: 1.25rem;
    color: var(--primary);
}

.product-card .small i {
    margin-right: 5px;
}

.product-card .card-footer {
    background: white;
    border-top: 1px solid rgba(0,0,0,0.05);
    padding: 1rem;
}

.product-card .btn-primary {
    background: var(--primary);
    border: none;
    border-radius: 6px;
    padding: 8px;
    font-weight: 500;
    transition: var(--transition);
}

.product-card .btn-primary:hover {
    background: var(--primary-dark);
    transform: translateY(-2px);
}

.product-card .btn-success {
    background: var(--success);
    border: none;
    border-radius: 6px;
    padding: 8px;
    font-weight: 500;
    transition: var(--transition);
}

.product-card .btn-success:hover {
    background: #157347;
    transform: translateY(-2px);
}

/* View All Button */
.btn-outline-primary {
    border-radius: 50px;
    padding: 8px 25px;
    font-weight: 500;
    transition: var(--transition);
}

.btn-outline-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(13, 110, 253, 0.2);
}

/* Alert for No Products */
.alert-info {
    border-radius: var(--border-radius);
    border: none;
    background: linear-gradient(135deg, #cfe2ff 0%, #e7f1ff 100%);
    color: var(--dark);
    box-shadow: var(--box-shadow);
}

.alert-link {
    font-weight: 600;
    text-decoration: none;
}

.alert-link:hover {
    text-decoration: underline;
}

/* Responsive Design */
@media (max-width: 768px) {
    .hero {
        text-align: center;
        padding: 2rem 0;
    }
    
    .hero h1 {
        font-size: 2.5rem;
    }
    
    .categories .col-6 {
        margin-bottom: 1rem;
    }
    
    .product-card {
        margin-bottom: 1.5rem;
    }
    
    .categories .card-body {
        padding: 1rem 0.5rem;
    }
    
    .categories .card-body i {
        font-size: 2rem;
    }
}

@media (max-width: 576px) {
    .hero h1 {
        font-size: 2rem;
    }
    
    .hero .lead {
        font-size: 1rem;
    }
    
    .categories .col-6 {
        width: 50%;
    }
    
    .products .row > div {
        padding: 0 8px;
    }
}

/* Animation for Add to Cart Button */
.add-to-cart {
    position: relative;
    overflow: hidden;
}

.add-to-cart:after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 5px;
    height: 5px;
    background: rgba(255, 255, 255, 0.5);
    opacity: 0;
    border-radius: 100%;
    transform: scale(1, 1) translate(-50%);
    transform-origin: 50% 50%;
}

.add-to-cart:focus:not(:active)::after {
    animation: ripple 1s ease-out;
}

@keyframes ripple {
    0% {
        transform: scale(0, 0);
        opacity: 0.5;
    }
    20% {
        transform: scale(25, 25);
        opacity: 0.3;
    }
    100% {
        opacity: 0;
        transform: scale(40, 40);
    }
}

/* Loading State for Button */
.add-to-cart[disabled] {
    opacity: 0.7;
    cursor: not-allowed;
}

/* Section Headers */
section h2 {
    position: relative;
    padding-bottom: 10px;
    font-weight: 700;
    color: var(--dark);
}

section h2:after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 50%;
    transform: translateX(-50%);
    width: 60px;
    height: 3px;
    background: var(--primary);
    border-radius: 2px;
}

/* Card Hover Effects */
.card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1) !important;
}

/* Utility Classes */
.text-shadow {
    text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.2);
}

.hover-shadow:hover {
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
}

/* Smooth Scrolling */
html {
    scroll-behavior: smooth;
}

/* Custom Scrollbar */
::-webkit-scrollbar {
    width: 10px;
}

::-webkit-scrollbar-track {
    background: #f1f1f1;
}

::-webkit-scrollbar-thumb {
    background: var(--primary);
    border-radius: 5px;
}

::-webkit-scrollbar-thumb:hover {
    background: var(--primary-dark);
}
</style>
    <!-- Header -->
    <?php include 'includes/header.php'; ?>

    <!-- Hero Section -->
    <section class="hero bg-primary text-white py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h1 class="display-4 fw-bold">Welcome to <?php echo SITE_NAME; ?></h1>
                    <p class="lead">Your one-stop shop for all your needs. Best prices, fast delivery!</p>
                    <a href="products/index.php" class="btn btn-light btn-lg">Shop Now</a>
                </div>
                <div class="col-md-6">
                    <img src="https://via.placeholder.com/600x400" alt="Hero" class="img-fluid rounded">
                </div>
            </div>
        </div>
    </section>

    <!-- Categories -->
    <section class="categories py-5">
        <div class="container">
            <h2 class="text-center mb-5">Shop by Category</h2>
            <div class="row">
                <?php foreach ($categories as $category): ?>
                <div class="col-md-2 col-sm-4 col-6 mb-3">
                    <a href="products/?category=<?php echo $category['id']; ?>" class="text-decoration-none">
                        <div class="card text-center">
                            <div class="card-body">
                                <i class="fas fa-mobile-alt fa-3x mb-3 text-primary"></i>
                                <h6><?php echo htmlspecialchars($category['name']); ?></h6>
                            </div>
                        </div>
                    </a>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Featured Products -->
    <section class="products py-5 bg-light">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Featured Products</h2>
                <a href="products/index.php" class="btn btn-outline-primary">View All</a>
            </div>
            <div class="row">
                <?php if (count($products) > 0): ?>
                    <?php foreach ($products as $product): ?>
                    <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                        <div class="card h-100 product-card">
                           <img src="<?php echo ASSETS_URL; ?>uploads/products/<?php 
    echo !empty($product['main_image']) 
        ? htmlspecialchars($product['main_image']) 
        : 'iphone14.jpeg'; 
?>"
class="card-img-top"
alt="<?php echo htmlspecialchars($product['name']); ?>">

                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($product['name']); ?></h5>
                                <?php if (!empty($product['category_name'])): ?>
                                    <p class="text-muted small"><?php echo htmlspecialchars($product['category_name']); ?></p>
                                <?php endif; ?>
                                <p class="card-text"><?php echo htmlspecialchars(substr($product['description'] ?? '', 0, 80)); ?>...</p>
                                <p class="text-primary fw-bold"><?php echo formatPrice($product['price'] ?? 0); ?></p>
                                <p class="small <?php echo ($product['stock_quantity'] ?? 0) > 0 ? 'text-success' : 'text-danger'; ?>">
                                    <i class="fas fa-<?php echo ($product['stock_quantity'] ?? 0) > 0 ? 'check' : 'times'; ?>"></i>
                                    <?php echo ($product['stock_quantity'] ?? 0) > 0 ? 'In Stock' : 'Out of Stock'; ?>
                                </p>
                            </div>
                            <div class="card-footer">
                                <div class="d-grid gap-2">
                                    <a href="products/view.php?id=<?php echo $product['id']; ?>" class="btn btn-primary btn-sm">View Details</a>
                                    <?php if (($product['stock_quantity'] ?? 0) > 0): ?>
                                        <button class="btn btn-success btn-sm add-to-cart" data-id="<?php echo $product['id']; ?>">Add to Cart</button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12">
                        <div class="alert alert-info">
                            No products available at the moment.
                            <a href="setup.php" class="alert-link">Add sample products?</a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <?php include 'includes/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <script>
    // Add to cart
    $(document).ready(function() {
        $('.add-to-cart').click(function() {
            const productId = $(this).data('id');
            const button = $(this);
            
            button.prop('disabled', true).text('Adding...');
            
            $.ajax({
                url: 'ajax/add-to-cart.php',
                method: 'POST',
                data: { product_id: productId },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        alert('Product added to cart!');
                        if ($('.cart-count').length) {
                            $('.cart-count').text(response.cart_count);
                        }
                    } else {
                        alert('Error: ' + response.message);
                    }
                },
                error: function() {
                    alert('Network error');
                },
                complete: function() {
                    setTimeout(() => {
                        button.prop('disabled', false).text('Add to Cart');
                    }, 1000);
                }
            });
        });
    });
    </script>
</body>
</html>
