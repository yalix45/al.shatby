<?php
session_start();
if (!isset($_SESSION['teacher'])) {
    header("Location: teacher_login.php");
    exit;
}
require_once '../../config.php';
$teacher = $_SESSION['teacher'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $phone = trim($_POST['phone']);
    // ربط الطالب بمعلم الجلسة
    $stmt = $pdo->prepare("INSERT INTO students (name, phone, teacher_id) VALUES (:name, :phone, :teacher_id)");
    $stmt->execute([
        ':name' => $name,
        ':phone' => $phone,
        ':teacher_id' => $teacher['id']
    ]);
    $message = "تم إضافة الطالب.";
}
?>
<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>إضافة طالب</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer">

    <style>
        @import url("https://fonts.googleapis.com/css2?family=Rubik:wght@300..900&display=swap");

        * {
            font-family: "Rubik", sans-serif;
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            background: #f8f9fa;
            color: #333;
            text-align: center;
            direction: rtl;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            padding: 20px;
        }

        /* navbar */
        .navbar {
            display: flex;
            align-content: center;
            justify-content: space-between;
            width: 100vw;
            height: 9vh;
            padding: 0.6rem;
            box-shadow: 1px 9px 11px rgba(0, 0, 0, 0.1);
            border-bottom: solid 1px #5ba3ac;
            background: white;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
        }

        .icon-nav {
            display: flex;
            align-items: center;
            gap: 3vw;
            justify-content: space-between;
            width: 100%;
        }

        .icon-nav .nav-img {
            display: flex;
            flex-direction: row-reverse;
            align-items: center;
            gap: 1rem;
        }

        .icon-nav .nav-img h3 {
            color: #2f5393;
        }

        .icon-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 2vw;
        }

        .icon-btn a i {
            font-size: 1.4rem;
            transition: 0.3s;
        }

        .icon-btn a:hover i {
            color: #1c646e;
        }

        .icon-btn-order a {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: row-reverse;
            text-decoration: none;
            background: #2f5393;
            padding: 0.7rem;
            border-radius: 10px;
            gap: 5px;
            color: white;
            font-size: 1.5vw;
        }

        .icon-btn-order a:hover {
            background: #1c646e;
        }


        .container {
    background: white;
    padding: 35px;
    border-radius: 15px;
    box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
    width: 90%;
    /* max-width: 500px; */
    margin-top: 12vh;
    display: flex;
    flex-direction: column;
    align-items: center;
    height: 90vh;
    justify-content: center;
    gap: 2rem;
}

form{
    display: flex;
    flex-direction: column;
    gap: 2rem;
}


h2 {
    margin-bottom: 20px;
    font-size: 26px;
    color: #1c646e;
}

label {
    font-size: 18px;
    margin-bottom: 5px;
    display: block;
    color: #555;
}

input[type="text"] {
    padding: 12px;
    font-size: 16px;
    border-radius: 5px;
    width: 100%;
    border: 2px solid #ddd;
    background: rgba(255, 255, 255, 0.9);
    color: #333;
    transition: 0.3s;
    margin-bottom: 15px; /* زيادة المسافة بين الحقول */
}

input[type="text"]:focus {
    border-color: #1c646e;
    outline: none;
}

input[type="submit"], a {
    width: 100%;
    text-align: center;
    padding: 14px;
    font-size: 18px;
    border-radius: 8px;
    transition: 0.3s;
    font-weight: bold;
    margin-top: 10px; /* زيادة المسافة بين الأزرار */
}

input[type="submit"] {
    background: #28a745;
    border: none;
    color: white;
}

input[type="submit"]:hover {
    background: #1c646e;
}

    

    
        .container a {
            display: block;
            margin-top: 15px;
            background: #dc3545;
            color: white;
            padding: 12px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 16px;
            font-weight: bold;
            transition: 0.3s;
        }





/* تحسين مظهر النموذج على الشاشات الصغيرة */
@media (max-width: 600px) {
    .container {
        width: 94vw; /* اجعل العرض أكبر للشاشات الصغيرة */
        padding: 25px; /* تقليل التباعد الداخلي قليلاً */
        height: 100%;
        align-items: center;
    }

    h2 {
        font-size: 22px; /* تصغير العنوان قليلاً */
    }

    input[type="text"] {
        padding: 10px; /* تقليل حجم حقول الإدخال */
        font-size: 14px; /* تصغير الخط قليلاً */
    }

    input[type="submit"], a {
        padding: 12px; /* تصغير الأزرار قليلاً */
        font-size: 16px; /* تقليل حجم الخط */
        margin-top: 8px; /* تقليل المسافة بين الأزرار */
    }
}


    </style>
</head>
<body>

<navbar class="navbar">
    <div class="icon-nav">
        <div class="nav-img">
            <h3>مـجـمـع الـشـاطـبـي</h3>
            <img src="../../photo/or-icon.png" alt="" style="width: 3.5rem;">
        </div>
        <div class="icon-btn">
            <a href="index.php"><i class="fa-solid fa-house" style="color: #2d4d86;"></i></a>
            <a href="../../index.php"><i class="fa-solid fa-store" style="color: #2f5393;"></i></a>
            <a href="teacher_logout.php"><i class="fa-solid fa-power-off" style="color: #2f5393;"></i></a>
        </div>
    </div>
</navbar>

<div class="container">


    <h2>إضافة طالب</h2>
    <?php if (isset($message)) echo "<p style='color:green;'>$message</p>"; ?>
    <form method="post">
        <label>اسم الطالب:</label>
        <input type="text" name="name" required>
        <label>رقم الهاتف:</label>
        <input type="text" name="phone" required>
        <input type="submit" value="إضافة الطالب">
    </form>
    <a  href="index.php">العودة للوحة التحكم</a>
</div>

</body>
</html>
