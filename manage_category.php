<?php
session_start();
include 'db_connect.php';
if (!isset($_SESSION['admin'])) { header("Location: admin_login.php"); exit; }

if (isset($_GET['delete'])) {
    $id=intval($_GET['delete']);
    $conn->query("DELETE FROM category WHERE c_id=$id");
    header("Location: manage_category.php"); exit;
}
if (isset($_POST['add'])) {
    $name=$_POST['name']; $image=$_POST['image'];
    $conn->query("INSERT INTO category(name,image) VALUES('$name','$image')");
}
if (isset($_POST['edit'])) {
    $id=$_POST['c_id']; $name=$_POST['name']; $image=$_POST['image'];
    $conn->query("UPDATE category SET name='$name',image='$image' WHERE c_id=$id");
}
$cats=$conn->query("SELECT * FROM category");
?>
<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<title>จัดการหมวดหมู่</title>
<style>
    body{font-family:'Prompt',sans-serif;background:#fffaf6;}
    header{background:#fff;padding:15px;text-align:center;border-bottom:1px solid #eee;}
    nav a{margin:0 10px;text-decoration:none;color:#333;font-weight:bold;}
    nav a:hover{color:#d2691e;}
    table{width:80%;margin:20px auto;border-collapse:collapse;background:#fff;}
    th,td{padding:10px;border:1px solid #ddd;text-align:center;}
    th{background:#f4a460;color:#fff;}
    input{width:90%;padding:8px;margin:5px 0;border:1px solid #ccc;border-radius:5px;}
    button{background:#d2691e;color:#fff;padding:5px 12px;border:none;border-radius:6px;cursor:pointer;}
    button:hover{background:#a0522d;}
    form{margin:20px auto;width:60%;background:#fff;padding:15px;border-radius:10px;}
</style>
</head>
<body>
<header>
  <h1>📂 จัดการหมวดหมู่สินค้า</h1>
  <nav>
    <a href="admin_dashboard.php">หน้าแรกหลังบ้าน</a>
    <a href="manage_products.php">สินค้า</a>
    <a href="manage_users.php">ลูกค้า</a>
    <a href="manage_orders.php">ออเดอร์</a>
  </nav>
</header>

<form method="post">
  <input type="hidden" name="c_id" id="c_id">
  <input type="text" name="name" id="name" placeholder="ชื่อหมวดหมู่" required>
  <input type="text" name="image" id="image" placeholder="ลิงก์รูปภาพ">
  <button name="add">เพิ่มหมวดหมู่</button>
  <button name="edit">บันทึกแก้ไข</button>
</form>

<table>
  <tr><th>ID</th><th>ชื่อหมวดหมู่</th><th>จัดการ</th></tr>
  <?php while($c=$cats->fetch_assoc()): ?>
    <tr>
      <td><?= $c['c_id']; ?></td>
      <td><?= $c['name']; ?></td>
      <td>
        <button onclick="editCat(<?= htmlspecialchars(json_encode($c)); ?>)">แก้ไข</button>
        <a href="?delete=<?= $c['c_id']; ?>" onclick="return confirm('ลบหมวดหมู่?');"><button>ลบ</button></a>
      </td>
    </tr>
  <?php endwhile; ?>
</table>

<script>
function editCat(c){
  document.getElementById('c_id').value=c.c_id;
  document.getElementById('name').value=c.name;
  document.getElementById('image').value=c.image;
}
</script>
</body>
</html>
