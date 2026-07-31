<?php
session_start();
require 'db_connect.php'; // เชื่อมต่อฐานข้อมูล

if(!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit;
}

// ดึงข้อมูลผู้ใช้จาก database
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <title>แก้ไขข้อมูลส่วนตัว - Happy Pet House</title>
  <style>
    body { font-family: 'Prompt', sans-serif; background-color: #fffaf6; padding: 20px; }
    h1 { text-align: center; color: #d2691e; }
    form { max-width: 700px; margin: 0 auto; background: #fff; padding: 25px; border-radius: 15px; box-shadow: 0 4px 8px rgba(0,0,0,0.1);}
    fieldset { border: 1px solid #ddd; border-radius: 10px; margin-bottom: 20px; padding: 15px;}
    legend { font-weight: bold; color: #a0522d; }
    input { width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 8px; margin-bottom: 10px; }
    button { background: #d2691e; color: #fff; padding: 12px; border: none; border-radius: 10px; cursor: pointer; width: 100%; }
    button:hover { background: #a0522d; }
  </style>
</head>
<body>
  <h1>แก้ไขข้อมูลส่วนตัว</h1>

  <form action="update_profile_action.php" method="POST">
    <!-- ข้อมูลส่วนตัว -->
    <fieldset>
      <legend>1. ข้อมูลส่วนตัว</legend>
      <label>ชื่อ–นามสกุล</label>
      <input type="text" name="fullname" value="<?= htmlspecialchars($user['fullname']); ?>" required>

      <label>เบอร์โทรศัพท์</label>
      <input type="tel" name="phone" value="<?= htmlspecialchars($user['phone']); ?>" required>

      <label>อีเมล</label>
      <input type="email" name="email" value="<?= htmlspecialchars($user['email']); ?>" required>

      <label>วันเดือนปีเกิด</label>
      <input type="date" name="birthday" value="<?= htmlspecialchars($user['birthday']); ?>">
    </fieldset>

    <!-- ข้อมูลเข้าสู่ระบบ -->
    <fieldset>
      <legend>2. ข้อมูลสำหรับเข้าสู่ระบบ</legend>
      <label>ชื่อผู้ใช้ (Username)</label>
      <input type="text" name="username" value="<?= htmlspecialchars($user['username']); ?>" required>

      <label>รหัสผ่าน (ถ้าไม่เปลี่ยนให้เว้นว่าง)</label>
      <input type="password" name="password">
    </fieldset>

    <!-- ที่อยู่ -->
    <fieldset>
      <legend>3. ที่อยู่สำหรับจัดส่งสินค้า</legend>
      <label>บ้านเลขที่ / หมู่บ้าน / อาคาร</label>
      <input type="text" name="address1" value="<?= htmlspecialchars($user['address1']); ?>">

      <label>ถนน / แขวง / เขต / ตำบล / อำเภอ / จังหวัด</label>
      <input type="text" name="address2" value="<?= htmlspecialchars($user['address2']); ?>">

      <label>รหัสไปรษณีย์</label>
      <input type="text" name="zipcode" value="<?= htmlspecialchars($user['zipcode']); ?>">

      <label>เบอร์โทรติดต่อสำหรับขนส่ง</label>
      <input type="tel" name="delivery_phone" value="<?= htmlspecialchars($user['delivery_phone']); ?>">
    </fieldset>

    <button type="submit">บันทึกการแก้ไข</button>
  </form>
</body>
</html>
