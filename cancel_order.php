<?php
require_once "db_connect.php";
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$order_id = $_GET['order_id'] ?? 0;

// อัปเดตสถานะเป็น canceled (เฉพาะถ้ายัง pending)
$sql = "UPDATE orders SET status='canceled' WHERE order_id=? AND status='pending'";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $order_id);
$stmt->execute();

header("Location: order_history.php");
exit;
