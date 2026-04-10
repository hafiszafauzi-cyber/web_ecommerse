<?php
// products/index.php
require_once '../config/database.php';
require_once '../config/constants.php';
require_once '../includes/functions.php';

$pdo = getDBConnection();

// Get parameters
$search = isset($_GET['search']) ? sanitize($_GET['search']) : '';
$category_id = isset($_GET['category']) ? intval($_GET['category']) : 0;
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$limit = ITEMS_PER_PAGE;
$offset = ($page - 1) * $limit;

// Build query
$sql = "SELECT p.*, c.name as category_name FROM products p 
        LEFT JOIN categories c ON p.category_id = c.id 
        WHERE p.is_active = 1";
$params = [];
$count_params = [];

if (!empty($search)) {
    $sql .= " AND (p.name LIKE ? OR p.description LIKE ?)";
    $search_term = "%$search%";
    $params[] = $search_term;
    $params[] = $search_term;
    $count_params[] = $search_term;
    $count_params[] = $search_term;
}

if ($category_id > 0) {
    $sql .= " AND p.category_id = ?";
    $params[] = $category_id;
    $count_params[] = $category_id;
}

// Get total count
$count_sql = "SELECT COUNT(*) as total FROM products p 
              LEFT JOIN categories c ON p.category_id = c.id 
              WHERE p.is_active = 1";
if (!empty($search)) {
    $count_sql .= " AND (p.name LIKE ? OR p.description LIKE ?)";
}
if ($category_id > 0) {
    $count_sql .= " AND p.category_id = ?";
}

$count_stmt = $pdo->prepare($count_sql);
$count_stmt->execute($count_params);
$total_count = $count_stmt->fetchColumn();
$total_pages = ceil($total_count / $limit);

// Add sorting and pagination
$sql .= " ORDER BY p.created_at DESC LIMIT " . intval($limit) . " OFFSET " . intval($offset);

// Get products
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll();

// Get categories for filter
$categories = $pdo->query("SELECT * FROM categories WHERE is_active = 1")->fetchAll();
?>

<?php include '../includes/header.php'; ?>
<head> 
<meta charset="UTF-8">
<title>Luxury Shop</title>

<link rel="icon" type="image/png" href="public/logo.png">

<link rel="stylesheet" href="/ecommerce-amazon/assets/css/style.css">
</head>


<div class="row">
    <!-- Sidebar -->
    <div class="col-md-3">
        <div class="card mb-4">
            <div class="card-body">
                <h5>Filters</h5>
                
                <!-- Search -->
                <form method="GET" class="mb-3">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" placeholder="Search..." value="<?php echo htmlspecialchars($search); ?>">
                        <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i></button>
                    </div>
                </form>
                
                <!-- Categories -->
                <h6>Categories</h6>
                <div class="list-group">
                    <a href="?search=<?php echo urlencode($search); ?>" 
                       class="list-group-item list-group-item-action <?php echo $category_id == 0 ? 'active' : ''; ?>">
                        All Categories
                    </a>
                    <?php foreach ($categories as $cat): ?>
                    <a href="?search=<?php echo urlencode($search); ?>&category=<?php echo $cat['id']; ?>" 
                       class="list-group-item list-group-item-action <?php echo $category_id == $cat['id'] ? 'active' : ''; ?>">
                        <?php echo htmlspecialchars($cat['name']); ?>
                    </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Main Content -->
    <div class="col-md-9">
        <h2>Products</h2>
        <?php if (!empty($search)): ?>
            <p>Search results for: "<strong><?php echo htmlspecialchars($search); ?></strong>"</p>
        <?php endif; ?>
        
        <div class="row">
            <?php if (count($products) > 0): ?>
                <?php foreach ($products as $product): ?>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card h-100">
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
                            <p class="card-text"><?php echo htmlspecialchars(substr($product['description'], 0, 100)); ?>...</p>
                            <p class="text-primary fw-bold"><?php echo formatPrice($product['price']); ?></p>
                            <p class="small <?php echo $product['stock_quantity'] > 0 ? 'text-success' : 'text-danger'; ?>">
                                <i class="fas fa-<?php echo $product['stock_quantity'] > 0 ? 'check' : 'times'; ?>"></i>
                                <?php echo $product['stock_quantity'] > 0 ? 'In Stock' : 'Out of Stock'; ?>
                                (<?php echo $product['stock_quantity']; ?>)
                            </p>
                        </div>
                        <div class="card-footer">
                            <div class="d-grid gap-2">
                                <a href="view.php?id=<?php echo $product['id']; ?>" class="btn btn-primary btn-sm">View Details</a>
                                <?php if ($product['stock_quantity'] > 0): ?>
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
                        <?php if (!empty($search)): ?>
                            No products found for "<?php echo htmlspecialchars($search); ?>"
                        <?php else: ?>
                            No products available.
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        
        <!-- Pagination -->
        <?php if ($total_pages > 1): ?>
            <nav class="mt-4">
                <ul class="pagination justify-content-center">
                    <?php if ($page > 1): ?>
                        <li class="page-item">
                            <a class="page-link" href="?search=<?php echo urlencode($search); ?>&category=<?php echo $category_id; ?>&page=<?php echo $page - 1; ?>">Previous</a>
                        </li>
                    <?php endif; ?>
                    
                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <?php if ($i == 1 || $i == $total_pages || ($i >= $page - 2 && $i <= $page + 2)): ?>
                            <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                                <a class="page-link" href="?search=<?php echo urlencode($search); ?>&category=<?php echo $category_id; ?>&page=<?php echo $i; ?>"><?php echo $i; ?></a>
                            </li>
                        <?php endif; ?>
                    <?php endfor; ?>
                    
                    <?php if ($page < $total_pages): ?>
                        <li class="page-item">
                            <a class="page-link" href="?search=<?php echo urlencode($search); ?>&category=<?php echo $category_id; ?>&page=<?php echo $page + 1; ?>">Next</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </nav>
        <?php endif; ?>
    </div>
</div>

<?php include '../includes/footer.php'; ?>

<script>
$(document).ready(function() {
    $('.add-to-cart').click(function() {
        const productId = $(this).data('id');
        const button = $(this);
        
        button.prop('disabled', true).text('Adding...');
        
        $.ajax({
            url: '../ajax/add-to-cart.php',
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
<script src="../assets/js/products.js"></script>
