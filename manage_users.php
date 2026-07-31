<?php
session_start();
include 'db_connect.php';
if (!isset($_SESSION['admin'])) { header("Location: admin_login.php"); exit; }

// ✅ ใช้ CONCAT รวม address1 + address2 เป็น address
$users = $conn->query("SELECT id, username, email, phone, CONCAT(IFNULL(address1,''), ' ', IFNULL(address2,'')) AS address FROM users");

if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM users WHERE id=$id");
    header("Location: manage_users.php"); exit;
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<title>จัดการลูกค้า</title>
<style>
    body{font-family:'Prompt',sans-serif;background:#fffaf6;}
    header{background:#fff;padding:15px;text-align:center;border-bottom:1px solid #eee;}
    nav a{margin:0 10px;text-decoration:none;color:#333;font-weight:bold;}
    nav a:hover{color:#d2691e;}
    table{width:90%;margin:20px auto;border-collapse:collapse;background:#fff;}
    th,td{padding:10px;border:1px solid #ddd;text-align:center;}
    th{background:#f4a460;color:#fff;}
    button{background:#d2691e;color:#fff;border:none;padding:5px 12px;border-radius:6px;cursor:pointer;}
    button:hover{background:#a0522d;}
</style>
</head>
<body>
<header>
  <h1>👤 จัดการลูกค้า</h1>
  <nav>
    <a href="admin_dashboard.php">หน้าแรกหลังบ้าน</a>
    <a href="manage_products.php">สินค้า</a>
    <a href="manage_category.php">หมวดหมู่</a>
    <a href="manage_orders.php">ออเดอร์</a>
  </nav>
</header>

<table>
  <tr><th>ID</th><th>ชื่อผู้ใช้</th><th>Email</th><th>เบอร์</th><th>ที่อยู่</th><th>จัดการ</th></tr>
  <?php while($u=$users->fetch_assoc()): ?>
    <tr>
      <td><?= $u['id']; ?></td>
      <td><?= $u['username']; ?></td>
      <td><?= $u['email']; ?></td>
      <td><?= $u['phone']; ?></td>
      <td><?= $u['address']; ?></td>
      <td>
  <a href="edit_user.php?id=<?= $u['id']; ?>"><button style="background:#f4a460;">แก้ไข</button></a>
  <a href="?delete=<?= $u['id']; ?>" onclick="return confirm('ลบลูกค้า?');"><button>ลบ</button></a>
</td>
    </tr>
  <?php endwhile; ?>
</table>
</body>
</html>
