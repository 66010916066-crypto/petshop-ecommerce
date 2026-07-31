<?php
session_start();
include 'db_connect.php';
$query = $conn->query("SELECT * FROM products WHERE c_id = 1");
$categoryName = "อาหารสุนัข";
?>
<?php include 'product_template.php'; ?>
