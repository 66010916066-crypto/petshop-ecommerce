<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

require_once "db_connect.php";

$user_id = $_SESSION['user_id'];

// ดึงข้อมูลผู้ใช้จาก database
$sql = "SELECT fullname, phone, CONCAT(address1,' ',address2,' ',zipcode) AS address FROM users WHERE id = ?";
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
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>กรอกข้อมูลการจัดส่ง</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: #fefcf7; /* สีพื้นหลังอ่อน */
      font-family: "Prompt", sans-serif;
    }
    .brand-logo {
      display: block;
      margin: 0 auto 20px auto;
      width: 120px;
    }
    .card {
      border-radius: 20px;
      border: none;
      box-shadow: 0px 6px 15px rgba(0,0,0,0.08);
      background: #ffffff;
    }
    .card-header {
      border-radius: 20px 20px 0 0 !important;
      background: #ff914d !important; /* สีธีม */
      border: none;
    }
    .card-header h3 {
      margin: 0;
      font-weight: 600;
    }
    .btn-theme {
      background-color: #ff914d;
      border: none;
      color: white;
      font-size: 1.1rem;
      font-weight: 600;
      border-radius: 12px;
      transition: 0.3s;
    }
    .btn-theme:hover {
      background-color: #ff7b2f;
    }
  </style>
</head>
<body>
<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-md-6">

      <!-- โลโก้ร้าน -->
      <img src="logo.webp" alt="ร้าน Happy Pet House" class="brand-logo">

      <div class="card shadow">
        <div class="card-header text-white text-center">
          <h3>กรอกข้อมูลการจัดส่ง</h3>
        </div>
        <div class="card-body p-4">
          <form method="POST" action="save_order.php">
            <div class="mb-3">
              <label class="form-label">ชื่อ-นามสกุล</label>
              <input type="text" class="form-control" name="fullname" value="<?= htmlspecialchars($user['fullname']) ?>" required>
            </div>
            <div class="mb-3">
              <label class="form-label">เบอร์โทร</label>
              <input type="text" class="form-control" name="phone" value="<?= htmlspecialchars($user['phone']) ?>" required>
            </div>
            <div class="mb-3">
              <label class="form-label">ที่อยู่</label>
              <textarea class="form-control" name="address" rows="3" required><?= htmlspecialchars($user['address']) ?></textarea>
            </div>

            <!-- ส่งข้อมูล cart จาก localStorage -->
            <input type="hidden" name="cart" id="cart-data">

            <button type="submit" class="btn btn-theme w-100">ยืนยันการสั่งซื้อ</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
// ดึง cart จาก localStorage แล้วใส่ลงใน hidden input
document.getElementById('cart-data').value = localStorage.getItem("cart");
</script>

</body>
</html>
