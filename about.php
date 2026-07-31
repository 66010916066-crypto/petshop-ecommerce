<?php
session_start();
include 'db_connect.php'; // เพื่อให้ header/โค้ดเหมือน index.php
?>
<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>เกี่ยวกับเรา - Happy Pet House</title>
  <style>
    /* --- คัดลอกสไตล์จาก index.php มาเต็ม ๆ เพื่อให้ header ตรงกันทุกตัวอักษร --- */
    body {
      margin: 0;
      font-family: 'Prompt', sans-serif;
      background-color: #fffaf6;
      color: #333;
    }
    header {
      background: #fff;
      padding: 15px 30px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      border-bottom: 1px solid #eee;
      position: sticky;
      top: 0;
      z-index: 10;
    }
    header img { height: 50px; cursor: pointer; }
    nav { flex: 1; text-align: center; }
    nav a {
      margin: 0 15px;
      text-decoration: none;
      font-weight: bold;
      color: #333;
    }
    nav a:hover { color: #d2691e; }
    .right-controls { display: flex; align-items: center; gap: 10px; }
    button {
      padding: 5px 12px; border: none; background: #d2691e; color: #fff;
      border-radius: 20px; cursor: pointer;
    }
    button:hover { background: #a0522d; }
    .cart-btn { position: relative; padding: 5px 15px; }
    .cart-count {
      position: absolute; top: -5px; right: -5px;
      background: red; color: #fff; font-size: 12px;
      font-weight: bold; border-radius: 50%; padding: 2px 6px;
    }
    .banner {
      width: 100%; height: 400px;
      background: url("Band.webp") no-repeat center center;
      background-size: contain; background-color: #fdfdfd;
      margin-bottom: 30px;
    }
    .categories, .products { text-align: center; margin: 20px auto; max-width: 1200px; }
    .category-grid, .product-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 30px;
    }
    .category img, .product img {
      width: 100%; max-height: 200px; object-fit: cover; border-radius: 10px;
    }
    .category h3, .product h3 { margin: 10px 0 5px; }
    .product p { margin: 5px 0; }
    .product button {
      background: #f4a460; color: #fff; font-weight: bold;
      border-radius: 8px; padding: 6px 14px;
    }
    .product button:hover { background: #cd853f; }
    footer {
      background: #fff; padding: 30px; margin-top: 50px;
      text-align: center; border-top: 1px solid #eee;
    }

    /* Modal (จาก index.php) */
    #qty-modal {
      display:none;
      position:fixed; top:0; left:0; width:100%; height:100%;
      background:rgba(0,0,0,0.5);
      align-items:center; justify-content:center; z-index:999;
    }
    #qty-modal .box {
      background:#fff; padding:20px; border-radius:10px;
      text-align:center; width:300px;
    }
    #qty-modal input {
      padding:5px; width:80px; text-align:center; margin:10px 0;
    }

    /* About section specific */
    .about-section {
      max-width: 900px;
      margin: 30px auto 60px;
      padding: 30px;
      background: #fff;
      border-radius: 12px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }
    .about-section h2 { color:#d2691e; text-align:center; margin-bottom:18px; }
    .about-section p { line-height:1.8; font-size:18px; }
  </style>
</head>
<body>

<!-- --- Header (ตรงกับ index.php ทุกประการ) --- -->
<header>
  <a href="index.php"><img src="logo.webp" alt="Happy Pet House Logo"></a>
  <nav>
    <a href="index.php">หน้าแรก</a>
    <a href="products.php">สินค้า</a>
    <a href="about.php">เกี่ยวกับเรา</a>
    <a href="contact.php">ติดต่อเรา</a>
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
<!-- --- End Header --- -->

<!-- (แสดง banner เหมือน index เพื่อให้ layout/spacing เท่ากัน) -->
<div class="banner"></div>

<!-- About content -->
<section class="about-section" role="main">
  <h2>เกี่ยวกับเรา</h2>
  <p>
    ยินดีต้อนรับสู่ <strong>Happy Pet House</strong> — ร้านขายอาหารและอุปกรณ์สำหรับสัตว์เลี้ยงของคุณ<br>
    ตั้งอยู่ที่ <strong>123 ต.เมือง อ.เมือง จ.มหาสารคาม</strong>
  </p>
  <p>
    เราคัดสรรสินค้าที่มีคุณภาพ ทั้งอาหารสุนัข แมว กระต่าย และอุปกรณ์ต่าง ๆ
    เพื่อความสุขและสุขภาพที่ดีของสัตว์เลี้ยงแสนรักของคุณ
  </p>
  <p>
    หากต้องการสอบถามเพิ่มเติม ติดต่อเราได้ที่กลุ่ม <strong>WEB DBISS66</strong> หรือผ่านหน้าติดต่อของเว็บไซต์
  </p>
</section>

<footer>
  <p>Happy Pet House © 2025 | สินค้าคุณภาพเพื่อสัตว์เลี้ยงที่คุณรัก</p>
</footer>

<!-- Modal (เหมือน index.php) -->
<div id="qty-modal">
  <div class="box">
    <h3>เลือกจำนวนสินค้า</h3>
    <input type="number" id="qty-input" min="1" max="50" value="1">
    <br>
    <button onclick="confirmQty()">ตกลง</button>
    <button onclick="closeModal()" style="background:gray; margin-left:10px;">ยกเลิก</button>
  </div>
</div>

<script>
/* --- สคริปต์เดียวกับ index.php (cart logic + modal) --- */
let cart = JSON.parse(localStorage.getItem("cart")) || [];
let currentProduct = null;

function updateCartCount() {
  let totalItems = cart.reduce((sum, item) => sum + item.qty, 0);
  const el = document.getElementById("cart-count");
  if(el) el.textContent = totalItems;
}

function addToCart(name, price) {
  currentProduct = { name, price };
  const qtyInput = document.getElementById("qty-input");
  if(qtyInput) qtyInput.value = 1;
  const modal = document.getElementById("qty-modal");
  if(modal) modal.style.display = "flex";
}

function closeModal() {
  const modal = document.getElementById("qty-modal");
  if(modal) modal.style.display = "none";
  currentProduct = null;
}

function confirmQty() {
  let qty = parseInt(document.getElementById("qty-input").value);
  if (isNaN(qty) || qty < 1 || qty > 50) {
    alert("กรุณาเลือกจำนวน 1-50");
    return;
  }

  let existing = cart.find(item => item.name === currentProduct.name);
  if (existing) {
    existing.qty += qty;
  } else {
    cart.push({ ...currentProduct, qty });
  }

  localStorage.setItem("cart", JSON.stringify(cart));
  updateCartCount();
  closeModal();
  alert(currentProduct.name + " จำนวน " + qty + " ชิ้น ถูกเพิ่มไปยังตะกร้าแล้ว!");
}

function goToCart() {
  window.location.href = "cart.html";
}

updateCartCount();
</script>
</body>
</html>
