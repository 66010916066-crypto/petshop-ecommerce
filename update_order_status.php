<?php
session_start();
include 'db_connect.php';
if (!isset($_SESSION['admin'])) { exit("forbidden"); }

$order_id = intval($_POST['order_id']);
$status = $conn->real_escape_string($_POST['status']);

if($conn->query("UPDATE orders SET status='$status' WHERE order_id=$order_id")){
    echo "ok";
}else{
    echo "error";
}
?>
