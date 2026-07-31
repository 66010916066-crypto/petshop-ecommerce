<?php
  session_start();
?>
<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ติดต่อเรา - Happy Pet House</title>
  <style>
    body { margin:0; font-family:'Prompt',sans-serif; background:#fffaf6; color:#333; }
    header {
      background:#fff; padding:15px 30px; display:flex; align-items:center; justify-content:space-between;
      border-bottom:1px solid #eee; position:sticky; top:0; z-index:10;
    }
    header img { height:50px; cursor:pointer; }
    nav { flex:1; text-align:center; }
    nav a { margin:0 15px; text-decoration:none; font-weight:bold; color:#333; }
    nav a:hover, nav a.active { color:#d2691e; }
    .right-controls { display:flex; align-items:center; gap:10px; }
    button {
      padding:5px 12px; border:none; background:#d2691e; color:#fff; border-radius:20px; cursor:pointer;
    }
    button:hover { background:#a0522d; }
    .cart-btn { position:relative; padding:5px 15px; }
    .cart-count {
      position:absolute; top:-5px; right:-5px; background:red; color:#fff;
      font-size:12px; font-weight:bold; border-radius:50%; padding:2px 6px;
    }
    footer { background:#fff; padding:30px; margin-top:50px; text-align:center; border-top:1px solid #eee; }
    .contact-section {
      max-width:900px; margin:50px auto; padding:30px; background:#fff;
      border-radius:12px; box-shadow:0 2px 8px rgba(0,0,0,0.1); text-align:center;
    }
    .contact-section h2 { color:#d2691e; margin-bottom:20px; }
    .contact-info { font-size:18px; line-height:1.8; }
    .qr-placeholder {
      width:200px; height:200px; border:2px dashed #ccc; margin:20px auto;
      display:flex; align-items:center; justify-content:center; font-size:14px; color:#aaa;
    }
  </style>
</head>
<body>

<header>
  <a href="index.php"><img src="logo.webp" alt="Happy Pet House Logo"></a>
  <nav>
    <a href="index.php">หน้าแรก</a>
    <a href="products.php">สินค้า</a>
    <a href="about.php">เกี่ยวกับเรา</a>
    <a href="contact.php" class="active">ติดต่อเรา</a>
  </nav>
  <div class="right-controls">
  <?php if(isset($_SESSION['admin']) && $_SESSION['admin'] === true): ?>
    <span>👋 สวัสดี, <?= htmlspecialchars($_SESSION['username']); ?> (Admin)</span>
    <button onclick="window.location.href='admin_dashboard.php'">จัดการระบบ</button>
    <button onclick="window.location.href='logout.php'">ออกจากระบบ</button>

  <?php elseif(isset($_SESSION['user_id'])): ?>
    <span>👋 สวัสดี, <?= htmlspecialchars($_SESSION['username']); ?></span>
    <button onclick="window.location.href='update_profile.php'">แก้ไขข้อมูล</button>
    <button onclick="window.location.href='order_history.php'">ประวัติการสั่งซื้อ</button>
    <button onclick="window.location.href='logout.php'">ออกจากระบบ</button>

  <?php else: ?>
    <button onclick="window.location.href='login.php'">เข้าสู่ระบบ</button>
    <button onclick="window.location.href='register.html'">สมัครสมาชิก</button>
  <?php endif; ?>

  <button class="cart-btn" onclick="goToCart()">🛒 ตะกร้า 
    <span id="cart-count" class="cart-count">0</span>
  </button>
</div>
</header>

<section class="contact-section">
  <h2>ติดต่อเรา</h2>
  <div class="contact-info">
    <p>📍 ที่อยู่ : 123 ต.เมือง อ.เมือง จ.มหาสารคาม</p>
    <p>📞 โทรศัพท์ : 080-123-4567</p>
    <p>💬 LINE ID : <b>@66wtuxe</b></p>
    <p>📧 อีเมล : happypethouse@gmail.com</p>
  </div>
  <div class="qr-placeholder">[ใส่ QR Code LINE ที่นี่]</div>
</section>

<footer>
  <p>© 2025 Happy Pet House - All Rights Reserved.</p>
</footer>

<script>
let cart = JSON.parse(localStorage.getItem("cart")) || [];
function updateCartCount(){
  let totalItems = cart.reduce((sum,item)=>sum+item.qty,0);
  document.getElementById("cart-count").textContent = totalItems;
}
function goToCart(){ window.location.href="cart.html"; }
updateCartCount();
</script>
</body>
</html>
