<?php
session_start();

// ลบ session ทั้งหมด
session_unset();
session_destroy();
?>

<script>
// ล้างตะกร้าใน localStorage ด้วย
localStorage.removeItem("cart");

// กลับไปหน้าแรก
window.location.href = "index.php";
</script>
