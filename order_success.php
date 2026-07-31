<?php
require_once "db_connect.php";
$order_id = $_GET['order_id'];

$sql = "SELECT * FROM orders WHERE order_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $order_id);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();

$sql_items = "SELECT * FROM order_items WHERE order_id = ?";
$stmt2 = $conn->prepare($sql_items);
$stmt2->bind_param("i", $order_id);
$stmt2->execute();
$items = $stmt2->get_result();
?>

<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ยืนยันคำสั่งซื้อ</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: #fefcf7;
      font-family: "Prompt", sans-serif;
    }
    .brand-logo {
      display: block;
      margin: 0 auto 25px auto;
      width: 120px;
    }
    .card {
      border-radius: 20px;
      border: none;
      box-shadow: 0px 6px 15px rgba(0,0,0,0.08);
    }
    .card-header {
      border-radius: 20px 20px 0 0 !important;
      background: #ff914d !important;
      border: none;
    }
    .card-header h3 {
      margin: 0;
      font-weight: 600;
    }
    .btn-theme {
      background-color: #ff914d;
      border: none;
      color: white;
      font-size: 1.1rem;
      font-weight: 600;
      border-radius: 12px;
      transition: 0.3s;
    }
    .btn-theme:hover {
      background-color: #ff7b2f;
    }
    ul {
      padding-left: 20px;
    }
  </style>
</head>
<body>
<div class="container py-5">
  <!-- โลโก้ร้าน -->
  <img src="logo.webp" alt="ร้าน Happy Pet House" class="brand-logo">

  <div class="card shadow p-4">
    <div class="card-header text-center text-white">
      <h4>สั่งซื้อสำเร็จ</h4>
    </div>
    <div class="card-body">
      <p><strong>ชื่อ:</strong> <?= htmlspecialchars($order['fullname']) ?></p>
      <p><strong>เบอร์โทร:</strong> <?= htmlspecialchars($order['phone']) ?></p>
      <p><strong>ที่อยู่:</strong> <?= htmlspecialchars($order['address']) ?></p>

      <h5 class="mt-4">รายการสินค้า</h5>
      <ul>
        <?php while($item = $items->fetch_assoc()): ?>
          <li><?= htmlspecialchars($item['product_name']) ?> - ฿<?= number_format($item['price'], 2) ?></li>
        <?php endwhile; ?>
      </ul>
      <p class="fw-bold fs-5">ยอดรวม: ฿<?= number_format($order['total_price'], 2) ?></p>

      <a href="index.php" class="btn btn-theme w-100 mt-3">กลับหน้าหลัก</a>
    </div>
  </div>
</div>
</body>
</html>
