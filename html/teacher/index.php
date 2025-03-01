<?php
session_start();
if (!isset($_SESSION['teacher'])) {
    header("Location: teacher_login.php");
    exit;
}

require_once '../../config.php';

$teacher = $_SESSION['teacher'];
$teacher_id = $teacher['id'];

// جلب الطلاب المرتبطين بالمعلم الحالي
$stmt = $pdo->prepare("SELECT * FROM students WHERE teacher_id = :teacher_id");
$stmt->execute(['teacher_id' => $teacher_id]);
$students = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>لوحة تحكم المعلم</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer">

    <style>
        @import url("https://fonts.googleapis.com/css2?family=Rubik:wght@300..900&display=swap");
@import url('https://fonts.googleapis.com/css2?family=Zain:wght@200;300;400;700;800;900&display=swap');


* {
    font-family: "Rubik", sans-serif;
    box-sizing: border-box;
    background-repeat: no-repeat;
    background-size: cover;
    margin: 0;
}
        /* تنسيق عام */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            direction: rtl;
            text-align: center;
        }




/* navbar */
.navbar{
    display: flex;
    align-content: center;
    justify-content: space-between;
    width: 100vw;
    height: 9vh;
    padding: 0.6rem;
    box-shadow: 1px 9px 11px rgba(0, 0, 0, 0.1);
    border-bottom: solid 1px #5ba3ac;
}
.icon-nav{
    display: flex;
    align-items: center;
    gap: 3vw;
    justify-content: space-between;
    width: 100%;
}
.icon-nav .icon-btn{
    display: flex;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 2vw;
}
.icon-nav .icon-btn i{
    font-size: 1.2rem;
}
.icon-nav .icon-btn-order a{
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: row-reverse;
    text-decoration: none;
    background: #2f5393;
    padding: 0.7rem;
    border-radius: 10px;
    gap: 5px;
}
.icon-nav .icon-btn-order i{
    font-size: 1.2rem;
}
.icon-nav .icon-btn-order p{
    font-size: 1.5vw;
    color: #fff;
}
.navbar .nav-img{
    display: flex;
    flex-direction: row-reverse;
    align-items: center;
    gap: 2rem;
}






        header {
            background-color: #6c00bd;
            color: white;
            padding: 15px 0;
            font-size: 24px;
        }

        .container {
            width: 90%;
            margin: auto;
            padding: 20px;
            background: white;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            margin-top: 20px;
        }

        nav {
            margin-bottom: 20px;
        }

        nav a {
            display: inline-block;
            background-color: #6c00bd;
            color: white;
            padding: 10px 15px;
            border-radius: 5px;
            text-decoration: none;
            margin: 5px;
            transition: 0.3s;
        }

        nav a:hover {
            background-color: #520089;
        }

        h2 {
            color: #333;
        }

        /* تنسيق الجدول */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            background: white;
            border-radius: 10px;
            overflow: hidden;
        }

        thead th {
            background-color: #6c00bd;
            color: white;
            padding: 12px;
        }

        tbody tr {
            border-bottom: 1px solid #ddd;
        }

        tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tbody tr:hover {
            background-color: #f1f1f1;
            transition: 0.3s;
        }

        th, td {
            padding: 12px;
            text-align: center;
        }

        /* تنسيق الأزرار */
        .action-btn {
            display: inline-block;
            padding: 8px 12px;
            border-radius: 5px;
            text-decoration: none;
            color: white;
            font-size: 14px;
            margin: 2px;
            transition: 0.3s;
        }

        .edit-btn {
            background: linear-gradient(45deg, #28a745, #218838);
        }

        .delete-btn {
            background: linear-gradient(45deg, #dc3545, #c82333);
        }

        .points-btn {
            background: linear-gradient(45deg, #007bff, #0056b3);
        }

        .edit-btn:hover {
            background: linear-gradient(45deg, #218838, #1e7e34);
        }

        .delete-btn:hover {
            background: linear-gradient(45deg, #c82333, #b21f2d);
        }

        .points-btn:hover {
            background: linear-gradient(45deg, #0056b3, #004494);
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
            <div style="    display: flex; flex-direction: row; gap: 2rem;">

                <div class="icon-btn">
                    <a href="index.php"> <i class="fa-solid fa-house" style="color: #2d4d86;"></i></a>
                    <a href="../../index.php"> <i class="fa-solid fa-store" style="color: #2f5393;"></i></i></a>
                    <a href="teacher_logout.php"> <i class="fa-solid fa-power-off" style="color: #2f5393;"></i></a>
                </div>
            </div>
        </div>
    </navbar>

<header>
    <h1>مرحبا، <?= htmlspecialchars($teacher['name']) ?></h1>
</header>
<div class="container">
    <nav>
        <a href="add_student.php">إضافة طالب</a>
        <a href="add_points.php">إضافة نقاط</a>
        <a href="teacher_logout.php">تسجيل خروج</a>
    </nav>

    <h2>قائمة طلابك</h2>
    <?php if (count($students) > 0): ?>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>الاسم</th>
                <th>رقم الهاتف</th>
                <th>النقاط</th>
                <th>العمليات</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($students as $s): ?>
            <tr>
                <td><?= $s['id'] ?></td>
                <td><?= htmlspecialchars($s['name']) ?></td>
                <td><?= $s['phone'] ?></td>
                <td><?= $s['points'] ?></td>
                <td>
                    <a href="edit_student.php?id=<?= $s['id'] ?>" class="action-btn edit-btn">تعديل</a>
                    <a href="delete_student.php?id=<?= $s['id'] ?>" class="action-btn delete-btn" onclick="return confirm('هل أنت متأكد من حذف الطالب؟')">حذف</a>
                    <a href="add_points.php?id=<?= $s['id'] ?>" class="action-btn points-btn">إضافة نقاط</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php else: ?>
        <p>لا يوجد طلاب حتى الآن.</p>
    <?php endif; ?>
</div>
</body>
</html>
