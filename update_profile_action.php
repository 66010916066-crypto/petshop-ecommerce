<?php
session_start();
require 'db_connect.php';

if(!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit;
}

$user_id = $_SESSION['user_id'];

// รับค่าจากฟอร์ม
$fullname = $_POST['fullname'];
$phone = $_POST['phone'];
$email = $_POST['email'];
$birthday = $_POST['birthday'];
$username = $_POST['username'];
$password = $_POST['password'];
$address1 = $_POST['address1'];
$address2 = $_POST['address2'];
$zipcode = $_POST['zipcode'];
$delivery_phone = $_POST['delivery_phone'];

if(!empty($password)){
  // ถ้ามีการเปลี่ยนรหัสผ่านให้ hash ใหม่
  $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
  $sql = "UPDATE users SET fullname=?, phone=?, email=?, birthday=?, username=?, password=?, address1=?, address2=?, zipcode=?, delivery_phone=? WHERE id=?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("ssssssssssi", $fullname, $phone, $email, $birthday, $username, $hashedPassword, $address1, $address2, $zipcode, $delivery_phone, $user_id);
} else {
  $sql = "UPDATE users SET fullname=?, phone=?, email=?, birthday=?, username=?, address1=?, address2=?, zipcode=?, delivery_phone=? WHERE id=?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("sssssssssi", $fullname, $phone, $email, $birthday, $username, $address1, $address2, $zipcode, $delivery_phone, $user_id);
}

if($stmt->execute()) {
  $_SESSION['username'] = $username; // อัปเดต session ด้วย
  header("Location: index.php?updated=1");
} else {
  echo "เกิดข้อผิดพลาด: " . $conn->error;
}
?>
