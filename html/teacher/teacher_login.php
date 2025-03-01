<?php
session_start();
require_once '../../config.php';

// استرجاع قائمة المعلمين
$stmt = $pdo->query("SELECT * FROM teachers");
$teachers = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $teacher_id = $_POST['teacher_id'];
    $phone = trim($_POST['phone']);

    // التحقق من رقم الهاتف ككلمة مرور
    $stmt = $pdo->prepare("SELECT * FROM teachers WHERE id = :id AND phone = :phone");
    $stmt->execute([':id' => $teacher_id, ':phone' => $phone]);
    $teacher = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($teacher) {
        $_SESSION['teacher'] = $teacher;
        header("Location: index.php");
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
    <title>تسجيل دخول المعلم</title>
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
            margin: 0;
            padding: 0;
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
            background-color: hsla(0, 0%, 10%, 0.1);
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
            margin-bottom: 2.5rem;
        }

        .login__box {
            grid-template-columns: max-content 1fr;
            align-items: center;
            column-gap: 0.75rem;
            border-bottom: 2px solid var(--white-color);
            margin-top: 1rem;
            margin-bottom: 2rem;
        }

        .login__box-input {
            position: relative;
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

        .login__button {
            width: 100%;
            padding: 1rem;
            border-radius: 0.5rem;
            background-color: var(--white-color);
            font-weight: var(--font-medium);
            cursor: pointer;
            margin-bottom: 2rem;
        }

        .login__error {
            color: red;
            font-size: 0.9rem;
            margin-top: 10px;
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








/* تخصيص مظهر القائمة */
.login__input {
    width: 100%;
    padding: 0.8rem;
    background-color:rgba(244, 244, 244, 0);
    color: #fff;
    /* border: 2px solid #ccc; */
    border-radius: 5px;
    font-size: 1rem;
    -webkit-appearance: none; /* لإخفاء المظهر الأصلي للـ select في بعض المتصفحات */
    -moz-appearance: none;
    appearance: none;
    text-align: center; /* للتأكد من أن النص يظهر من اليمين إلى اليسار */
}

/* إضافة مثل السهم للقائمة */
.login__input::after {
    content: ' ▼'; /* يضيف السهم داخل الـ select */
    font-size: 1.5rem;
    color: #fff;
    position: absolute;
    right: 10px;
    top: 10px;
    pointer-events: none;
}


.login__input::placeholder {
    color: #fff;  /* هنا يمكنك تغيير اللون إلى أي لون تريده */
    opacity: 1;  /* لجعل اللون ثابتًا ولا يتغير */
}







          .login__forgot {
            color: var(--white-color);
            text-align: center;
            margin: 2rem 4.5rem 0rem 4.5rem;
            }
            .login__forgot:hover {
              text-decoration: underline;
            }


            .in-te{
                color: #000;
                font-family: "Rubik", sans-serif;
            }



    </style>
</head>
<body>
    <div class="login">
        <img src="../../photo/login-bg.png" alt="login image" class="login__img">
        <form method="post" class="login__form">
            <h1 class="login__title">تسجيل دخول المعلم</h1>
            
            <?php if(isset($error)) echo "<p class='login__error'>$error</p>"; ?>
            
            <div class="login__content">
                <div class="login__box">
                    <div class="login__box-input">
                        <select name="teacher_id" required class="login__input">
                            <option class="in-te" value="">-- اختر المعلم --</option>
                            <?php foreach($teachers as $t): ?>
                                <option class="in-te" value="<?= $t['id'] ?>"><?= htmlspecialchars($t['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <!-- <label class="login__label">المعلم</label> -->
                    </div>
                </div>

                <div class="login__box">
                    <div class="login__box-input">
                        <input type="text" name="phone" required class="login__input" placeholder="رقم الهاتف">
                        <!-- <label class="login__label">رقم الهاتف (كلمة المرور)</label> -->
                    </div>
                </div>

                <a href="../all-login.html" class="login__forgot">العودة الى صفحات الدخول</a>

            </div>

            
            <button type="submit" class="login__button">دخول</button>
        </form>
    </div>
</body>
</html>
