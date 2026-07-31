<?php
require_once "db_connect.php";
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// ดึงประวัติการสั่งซื้อ
$sql = "SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$orders = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <title>ประวัติการสั่งซื้อ</title>
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
    .btn-detail {
      background: #f4a460;
      color: #fff;
      border-radius: 20px;
      padding: 6px 14px;
    }
    .btn-detail:hover {
      background: #cd853f;
    }
    .btn-cancel {
      background: #dc3545;
      color: #fff;
      border-radius: 20px;
      padding: 6px 14px;
    }
    .btn-cancel:hover {
      background: #b02a37;
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
    <h3 class="text-center mb-4">📜 ประวัติการสั่งซื้อ</h3>

    <?php if ($orders->num_rows > 0): ?>
      <div class="table-responsive">
        <table class="table table-bordered align-middle text-center">
          <thead class="table-light">
            <tr>
              <th>รหัสคำสั่งซื้อ</th>
              <th>วันที่</th>
              <th>ยอดรวม</th>
              <th>สถานะ</th>
              <th>การจัดการ</th>
            </tr>
          </thead>
          <tbody>
            <?php while ($order = $orders->fetch_assoc()): ?>
              <tr>
                <td>#<?= $order['order_id'] ?></td>
                <td><?= date("d/m/Y H:i", strtotime($order['created_at'])) ?></td>
                <td>฿<?= number_format($order['total_price'], 2) ?></td>
                <td>
                  <?php
                    $status = $order['status'] ?? 'pending';
                    if ($status == "pending") {
                      echo "<span class='badge-status bg-warning text-dark'>รอดำเนินการ</span>";
                    } elseif ($status == "shipped") {
                      echo "<span class='badge-status bg-info text-white'>จัดส่งแล้ว</span>";
                    } elseif ($status == "completed") {
                      echo "<span class='badge-status bg-success'>สำเร็จ</span>";
                    } elseif ($status == "canceled") {
                      echo "<span class='badge-status bg-danger'>ยกเลิกแล้ว</span>";
                    } else {
                      echo "<span class='badge-status bg-secondary'>ไม่ทราบสถานะ</span>";
                    }
                  ?>
                </td>
                <td>
                  <a href="order_detail.php?order_id=<?= $order['order_id'] ?>" class="btn btn-detail">ดูรายละเอียด</a>

                  <?php if ($status == "pending"): ?>
                    <a href="cancel_order.php?order_id=<?= $order['order_id'] ?>"
                       class="btn btn-cancel"
                       onclick="return confirm('คุณต้องการยกเลิกคำสั่งซื้อนี้หรือไม่?');">
                      ❌ ยกเลิก
                    </a>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>
    <?php else: ?>
      <p class="text-center text-muted">❌ คุณยังไม่มีประวัติการสั่งซื้อ</p>
    <?php endif; ?>

    <div class="text-center mt-4">
      <a href="index.php" class="btn btn-detail">⬅ กลับหน้าหลัก</a>
    </div>
  </div>
</div>

</body>
</html>
