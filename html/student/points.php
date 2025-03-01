<?php
require_once '../../config.php';
$message = "";
$student_info = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $phone = trim($_POST['phone']);
    $stmt = $pdo->prepare("SELECT * FROM students WHERE phone = :phone");
    $stmt->execute([':phone' => $phone]);
    $student_info = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$student_info) {
        $message = "الطالب غير موجود.";
    }
}
?>
<!DOCTYPE html>
<html lang="ar">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>استعلام عن النقاط</title>
  <link rel="stylesheet" href="../css/style.css">
  <style>
           @import url("https://fonts.googleapis.com/css2?family=Poppins:wght@400;500&display=swap");
@import url("https://fonts.googleapis.com/css2?family=Rubik:wght@300..900&display=swap");
@import url('https://fonts.googleapis.com/css2?family=Zain:wght@200;300;400;700;800;900&display=swap');

    :root {
      --white-color: hsl(0, 0%, 100%);
      --black-color: hsl(0, 0% , 0%);
      --body-font:   "Rubik", sans-serif; 
      --h1-font-size: 1.75rem;
      --normal-font-size: 1rem;
      --small-font-size: .813rem;
       --font-medium: 500; 
       }

    * {
      box-sizing: border-box;
      padding: 0;
      margin: 0;
    }

    body,
    input,
    button {
      font-size: var(--normal-font-size);
      font-family: var(--body-font);
    }

    body {
      color: var(--white-color);
    }

    input,
    button {
      border: none;
      outline: none;
    }

    .login {
      position: relative;
      height: 100vh;
      display: grid;
      align-items: center;
    }

    .login__img {
      position: absolute;
      width: 100%;
      height: 100%;
      object-fit: cover;
      object-position: center;
    }

    .login__form {
      position: relative;
      background-color: hsla(0, 0%, 10%, 0.5);
      border: 2px solid var(--white-color);
      margin-inline: 1.5rem;
      padding: 2.5rem 1.5rem;
      border-radius: 1rem;
      backdrop-filter: blur(8px);
    }

    .login__title {
      text-align: center;
      font-size: var(--h1-font-size);
      font-weight: var(--font-medium);
      margin-bottom: 2rem;
    }

    .login__content {
      row-gap: 1.75rem;
      margin-bottom: 1.5rem;
    }

    .login__box {
      display: grid;
      grid-template-columns: max-content 1fr;
      align-items: center;
      column-gap: 0.75rem;
      border-bottom: 2px solid var(--white-color);
    }

    .login__input {
      width: 100%;
      padding-block: 0.8rem;
      background: none;
      color: var(--white-color);
      position: relative;
      z-index: 1;
    }

    .login__label {
      position: absolute;
      left: 0;
      top: 13px;
      font-weight: var(--font-medium);
      transition: top 0.3s, font-size 0.3s;
    }

    .login__input:focus + .login__label {
      top: -12px;
      font-size: var(--small-font-size);
    }

    .login__check {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 1.5rem;
    }

    .login__check-label,
    .login__forgot,
    .login__register {
      font-size: var(--small-font-size);
    }

    .login__button {
      width: 100%;
      padding: 1rem;
      border-radius: 0.5rem;
      background-color: var(--white-color);
      font-weight: var(--font-medium);
      cursor: pointer;
      margin-bottom: 2rem;
    }

    .login__register {
      text-align: center;
    }

    .login__register a {
      color: var(--white-color);
      font-weight: var(--font-medium);
    }

    .login__register a:hover {
      text-decoration: underline;
    }

    @media screen and (min-width: 576px) {
      .login {
        justify-content: center;
      }

      .login__form {
        width: 432px;
        padding: 4rem 3rem 3.5rem;
        border-radius: 1.5rem;
      }

      .login__title {
        font-size: 2rem;
      }
    }


    .login__input::placeholder {
    color: #fff;  /* هنا يمكنك تغيير اللون إلى أي لون تريده */
    opacity: 1;  /* لجعل اللون ثابتًا ولا يتغير */
}


.in-te{
                color: #000;
                font-family: "Rubik", sans-serif;
            }



            .login__forgot {
            color: var(--white-color);
            text-align: center;
            margin: 2rem 4.5rem 0rem 4.5rem;
            }
            .login__forgot:hover {
              text-decoration: underline;
            }

            .login__box{
                margin-bottom: 2rem;
            }


  </style>
</head>
<body>

<div class="login">
  <img src="../../photo/login-bg.png" alt="login image" class="login__img">
  <form action="" method="post" class="login__form">
    <h1 class="login__title">استعلام عن النقاط</h1>

    <?php if ($message) echo "<p style='color:red;'>$message</p>"; ?>

    <div class="login__content">
      <div class="login__box">
        <i class="bx bx-phone"></i>
        <div class="login__box-input">
          <input type="text" name="phone" required class="login__input" placeholder="أدخل رقم الهاتف">
          <!-- <label for="" class="login__label">رقم الهاتف</label> -->
        </div>
      </div>
      <a href="../all-login.html" class="login__forgot">العودة الى صفحات الدخول</a>

    </div>

    <button type="submit" class="login__button">استعلام</button>

    <?php if ($student_info): ?>
      <div class="login__content">
        <h3>بيانات الطالب</h3>
        <p>الاسم: <?= htmlspecialchars($student_info['name']) ?></p>
        <p>النقاط: <?= $student_info['points'] ?></p>
      </div>
    <?php endif; ?>
  </form>
</div>

</body>
</html>
