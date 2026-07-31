<?php
session_start();
?>

<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <title>เข้าสู่ระบบ - Happy Pet House</title>
  <style>
    body {
      margin: 0;
      font-family: 'Prompt', sans-serif;
      background-color: #fffaf6;
      color: #333;
      padding: 20px;
    }
    h1 {
      text-align: center;
      margin-bottom: 20px;
      color: #d2691e;
    }
    form {
      max-width: 400px;
      margin: 0 auto;
      background: #fff;
      padding: 25px;
      border-radius: 15px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    label {
      display: block;
      margin: 8px 0 4px;
    }
    input {
      width: 100%;
      padding: 8px;
      border: 1px solid #ccc;
      border-radius: 8px;
      margin-bottom: 10px;
      font-family: inherit;
    }
    button {
      display: block;
      width: 100%;
      padding: 12px;
      font-size: 16px;
      font-weight: bold;
      background: #d2691e;
      color: #fff;
      border: none;
      border-radius: 10px;
      cursor: pointer;
      margin-top: 10px;
    }
    button:hover {
      background: #a0522d;
    }
    .error {
      color: red;
      text-align: center;
      margin-bottom: 10px;
    }
  </style>
</head>
<body>

  <h1>เข้าสู่ระบบ</h1>

  <?php if(isset($_SESSION['error'])): ?>
    <div class="error"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
  <?php endif; ?>

  <form action="login_action.php" method="POST">
    <label>ชื่อผู้ใช้ (Username)</label>
    <input type="text" name="username" required>

    <label>รหัสผ่าน</label>
    <input type="password" name="password" required>

    <button type="submit">เข้าสู่ระบบ</button>
  </form>

</body>
</html>
