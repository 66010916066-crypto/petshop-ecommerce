<?php
session_start();
include 'db_connect.php'; // ไฟล์เชื่อมต่อฐานข้อมูล

// รับค่า c_id
$c_id = isset($_GET['c_id']) ? intval($_GET['c_id']) : 0;

// ดึงชื่อหมวดหมู่
$catName = "สินค้าทั้งหมด";
if ($c_id > 0) {
  $catQuery = $conn->query("SELECT name FROM category WHERE c_id = $c_id");
  if ($catQuery && $catQuery->num_rows > 0) {
    $catName = $catQuery->fetch_assoc()['name'];
  }
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($catName) ?> | Happy Pet House</title>
  <style>
    body { margin: 0; font-family: 'Prompt', sans-serif; background-color: #fffaf6; color: #333; }
    header {
      background:#fff; padding:15px 30px; display:flex; align-items:center; justify-content:space-between;
      border-bottom:1px solid #eee; position:sticky; top:0; z-index:10;
    }
    header img { height: 50px; cursor:pointer; }
    nav { flex:1; text-align:center; }
    nav a { margin:0 15px; text-decoration:none; font-weight:bold; color:#333; }
    nav a:hover, nav a.active { color:#d2691e; }
    .right-controls { display:flex; align-items:center; gap:10px; }
    button { padding:5px 12px; border:none; background:#d2691e; color:#fff; border-radius:20px; cursor:pointer; }
    button:hover { background:#a0522d; }
    .cart-btn { position:relative; padding:5px 15px; }
    .cart-count {
      position:absolute; top:-5px; right:-5px;
      background:red; color:#fff; font-size:12px; font-weight:bold;
      border-radius:50%; padding:2px 6px;
    }
    .container { max-width:1200px; margin:30px auto; padding:0 20px; }
    h2 { text-align:center; margin-bottom:20px; color:#333; }
    .product-grid {
      display:grid;
      grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
      gap:25px;
    }
    .product {
      background:#fff; border:1px solid #eee; border-radius:10px;
      padding:15px; text-align:center; transition:0.2s;
    }
    .product:hover { box-shadow:0 4px 12px rgba(0,0,0,0.1); }
    .product img { width:100%; max-height:200px; object-fit:cover; border-radius:10px; display:block; margin:0 auto; }
    .product h3 { margin:10px 0 5px; font-size:18px; }
    .product p { margin:5px 0; font-weight:bold; }
    .product button {
      background:#f4a460; color:#fff; font-weight:bold;
      border-radius:8px; padding:6px 14px;
    }
    .product button:hover { background:#cd853f; }
    a.product-link { text-decoration:none; color:inherit; display:block; }
    /* Modal */
    #qty-modal { display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); align-items:center; justify-content:center; z-index:999; }
    #qty-modal .box { background:#fff; padding:20px; border-radius:10px; text-align:center; width:300px; }
    #qty-modal input { padding:5px; width:80px; text-align:center; margin:10px 0; }
  </style>
</head>
<body>

<header>
  <a href="index.php"><img src="logo.webp" alt="Happy Pet House Logo"></a>
  <nav>
    <a href="index.php">หน้าแรก</a>
    <a href="products.php" class="active">สินค้า</a>
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

<div class="container">
  <h2><?= htmlspecialchars($catName) ?></h2>
  <div class="product-grid">
    <?php
    $query = $c_id > 0 ? "SELECT * FROM products WHERE c_id = $c_id" : "SELECT * FROM products";
    $prodQuery = $conn->query($query);
    if ($prodQuery && $prodQuery->num_rows > 0):
      while($prod = $prodQuery->fetch_assoc()):
        $p_id = (int)$prod['p_id'];
        $name = htmlspecialchars($prod['name']);
        $price = number_format((float)$prod['price'],2);
        $image = htmlspecialchars($prod['image']);
    ?>
        <div class="product" aria-label="<?= $name ?>">
          <!-- link รอบรูปและชื่อ ให้คลิกเข้า product_detail.php?p_id=... -->
          <a class="product-link" href="product_detail.php?p_id=<?= $p_id; ?>">
            <img src="<?= $image; ?>" alt="<?= $name; ?>">
            <h3><?= $name; ?></h3>
          </a>
          <p><?= $price; ?> บาท</p>
          <button onclick="addToCart('<?= $name; ?>', <?= (float)$prod['price']; ?>)">เพิ่มใส่ตะกร้า</button>
        </div>
    <?php endwhile; else: ?>
      <p style="grid-column:1/-1; text-align:center;">ไม่มีสินค้าในหมวดหมู่นี้</p>
    <?php endif; ?>
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
  const el = document.getElementById("cart-count");
  if (el) el.textContent = totalItems;
}

function addToCart(name, price) {
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
