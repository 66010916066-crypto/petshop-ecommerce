<?php
session_start();

$servername = "localhost";
$dbusername = "root";
$dbpassword = "";
$dbname = "petshop";

$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

// ✅ ตรวจสอบก่อนว่าเป็น admin หรือไม่
if ($username === "web123" && $password === "web123") {
    $_SESSION['admin'] = true;
    $_SESSION['username'] = "Admin";
    header("Location: admin_dashboard.php");
    exit;
}

// ✅ ถ้าไม่ใช่ admin → ไปตรวจสอบตาราง users
$sql = "SELECT * FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();

    // ตรวจสอบรหัสผ่าน (ถ้าเก็บ hash)
    if (password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];   // id ของ user
        $_SESSION['username'] = $user['username'];
        header("Location: member_home.php");
        exit;
    } else {
        $_SESSION['error'] = "รหัสผ่านไม่ถูกต้อง";
        header("Location: login.php");
        exit;
    }
} else {
    $_SESSION['error'] = "ไม่พบผู้ใช้";
    header("Location: login.php");
    exit;
}
?>
