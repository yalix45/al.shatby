<?php
session_start();
require_once '../../config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $stmt = $pdo->prepare("SELECT * FROM admin WHERE username = :username");
    $stmt->execute([':username' => $username]);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($admin && password_verify($password, $admin['password'])) {
        $_SESSION['admin'] = $admin;
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "بيانات الدخول غير صحيحة.";
    }
}
?>
<!DOCTYPE html>
<html lang="ar">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>تسجيل دخول الإدارة</title>
  <link rel="stylesheet" href="../../css/login.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/2.5.0/remixicon.css">
</head>
<body>
  <div class="login">
    <img src="../../photo/login-bg.png" alt="login image" class="login__img">
    <form method="post" class="login__form">
      <h1 class="login__title">تسجيل الدخول الادارة</h1>
      <?php if(isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
      <div class="login__content">
        <div class="login__box">
          <i class="ri-user-line login__icon"></i>
          <div class="login__box-input">
            <input type="text" name="username" required class="login__input" placeholder="">
            <label class="login__label">اسم المستخدم</label>
          </div>
        </div>

        <div class="login__box">
          <i class="ri-lock-2-line login__icon"></i>
          <div class="login__box-input">
            <input type="password" name="password" required class="login__input" id="login-pass" placeholder="">
            <label class="login__label">كلمة المرور</label>
            <i class="ri-eye-off-line login__eye" id="login-eye"></i>
          </div>
        </div>
      </div>

      <div class="login__check">
        <div class="login__check-group">
          <input type="checkbox" class="login__check-input" id="remember-me">
          <label for="remember-me" class="login__check-label">تذكرني</label>
        </div>
        <a href="../all-login.html" class="login__forgot">العودة الى صفحات الدخول</a>
      </div>
      <button type="submit" class="login__button">دخول</button>
    </form>
  </div>

  <script>
    document.getElementById('login-eye').addEventListener('click', function () {
      const passwordInput = document.getElementById('login-pass');
      if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        this.classList.replace('ri-eye-off-line', 'ri-eye-line');
      } else {
        passwordInput.type = 'password';
        this.classList.replace('ri-eye-line', 'ri-eye-off-line');
      }
    });
  </script>
</body>
</html>
