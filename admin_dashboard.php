<?php
session_start();
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard - Happy Pet House</title>
  <style>
    body {
      margin: 0;
      font-family: 'Prompt', sans-serif;
      background-color: #fffaf6;
      color: #333;
    }
    header {
      background: #fff;
      padding: 15px 30px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      border-bottom: 1px solid #eee;
    }
    header img { height: 50px; cursor: pointer; }
    nav { flex: 1; text-align: center; }
    nav a {
      margin: 0 15px;
      text-decoration: none;
      font-weight: bold;
      color: #333;
    }
    nav a:hover { color: #d2691e; }
    .right-controls { display: flex; align-items: center; gap: 10px; }
    button {
      padding: 6px 16px; border: none; background: #d2691e; color: #fff;
      border-radius: 20px; cursor: pointer;
    }
    button:hover { background: #a0522d; }
    .dashboard {
      max-width: 1000px;
      margin: 40px auto;
      text-align: center;
    }
    h2 { color: #d2691e; margin-bottom: 30px; }
    .grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
      gap: 30px;
    }
    .card {
      background: #fff;
      padding: 25px;
      border-radius: 15px;
      box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }
    .card h3 { margin: 15px 0; }
    .card button {
      background: #f4a460;
      font-weight: bold;
      border-radius: 8px;
    }
    .card button:hover { background: #cd853f; }
  </style>
</head>
<body>

<header>
  <a href="index.php"><img src="logo.webp" alt="Happy Pet House Logo"></a>
  <nav>
    <a href="index.php">หน้าแรก</a>
    <a href="products.php">สินค้า</a>
    <a href="about.php">เกี่ยวกับเรา</a>
    <a href="contact.php">ติดต่อเรา</a>
  </nav>
  <div class="right-controls">
    <span>👋 ยินดีต้อนรับ, <?= htmlspecialchars($_SESSION['username']); ?> (Admin)</span>
    <button onclick="window.location.href='logout.php'">ออกจากระบบ</button>
  </div>
</header>

<div class="dashboard">
  <h2>📋 แผงควบคุมผู้ดูแลระบบ</h2>
  <div class="grid">
    <div class="card">
      <h3>สินค้า</h3>
      <p>เพิ่ม, แก้ไข, ลบ และดูสินค้าทั้งหมด</p>
      <button onclick="window.location.href='manage_products.php'">จัดการสินค้า</button>
    </div>
    <div class="card">
      <h3>หมวดหมู่สินค้า</h3>
      <p>เพิ่ม, แก้ไข, ลบ และดูหมวดหมู่สินค้า</p>
      <button onclick="window.location.href='manage_category.php'">จัดการหมวดหมู่</button>
    </div>
    <div class="card">
      <h3>ลูกค้า</h3>
      <p>ดูรายชื่อลูกค้า, แก้ไข, หรือลบข้อมูล</p>
      <button onclick="window.location.href='manage_users.php'">จัดการลูกค้า</button>
    </div>
    <div class="card">
      <h3>ออเดอร์สินค้า</h3>
      <p>ดูรายการสั่งซื้อ, ตรวจสอบสถานะ และรายละเอียด</p>
      <button onclick="window.location.href='manage_orders.php'">จัดการออเดอร์</button>
    </div>
  </div>
</div>

</body>
</html>
