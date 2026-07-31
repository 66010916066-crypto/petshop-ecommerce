<?php
session_start();
include 'db_connect.php';

$q = $_GET['q'] ?? '';
?>

<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <title>ผลการค้นหา: <?= htmlspecialchars($q) ?></title>
  <style>
    body { font-family:'Prompt',sans-serif; background:#fffaf6; margin:0; }
    header {
      background:#fff; padding:15px 30px; display:flex; align-items:center; justify-content:space-between;
      border-bottom:1px solid #eee; position:sticky; top:0; z-index:10;
    }
    header img { height:50px; cursor:pointer; }
    nav { flex:1; text-align:center; }
    nav a { margin:0 15px; text-decoration:none; font-weight:bold; color:#333; }
    nav a:hover { color:#d2691e; }
    .right-controls { display:flex; align-items:center; gap:10px; }
    button { padding:5px 12px; border:none; background:#d2691e; color:#fff; border-radius:20px; cursor:pointer; }
    button:hover { background:#a0522d; }
    .cart-btn { position:relative; padding:5px 15px; }
    .cart-count {
      position:absolute; top:-5px; right:-5px; background:red; color:#fff; font-size:12px;
      font-weight:bold; border-radius:50%; padding:2px 6px;
    }

    .products { text-align:center; max-width:1200px; margin:30px auto; }
    .product-grid {
      display:grid; grid-template-columns:repeat(auto-fit,minmax(200px,1fr));
      gap:20px;
    }
    .product {
      background:#fff; border:1px solid #eee; border-radius:10px; padding:15px;
      box-shadow:0 2px 4px rgba(0,0,0,0.1);
    }
    .product img { max-height:180px; width:100%; object-fit:cover; border-radius:8px; }
    .product h3 { font-size:16px; margin:10px 0; }
    .product p { color:#555; margin:5px 0; }
    .product button {
      background:#f4a460; border:none; color:#fff; padding:6px 14px;
      border-radius:8px; cursor:pointer; font-weight:bold;
    }
    .product button:hover { background:#cd853f; }

    /* Modal */
    #qty-modal {
      display:none; position:fixed; top:0; left:0; width:100%; height:100%;
      background:rgba(0,0,0,0.5); align-items:center; justify-content:center; z-index:999;
    }
    #qty-modal .box {
      background:#fff; padding:20px; border-radius:10px;
      text-align:center; width:300px;
    }
    #qty-modal input {
      padding:5px; width:80px; text-align:center; margin:10px 0;
    }
  </style>
</head>
<body>

<!-- ✅ Header -->
<header>
  <a href="index.php"><img src="logo.webp" alt="Happy Pet House Logo"></a>
  <nav>
    <a href="index.php">หน้าแรก</a>
    <a href="products.php">สินค้า</a>
    <a href="about.php">เกี่ยวกับเรา</a>
    <a href="contact.php">ติดต่อเรา</a>
  </nav>
  <div class="right-controls">
    <?php if(isset($_SESSION['user_id'])): ?>
      <span>👋 สวัสดี, <?= htmlspecialchars($_SESSION['username']); ?></span>
      <button onclick="window.location.href='order_history.php'">ประวัติการสั่งซื้อ</button>
      <button onclick="window.location.href='logout.php'">ออกจากระบบ</button>
    <?php else: ?>
      <button onclick="window.location.href='login.php'">เข้าสู่ระบบ</button>
      <button onclick="window.location.href='register.html'">สมัครสมาชิก</button>
    <?php endif; ?>
    <button class="cart-btn" onclick="goToCart()">🛒 ตะกร้า <span id="cart-count" class="cart-count">0</span></button>
  </div>
</header>

<!-- ✅ ผลการค้นหา -->
<div class="products">
  <h2>ผลการค้นหา: <?= htmlspecialchars($q) ?></h2>
  <div class="product-grid">
    <?php
    if ($q != '') {
        $stmt = $conn->prepare("SELECT * FROM products WHERE name LIKE ?");
        $search = "%$q%";
        $stmt->bind_param("s", $search);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($prod = $result->fetch_assoc()) {
                echo '<div class="product">';
                echo '<a href="product_detail.php?p_id='.$prod['p_id'].'">';
                echo '  <img src="'.$prod['image'].'" alt="'.$prod['name'].'">';
                echo '</a>';
                echo '<h3>';
                echo '  <a href="product_detail.php?p_id='.$prod['p_id'].'" style="text-decoration:none; color:#333;">';
                echo htmlspecialchars($prod['name']);
                echo '  </a>';
                echo '</h3>';
                echo '<p>'.number_format($prod['price'],2).' บาท</p>';
                echo '<button onclick="addToCart(\''.$prod['name'].'\', '.$prod['price'].')">เพิ่มใส่ตะกร้า</button>';
                echo '</div>';
            }
        } else {
            echo "<p>❌ ไม่พบสินค้า</p>";
        }
    }
    ?>
  </div>
</div>

<!-- ✅ Modal -->
<div id="qty-modal">
  <div class="box">
    <h3>เลือกจำนวนสินค้า</h3>
    <input type="number" id="qty-input" min="1" max="50" value="1">
    <br>
    <button onclick="confirmQty()">ตกลง</button>
    <button onclick="closeModal()" style="background:gray; margin-left:10px;">ยกเลิก</button>
  </div>
</div>

<!-- ✅ Script -->
<script>
let cart = JSON.parse(localStorage.getItem("cart")) || [];
let currentProduct = null;

function updateCartCount() {
  let totalItems = cart.reduce((sum, item) => sum + item.qty, 0);
  document.getElementById("cart-count").textContent = totalItems;
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
