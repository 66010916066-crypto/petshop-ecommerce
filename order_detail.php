<?php
require_once "db_connect.php";
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$order_id = $_GET['order_id'] ?? 0;

// ดึงข้อมูลคำสั่งซื้อ
$sql = "SELECT * FROM orders WHERE order_id = ? AND user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $order_id, $_SESSION['user_id']);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();

if (!$order) {
    die("❌ ไม่พบคำสั่งซื้อ");
}

// ดึงสินค้าในคำสั่งซื้อ
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
  <title>รายละเอียดคำสั่งซื้อ #<?= $order_id ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #fffaf6;
      font-family: 'Prompt', sans-serif;
    }
    .navbar {
      background: #fff;
      border-bottom: 1px solid #eee;
    }
    .navbar-brand img {
      height: 45px;
    }
    .card {
      border-radius: 15px;
    }
    h3 {
      color: #d2691e;
    }
    .btn-back {
      background: #f4a460;
      color: #fff;
      border-radius: 20px;
      padding: 6px 16px;
    }
    .btn-back:hover {
      background: #cd853f;
    }
    .badge-status {
      font-size: 14px;
      padding: 6px 12px;
      border-radius: 12px;
    }
  </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar px-3">
  <a class="navbar-brand" href="index.php">
    <img src="logo.webp" alt="Happy Pet House Logo">
  </a>
  <span class="ms-auto">👋 <?= htmlspecialchars($_SESSION['username']); ?></span>
</nav>

<div class="container my-5">
  <div class="card shadow p-4">
    <h3 class="text-center mb-4">🧾 รายละเอียดคำสั่งซื้อ #<?= $order_id ?></h3>

    <p><strong>👤 ชื่อผู้สั่ง:</strong> <?= htmlspecialchars($order['fullname']) ?></p>
    <p><strong>📞 เบอร์โทร:</strong> <?= htmlspecialchars($order['phone']) ?></p>
    <p><strong>📍 ที่อยู่จัดส่ง:</strong> <?= htmlspecialchars($order['address']) ?></p>
    <p><strong>📅 วันที่สั่งซื้อ:</strong> <?= date("d/m/Y H:i", strtotime($order['created_at'])) ?></p>
    <p><strong>🚚 สถานะ:</strong> 
      <?php
        if ($order['status'] == "pending") {
          echo "<span class='badge-status bg-warning text-dark'>รอดำเนินการ</span>";
        } elseif ($order['status'] == "shipped") {
          echo "<span class='badge-status bg-info text-white'>จัดส่งแล้ว</span>";
        } elseif ($order['status'] == "completed") {
          echo "<span class='badge-status bg-success'>สำเร็จ</span>";
        } else {
          echo "<span class='badge-status bg-secondary'>ไม่ทราบสถานะ</span>";
        }
      ?>
    </p>

    <h5 class="mt-4">📦 รายการสินค้า</h5>
    <div class="table-responsive">
      <table class="table table-bordered align-middle text-center">
        <thead class="table-light">
          <tr>
            <th>สินค้า</th>
            <th>จำนวน</th>
            <th>ราคา</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($item = $items->fetch_assoc()): ?>
            <tr>
              <td><?= htmlspecialchars($item['product_name']) ?></td>
              <td><?= $item['quantity'] ?> ชิ้น</td>
              <td>฿<?= number_format($item['price'], 2) ?></td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>

    <p class="fw-bold text-end">💰 ยอดรวม: ฿<?= number_format($order['total_price'], 2) ?></p>

    <div class="text-center">
      <a href="order_history.php" class="btn btn-back">⬅ กลับไปประวัติการสั่งซื้อ</a>
    </div>
  </div>
</div>

</body>
</html>
