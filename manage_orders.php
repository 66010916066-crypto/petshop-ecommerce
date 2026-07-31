<?php
session_start();
include 'db_connect.php';
if (!isset($_SESSION['admin'])) { 
    header("Location: admin_login.php"); 
    exit; 
}

// ✅ อัปเดตสถานะออเดอร์
if (isset($_POST['update_status'])) {
    $order_id = intval($_POST['order_id']);
    $status = $_POST['status'];
    $conn->query("UPDATE orders SET status='$status' WHERE order_id=$order_id");
    header("Location: manage_orders.php"); 
    exit;
}

// ✅ ดึงรายการออเดอร์
$orders=$conn->query("SELECT o.*, u.username, 
                      CONCAT(u.address1, ' ', u.address2) as address 
                      FROM orders o 
                      LEFT JOIN users u ON o.user_id=u.id
                      ORDER BY o.created_at DESC");
?>
<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<title>จัดการออเดอร์</title>
<style>
    body{font-family:'Prompt',sans-serif;background:#fffaf6;}
    header{background:#fff;padding:15px;text-align:center;border-bottom:1px solid #eee;}
    nav a{margin:0 10px;text-decoration:none;color:#333;font-weight:bold;}
    nav a:hover{color:#d2691e;}
    table{width:95%;margin:20px auto;border-collapse:collapse;background:#fff;}
    th,td{padding:10px;border:1px solid #ddd;text-align:center;}
    th{background:#f4a460;color:#fff;}
    .subtable{margin:10px auto;width:90%;border:1px solid #ccc;}
    select,button{padding:5px 10px;border:none;border-radius:6px;cursor:pointer;}
    button{background:#d2691e;color:#fff;}
    button:hover{background:#a0522d;}
</style>
</head>
<body>
<header>
  <h1>🧾 จัดการออเดอร์</h1>
  <nav>
    <a href="admin_dashboard.php">หน้าแรกหลังบ้าน</a>
    <a href="manage_products.php">สินค้า</a>
    <a href="manage_category.php">หมวดหมู่</a>
    <a href="manage_users.php">ลูกค้า</a>
  </nav>
</header>

<?php while($o=$orders->fetch_assoc()): ?>
  <table>
    <tr>
      <th>เลขที่ออเดอร์</th><th>ลูกค้า</th><th>ที่อยู่จัดส่ง</th><th>ราคารวม</th><th>วันที่สั่ง</th><th>สถานะ</th><th>อัปเดต</th>
    </tr>
    <tr>
      <td><?= $o['order_id']; ?></td>
      <td><?= $o['username']; ?></td>
      <td><?= $o['address']; ?></td>
      <td><?= number_format($o['total_price'],2); ?> บาท</td>
      <td><?= $o['created_at']; ?></td>
      <td><?= $o['status']; ?></td>
      <td>
        <form method="post" style="display:inline;">
          <input type="hidden" name="order_id" value="<?= $o['order_id']; ?>">
          <select name="status">
            <option value="pending" <?= $o['status']=="pending"?"selected":""; ?>>รอชำระ</option>
            <option value="shipped" <?= $o['status']=="shipped"?"selected":""; ?>>จัดส่งแล้ว</option>
            <option value="completed" <?= $o['status']=="completed"?"selected":""; ?>>สำเร็จ</option>
          </select>
          <button type="submit" name="update_status">อัปเดต</button>
        </form>
      </td>
    </tr>
    <tr>
      <td colspan="7">
        <b>รายละเอียดสินค้า:</b>
        <table class="subtable">
          <tr><th>สินค้า</th><th>จำนวน</th><th>ราคา</th></tr>
          <?php
            $items=$conn->query("SELECT product_name, quantity, price 
                                 FROM order_items 
                                 WHERE order_id=".$o['order_id']);
            while($i=$items->fetch_assoc()):
          ?>
            <tr>
              <td><?= $i['product_name']; ?></td>
              <td><?= $i['quantity']; ?></td>
              <td><?= number_format($i['price'],2); ?></td>
            </tr>
          <?php endwhile; ?>
        </table>
      </td>
    </tr>
  </table>
<?php endwhile; ?>
</body>
</html>
