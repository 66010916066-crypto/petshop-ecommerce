<?php
session_start();
include 'db_connect.php';
if (!isset($_SESSION['admin'])) { header("Location: admin_login.php"); exit; }

$id = intval($_GET['id']);
$user = $conn->query("SELECT * FROM users WHERE id=$id")->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email    = $_POST['email'];
    $phone    = $_POST['phone'];
    $address1 = $_POST['address1'];
    $address2 = $_POST['address2'];

    $stmt = $conn->prepare("UPDATE users SET username=?, email=?, phone=?, address1=?, address2=? WHERE id=?");
    $stmt->bind_param("sssssi", $username, $email, $phone, $address1, $address2, $id);
    $stmt->execute();

    header("Location: manage_users.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<title>แก้ไขลูกค้า</title>
<style>
    body{font-family:'Prompt',sans-serif;background:#fffaf6;}
    header{background:#fff;padding:15px;text-align:center;border-bottom:1px solid #eee;}
    form{max-width:500px;margin:30px auto;background:#fff;padding:20px;border-radius:10px;box-shadow:0 2px 6px rgba(0,0,0,0.1);}
    label{display:block;margin:10px 0 5px;font-weight:bold;}
    input{width:100%;padding:8px;border:1px solid #ccc;border-radius:6px;}
    button{margin-top:15px;background:#d2691e;color:#fff;border:none;padding:10px 16px;border-radius:6px;cursor:pointer;}
    button:hover{background:#a0522d;}
</style>
</head>
<body>
<header><h1>✏️ แก้ไขลูกค้า</h1></header>

<form method="post">
  <label>ชื่อผู้ใช้</label>
  <input type="text" name="username" value="<?= htmlspecialchars($user['username']); ?>" required>

  <label>Email</label>
  <input type="email" name="email" value="<?= htmlspecialchars($user['email']); ?>" required>

  <label>เบอร์โทร</label>
  <input type="text" name="phone" value="<?= htmlspecialchars($user['phone']); ?>">

  <label>ที่อยู่ 1</label>
  <input type="text" name="address1" value="<?= htmlspecialchars($user['address1']); ?>">

  <label>ที่อยู่ 2</label>
  <input type="text" name="address2" value="<?= htmlspecialchars($user['address2']); ?>">

  <button type="submit">บันทึกการแก้ไข</button>
</form>
</body>
</html>
