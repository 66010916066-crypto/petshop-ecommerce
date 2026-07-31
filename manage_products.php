<?php
session_start();
include 'db_connect.php';
if (!isset($_SESSION['admin'])) { header("Location: admin_login.php"); exit; }

// ลบสินค้า
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM products WHERE p_id=$id");
    header("Location: manage_products.php");
    exit;
}

// เพิ่มสินค้า
if (isset($_POST['add'])) {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $image = $_POST['image'];
    $desc = $_POST['description'];
    $c_id = $_POST['c_id'];
    $conn->query("INSERT INTO products(name,price,image,description,c_id) 
                  VALUES('$name','$price','$image','$desc','$c_id')");
}

// แก้ไขสินค้า
if (isset($_POST['edit'])) {
    $id = $_POST['p_id'];
    $name = $_POST['name'];
    $price = $_POST['price'];
    $image = $_POST['image'];
    $desc = $_POST['description'];
    $c_id = $_POST['c_id'];
    $conn->query("UPDATE products SET name='$name',price='$price',image='$image',description='$desc',c_id='$c_id'
                  WHERE p_id=$id");
}
$products = $conn->query("SELECT p.*, c.name AS cat_name FROM products p LEFT JOIN category c ON p.c_id=c.c_id");
$cats = $conn->query("SELECT * FROM category");
?>
<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<title>จัดการสินค้า</title>
<style>
    body { font-family:'Prompt',sans-serif; background:#fffaf6; }
    header { background:#fff; padding:15px; border-bottom:1px solid #eee; text-align:center; }
    nav a { margin:0 10px; font-weight:bold; text-decoration:none; color:#333; }
    nav a:hover { color:#d2691e; }
    table { width:90%; margin:20px auto; border-collapse:collapse; background:#fff; }
    th,td { padding:10px; border:1px solid #ddd; text-align:center; }
    th { background:#f4a460; color:#fff; }
    button { background:#d2691e; color:#fff; border:none; padding:5px 12px; border-radius:6px; cursor:pointer; }
    button:hover { background:#a0522d; }
    form { margin:20px auto; width:90%; background:#fff; padding:15px; border-radius:10px; }
    input,select,textarea { width:95%; margin:5px 0; padding:8px; border:1px solid #ccc; border-radius:5px; }
</style>
</head>
<body>
<header>
  <h1>📦 จัดการสินค้า</h1>
  <nav>
    <a href="admin_dashboard.php">หน้าแรกหลังบ้าน</a>
    <a href="manage_category.php">หมวดหมู่</a>
    <a href="manage_users.php">ลูกค้า</a>
    <a href="manage_orders.php">ออเดอร์</a>
  </nav>
</header>

<h2 style="text-align:center;">เพิ่ม/แก้ไขสินค้า</h2>
<form method="post">
    <input type="hidden" name="p_id" id="p_id">
    <input type="text" name="name" id="name" placeholder="ชื่อสินค้า" required>
    <input type="number" name="price" id="price" placeholder="ราคา" required>
    <input type="text" name="image" id="image" placeholder="ลิงก์รูปภาพ">
    <textarea name="description" id="description" placeholder="รายละเอียดสินค้า"></textarea>
    <select name="c_id" id="c_id">
      <?php while($c=$cats->fetch_assoc()): ?>
        <option value="<?= $c['c_id']; ?>"><?= $c['name']; ?></option>
      <?php endwhile; ?>
    </select>
    <button type="submit" name="add">เพิ่มสินค้า</button>
    <button type="submit" name="edit">บันทึกการแก้ไข</button>
</form>

<h2 style="text-align:center;">รายการสินค้า</h2>
<table>
  <tr><th>ID</th><th>ชื่อสินค้า</th><th>ราคา</th><th>หมวดหมู่</th><th>จัดการ</th></tr>
  <?php while($p=$products->fetch_assoc()): ?>
    <tr>
      <td><?= $p['p_id']; ?></td>
      <td><?= $p['name']; ?></td>
      <td><?= number_format($p['price'],2); ?></td>
      <td><?= $p['cat_name']; ?></td>
      <td>
        <button onclick="editProduct(<?= htmlspecialchars(json_encode($p)); ?>)">แก้ไข</button>
        <a href="?delete=<?= $p['p_id']; ?>" onclick="return confirm('ยืนยันการลบ?');"><button>ลบ</button></a>
      </td>
    </tr>
  <?php endwhile; ?>
</table>

<script>
function editProduct(p){
  document.getElementById('p_id').value = p.p_id;
  document.getElementById('name').value = p.name;
  document.getElementById('price').value = p.price;
  document.getElementById('image').value = p.image;
  document.getElementById('description').value = p.description;
  document.getElementById('c_id').value = p.c_id;
}
</script>
</body>
</html>
