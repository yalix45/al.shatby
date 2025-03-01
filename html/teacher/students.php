<?php
session_start();
require_once '../../config.php';

// التحقق من تسجيل دخول المعلم
if (!isset($_SESSION['teacher_id'])) {
    header("Location: teacher_login.php");
    exit;
}

$teacher_id = $_SESSION['teacher_id']; // الحصول على معرف المعلم من الجلسة

// استرجاع الطلاب المرتبطين بالمعلم فقط
$stmt = $pdo->prepare("SELECT * FROM students WHERE teacher_id = :teacher_id");
$stmt->execute([':teacher_id' => $teacher_id]);
$students = $stmt->fetchAll(PDO::FETCH_ASSOC);

// حساب عدد الطلاب
$total_students = count($students);
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>إدارة طلابك</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
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



        .container {
            width: 90%;
            margin: auto;
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        thead th {
            background-color: #6c00bd;
            color: white;
            padding: 12px;
        }
        th, td {
            padding: 12px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }
        tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tbody tr:hover {
            background-color: #f1f1f1;
        }
        .action-btn {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 5px;
            text-decoration: none;
            color: white;
            margin: 2px;
        }
        .edit-btn {
            background-color: #28a745;
        }
        .delete-btn {
            background-color: #dc3545;
        }
        .points-btn {
            background-color: #007bff;
        }
        .edit-btn:hover {
            background-color: #218838;
        }
        .delete-btn:hover {
            background-color: #c82333;
        }
        .points-btn:hover {
            background-color: #0056b3;
        }
        .fa-trash, .fa-pen-fancy, .fa-coins {
            margin-left: 5px;
        }
    </style>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer">

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

<div class="container">
    <h2>إدارة طلابك</h2>
    <h3>عدد الطلاب: <?= $total_students ?></h3>

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
            <?php foreach($students as $s): ?>
            <tr>
                <td><?= $s['id'] ?></td>
                <td><?= htmlspecialchars($s['name']) ?></td>
                <td><?= $s['phone'] ?></td>
                <td><?= $s['points'] ?></td>
                <td>
                    <!-- زر تعديل الطالب -->
                    <a href="edit_student.php?id=<?= $s['id'] ?>" class="action-btn edit-btn">
                        تعديل <i class="fa-solid fa-pen-fancy"></i>
                    </a>

                    <!-- زر حذف الطالب -->
                    <a href="delete_student.php?id=<?= $s['id'] ?>" class="action-btn delete-btn" onclick="return confirm('هل أنت متأكد من حذف الطالب؟')">
                        حذف <i class="fa-solid fa-trash"></i>
                    </a>

                    <!-- زر إضافة النقاط -->
                    <a href="add_points.php?id=<?= $s['id'] ?>" class="action-btn points-btn">
                        إضافة نقاط <i class="fa-solid fa-coins"></i>
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>
