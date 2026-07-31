<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

require_once "db_connect.php";

$user_id = $_SESSION['user_id'];
$fullname = $_POST['fullname'];
$phone = $_POST['phone'];
$address = $_POST['address'];
$cart = json_decode($_POST['cart'], true);

$total_price = 0;

// ✅ ตรวจสอบสต็อกก่อนทำรายการ
foreach ($cart as $item) {
    $name = $item['name'];
    $qty = $item['qty'];

    $sql_check = "SELECT stock FROM products WHERE name = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("s", $name);
    $stmt_check->execute();
    $result = $stmt_check->get_result();
    $row = $result->fetch_assoc();

    if (!$row || $row['stock'] < $qty) {
        echo "<script>
            alert('สินค้าชื่อ $name มีสต็อกไม่พอ (คงเหลือ {$row['stock']} ชิ้น)');
            window.location.href = 'cart.html';
        </script>";
        exit();
    }

    $total_price += $item['price'] * $item['qty'];
}

// 1. บันทึกลงตาราง orders
$sql = "INSERT INTO orders (user_id, fullname, phone, address, total_price, created_at)
        VALUES (?, ?, ?, ?, ?, NOW())";
$stmt = $conn->prepare($sql);
$stmt->bind_param("isssd", $user_id, $fullname, $phone, $address, $total_price);
$stmt->execute();
$order_id = $stmt->insert_id;

// 2. บันทึกสินค้าลง order_items + ตัดสต็อค
$sql_item = "INSERT INTO order_items (order_id, product_name, price, quantity) VALUES (?, ?, ?, ?)";
$stmt_item = $conn->prepare($sql_item);

$sql_stock = "UPDATE products SET stock = stock - ? WHERE name = ?";
$stmt_stock = $conn->prepare($sql_stock);

foreach ($cart as $item) {
    $name = $item['name'];
    $price = $item['price'];
    $qty = $item['qty'];

    // insert order_items
    $stmt_item->bind_param("isdi", $order_id, $name, $price, $qty);
    $stmt_item->execute();

    // update stock
    $stmt_stock->bind_param("is", $qty, $name);
    $stmt_stock->execute();
}

// 3. ลบ cart
echo "<script>
localStorage.removeItem('cart');
window.location.href = 'order_success.php?order_id=$order_id';
</script>";
?>
