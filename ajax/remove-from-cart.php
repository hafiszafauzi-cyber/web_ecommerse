<?php

require_once '../config/database.php';

$pdo = getDBConnection();

if(isset($_POST['cart_id'])){

$cart_id = $_POST['cart_id'];

$stmt = $pdo->prepare("DELETE FROM carts WHERE id = ?");
$stmt->execute([$cart_id]);

echo "deleted";

}

?>