<?php
session_start();
include 'db_connect.php';

// รับค่า product id
$p_id = isset($_GET['p_id']) ? intval($_GET['p_id']) : 0;

// ดึงข้อมูลสินค้า
$productQuery = $conn->query("SELECT * FROM products WHERE p_id = $p_id");
$product = $productQuery->fetch_assoc();

if (!$product) {
  echo "<p style='text-align:center;'>ไม่พบสินค้านี้</p>";
  exit;
}

// ดึงรูปสินค้า
$imageQuery = $conn->query("SELECT * FROM product_images WHERE product_id = $p_id");
$images = [];
while ($row = $imageQuery->fetch_assoc()) {
  $images[] = trim($row['image_path']); // ⭐ ตัดช่องว่าง/ \r\n ออก
}
if (empty($images)) {
  $images[] = $product['image'];
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <title><?= htmlspecialchars($product['name']); ?> | Happy Pet House</title>
  <style>
    body { margin:0; font-family:'Prompt',sans-serif; background:#fffaf6; color:#333; }
    header {
      background:#fff; padding:15px 30px; display:flex; align-items:center;
      justify-content:space-between; border-bottom:1px solid #eee; position:sticky; top:0; z-index:10;
    }
    header img { height:50px; cursor:pointer; }
    nav { flex:1; text-align:center; }
    nav a { margin:0 15px; text-decoration:none; font-weight:bold; color:#333; }
    nav a:hover { color:#d2691e; }
    .right-controls { display:flex; align-items:center; gap:10px; }
    button {
      padding:5px 12px; border:none; background:#d2691e; color:#fff;
      border-radius:20px; cursor:pointer;
    }
    button:hover { background:#a0522d; }
    .cart-btn { position:relative; padding:5px 15px; }
    .cart-count {
      position:absolute; top:-5px; right:-5px;
      background:red; color:#fff; font-size:12px; font-weight:bold;
      border-radius:50%; padding:2px 6px;
    }

    /* Container */
    .container { max-width:1000px; margin:30px auto; background:#fff; padding:20px; border-radius:10px; box-shadow:0 4px 12px rgba(0,0,0,0.1); }

    /* Gallery */
    .gallery { text-align:center; }
    .gallery-main img { max-width:350px; max-height:350px; border-radius:10px; object-fit:contain; }
    .gallery-thumbs { display:flex; gap:10px; justify-content:center; margin-top:15px; }
    .gallery-thumbs img {
      width:80px; height:80px; object-fit:cover; border-radius:8px; cursor:pointer;
      border:2px solid transparent;
    }
    .gallery-thumbs img:hover { border:2px solid #d2691e; }

    /* Details */
    .details { margin-top:20px; text-align:center; }
    .details h2 { margin-bottom:10px; }
    .price { color:#d2691e; font-size:20px; font-weight:bold; margin:10px 0; }
    .stock { margin:10px 0; }
    .btns { margin-top:20px; display:flex; justify-content:center; gap:15px; }
    .btns button { border-radius:8px; padding:10px 20px; font-weight:bold; }
    .btn-back { background:gray; }
    .btn-back:hover { background:#444; }

    /* Modal */
    #qty-modal {
      display:none; position:fixed; top:0; left:0; width:100%; height:100%;
      background:rgba(0,0,0,0.5); align-items:center; justify-content:center; z-index:999;
    }
    #qty-modal .box {
      background:#fff; padding:20px; border-radius:10px; text-align:center; width:300px;
    }
    #qty-modal input { padding:5px; width:80px; text-align:center; margin:10px 0; }
  </style>
</head>
<body>

<header>
  <a href="index.php"><img src="logo.webp" alt="logo"></a>
  <nav>
    <a href="index.php">หน้าแรก</a>
    <a href="products.php">สินค้า</a>
    <a href="about.php">เกี่ยวกับเรา</a>
    <a href="contact.php">ติดต่อเรา</a>
  </nav>
  <div class="right-controls">
    <?php if(isset($_SESSION['user_id'])): ?>
      <span>👋 สวัสดี, <?= htmlspecialchars($_SESSION['username']); ?></span>
      <button onclick="window.location.href='update_profile.php'">แก้ไขข้อมูล</button>
      <button onclick="window.location.href='order_history.php'">ประวัติการสั่งซื้อ</button>
      <button onclick="window.location.href='logout.php'">ออกจากระบบ</button>
    <?php else: ?>
      <button onclick="window.location.href='login.php'">เข้าสู่ระบบ</button>
      <button onclick="window.location.href='register.html'">สมัครสมาชิก</button>
    <?php endif; ?>
    <button class="cart-btn" onclick="goToCart()">🛒 ตะกร้า <span id="cart-count" class="cart-count">0</span></button>
  </div>
</header>

<div class="container">
  <div class="gallery">
    <div class="gallery-main">
      <img id="main-img" src="<?= $images[0]; ?>" alt="<?= $product['name']; ?>">
    </div>
    <div class="gallery-thumbs">
      <?php foreach ($images as $img): ?>
        <img src="<?= $img; ?>" onclick="document.getElementById('main-img').src='<?= $img; ?>'">
      <?php endforeach; ?>
    </div>
  </div>

  <div class="details">
    <h2><?= $product['name']; ?></h2>
    <p class="price">฿<?= number_format($product['price'],2); ?></p>
    <p class="stock">คงเหลือ: <?= $product['stock']; ?> ชิ้น</p>
    <p><?= nl2br(htmlspecialchars($product['description'])); ?></p>
    <div class="btns">
      <button onclick="openQtyModal('<?= $product['name']; ?>', <?= $product['price']; ?>)">เพิ่มใส่ตะกร้า</button>
      <button class="btn-back" onclick="window.history.back()">ย้อนกลับ</button>
    </div>
  </div>
</div>

<!-- Modal -->
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
let cart = JSON.parse(localStorage.getItem("cart")) || [];
let currentProduct = null;

function updateCartCount() {
  let totalItems = cart.reduce((sum, item) => sum + item.qty, 0);
  document.getElementById("cart-count").textContent = totalItems;
}

function openQtyModal(name, price) {
  currentProduct = { name, price };
  document.getElementById("qty-input").value = 1;
  document.getElementById("qty-modal").style.display = "flex";
}

function closeModal() {
  document.getElementById("qty-modal").style.display = "none";
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
