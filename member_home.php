<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    // ถ้ายังไม่ได้ล็อกอิน ให้เด้งกลับไปหน้า login.php
    header("Location: login.php");
    exit;
} else {
    // ถ้าล็อกอินสำเร็จแล้ว ให้เด้งไปหน้า index.php ทันที
    header("Location: index.php");
    exit;
}
?>