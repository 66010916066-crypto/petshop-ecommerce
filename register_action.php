<?php
session_start();

// 1. เชื่อมต่อฐานข้อมูล
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "petshop";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 2. รับค่าจากฟอร์ม
$fullname       = $_POST['fullname'] ?? '';
$phone          = $_POST['phone'] ?? '';
$email          = $_POST['email'] ?? '';
$birthday       = $_POST['birthday'] ?? NULL;
$usernameInput  = $_POST['username'] ?? '';
$passwordInput  = $_POST['password'] ?? '';
$address1       = $_POST['address1'] ?? '';
$address2       = $_POST['address2'] ?? '';
$zipcode        = $_POST['zipcode'] ?? '';
$delivery_phone = $_POST['delivery_phone'] ?? '';
$interest       = isset($_POST['interest']) ? $_POST['interest'] : '';
$pet_name       = $_POST['pet_name'] ?? '';
$newsletter     = isset($_POST['newsletter']) ? "Yes" : "No";

if (is_array($interest)) {
    $interest = implode(", ", $interest);
}

// เข้ารหัสรหัสผ่าน
$hashedPassword = password_hash($passwordInput, PASSWORD_DEFAULT);

// 3. Insert ข้อมูล
$sql = "INSERT INTO users (fullname, phone, email, birthday, username, password, address1, address2, zipcode, delivery_phone, interest, pet_name, newsletter)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("sssssssssssss",
    $fullname, $phone, $email, $birthday, $usernameInput, $hashedPassword,
    $address1, $address2, $zipcode, $delivery_phone, $interest, $pet_name, $newsletter
);

if ($stmt->execute()) {
    // ✅ สมัครสำเร็จ → login อัตโนมัติ
    $_SESSION['user_id'] = $stmt->insert_id;
    $_SESSION['username'] = $usernameInput;
    $_SESSION['fullname'] = $fullname;

    header("Location: index.php"); // กลับไปหน้าแรก
    exit;
} else {
    echo "<h2>❌ เกิดข้อผิดพลาด</h2>";
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
