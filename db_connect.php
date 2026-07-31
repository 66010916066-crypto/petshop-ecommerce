<?php
$host = "localhost";
$user = "root";     // ตั้งค่าตาม MySQL ของคุณ
$pass = "";         // ถ้ามีรหัสผ่านใส่ตรงนี้
$db   = "petshop";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("เชื่อมต่อฐานข้อมูลล้มเหลว: " . mysqli_connect_error());
}

// ✅ ตั้งค่า charset ให้รองรับภาษาไทย/อังกฤษ/อีโมจิ
mysqli_set_charset($conn, "utf8mb4");
?>
