<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= $categoryName ?> | Happy Pet House</title>
  <style>
    body { margin:0; font-family:'Prompt',sans-serif; background:#fffaf6; color:#333; }
    header { background:#fff; padding:15px 30px; display:flex; align-items:center; justify-content:space-between; border-bottom:1px solid #eee; position:sticky; top:0; z-index:10; }
    header img { height:50px; cursor:pointer; }
    nav a { margin:0 15px; text-decoration:none; font-weight:bold; color:#333; }
    nav a:hover { color:#d2691e; }
    .right-controls { display:flex; align-items:center; gap:10px; }
    .cart-btn { padding:5px 12px; border:none; background:#d2691e; color:#fff; border-radius:20px; cursor:pointer; position:relative; }
    .cart-btn:hover { background:#a0522d; }
    .cart-count { position:absolute; top:-5px; right:-5px; background:red; color:#fff; font-size:12px; font-weight:bold; border-radius:50%; padding:2px 6px; }
    .banner { height:200px; background:#eee; display:flex; align-items:center; justify-content:center; margin-bottom:20px; }
    .products { max-width:1200px; margin:0 auto; padding:20px; }
    .products h2 { text-align:center; margin-bottom:25px; }
    .product-grid { display:grid; grid-template-columns:repeat(auto-fit, minmax(220px, 1fr)); gap:20px; }
    .product { border:1px solid #eee; border-radius:10px; padding:15px; text-align:center; background:#fff; transition:transform 0.2s; }
    .product:hover { transform:scale(1.02); box-shadow:0 4px 12px rgba(0,0,0,0.1); }
    .product img { width:100%; height:180px; object-fit:cover; border-radius:8px; }
    .product h3 { margin:10px 0 5px; }
    .price { color:#d2691e; font-weight:bold; margin-bottom:10px; }
    .product button { background:#f4a460; border-radius:8px; padding:6px 14px; border:none; cursor:pointer; color:#fff; font-weight:bold; }
    .product button:hover { background:#cd853f; }
    footer { background:#fff; padding:30px; margin-top:50px; text-align:center; border-top:1px solid #eee; }
  </style>
</head>
<body>
  <!-- Header -->
  <header>
    <a href="index.php"><img src="logo.webp" alt="Happy Pet House Logo"></a>
    <nav>
      <a href="index.php">หน้าแรก</a>
      <a href="products.php">สินค้า</a>
      <a href="contact.php">ติดต่อเรา</a>
      <a href="about.php">เกี่ยวกับเรา</a>
    </nav>
    <div class="right-controls">
      <button class="cart-btn" onclick="goToCart()">🛒 ตะกร้า <span id="cart-count" class="cart-count">0</span></button>
    </div>
  </header>

  <!-- Banner -->
  <div class="banner">
    <h2><?= $categoryName ?></h2>
  </div>

  <!-- Products Section -->
  <section class="products">
    <h2>สินค้าในหมวด <?= $categoryName ?></h2>
    <div class="product-grid">
      <?php while($row = $query->fetch_assoc()): ?>
        <div class="product">
          <img src="<?= $row['image']; ?>" alt="<?= $row['name']; ?>">
          <h3><?= $row['name']; ?></h3>
          <p class="price">฿<?= number_format($row['price'],2); ?></p>
          <button onclick="addToCart('<?= $row['name']; ?>', <?= $row['price']; ?>)">เพิ่มใส่ตะกร้า</button>
        </div>
      <?php endwhile; ?>
    </div>
  </section>

  <!-- Footer -->
  <footer>
    <p>Happy Pet House © 2025 | สินค้าคุณภาพเพื่อสัตว์เลี้ยงที่คุณรัก</p>
  </footer>

  <script>
    let cart = JSON.parse(localStorage.getItem("cart")) || [];
    function updateCartCount(){ document.getElementById("cart-count").textContent = cart.length; }
    function addToCart(name, price){
      cart.push({name, price});
      localStorage.setItem("cart", JSON.stringify(cart));
      updateCartCount();
      alert(name+" ถูกเพิ่มไปยังตะกร้าแล้ว!");
    }
    function goToCart(){ window.location.href = "cart.html"; }
    updateCartCount();
  </script>
</body>
</html>
