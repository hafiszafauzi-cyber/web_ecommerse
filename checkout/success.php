<?php
require_once '../config/database.php';

if(!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit;
}

$order_id = $_GET['order_id'] ?? 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Success - Amazon-like Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <?php include '../includes/header.php'; ?>
    
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card text-center">
                    <div class="card-body py-5">
                        <div class="mb-4">
                            <i class="fas fa-check-circle text-success" style="font-size: 5rem;"></i>
                        </div>
                        <h2 class="card-title mb-3">Order Placed Successfully!</h2>
                        <p class="card-text mb-4">
                            Thank you for your purchase. Your order has been placed successfully.
                        </p>
                        <div class="alert alert-info mb-4">
                            <strong>Order ID:</strong> #<?php echo str_pad($order_id, 8, '0', STR_PAD_LEFT); ?><br>
                            <strong>Date:</strong> <?php echo date('F d, Y'); ?><br>
                            We will send you a confirmation email shortly.
                        </div>
                        <div class="d-grid gap-2 d-md-flex justify-content-center">
                            <a href="../index.php" class="btn btn-primary">Continue Shopping</a>
                            <a href="#" class="btn btn-outline-primary">View Order Details</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <?php include '../includes/footer.php'; ?>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>