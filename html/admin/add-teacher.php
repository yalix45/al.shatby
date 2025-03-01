<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit;
}

require_once '../../config.php';

// إضافة معلم جديد
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_teacher'])) {
    $name = trim($_POST['name']);
    $phone = trim($_POST['phone']);
    $class = trim($_POST['class']);
    
    $stmt = $pdo->prepare("INSERT INTO teachers (name, phone, class) VALUES (:name, :phone, :class)");
    $stmt->execute([
        ':name' => $name,
        ':phone' => $phone,
        ':class' => $class
    ]);
    $message = "تم إضافة المعلم.";

    // التأكد من عدم وجود أي مخرجات قبل هذا السطر
    header("Location: dashboard.php?button=button2");
    exit; // لا تنسى الخروج بعد تنفيذ الـ header
}


?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- icon -->
    <link rel="icon" type="image/png" href="../../photo/icon-for-header.png">
    <!-- css -->
    <link rel="stylesheet" href="../../css/dashboard.css">
    <!-- labrary -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"> -->
    <title>لوحة التحكم</title>
    <style>
        a{
            width: 100%;
            text-decoration: none;
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
                <!-- <div class="icon-btn-order">
                    <a href="#"> 
                        <i class="fa-solid fa-cart-shopping" style="color: #fff; "></i>
                        <p>استعراض الطلبات</p>
                    </a>
                </div> -->
                <div class="icon-btn">
                    <a href="dashboard.php"> <i class="fa-solid fa-house" style="color: #2d4d86;"></i></a>
                    <a href="../../index.php"> <i class="fa-solid fa-store" style="color: #2f5393;"></i></i></a>
                    <a href="admin_logout.php"> <i class="fa-solid fa-power-off" style="color: #2f5393;"></i></a>
                </div>
            </div>
        </div>
    </navbar>

    <content>
        <header>
            <div class="top-header-btn">
                <!-- القائمة الجانبية -->
                <nav class="sidebar">
                    <a href="dashboard.php?button=button-das"><div class="dow-page"><button class="tab-button" id="btn-dashboard" onclick="loadPage('btn-dashboard.php', this)"><i class="fa-solid fa-house-user"></i> <p>لوحة التحكم</p></button></div></a>
                    <a href="dashboard.php?button=button2"><div class="dow-page"><button class="tab-button" id="btn-" onclick="loadPage('teachers.php', this)"><i class="fa-solid fa-chalkboard-user"></i> <p>المعلمون</p></button></div></a>
                    <a href="dashboard.php?button=button3"><div class="dow-page"><button class="tab-button" id="btn-" onclick="loadPage('students.php', this)"><i class="fa-solid fa-users"></i> <p>الطلاب</p></button></div></a>
                    <a href="dashboard.php?button=button4"><div class="dow-page"><button class="tab-button" id="btn-" onclick="loadPage('products.php', this)"><i class="fa-solid fa-shop"></i> <p>المتجر</p></button></div></a>
                    <a href="dashboard.php?button=button5"><div class="dow-page"><button class="tab-button" id="btn-" onclick="loadPage('orders.php', this)"><i class="fa-solid fa-boxes-stacked"></i> <p>الطلبات</p></button></div></a>
                    <a href="dashboard.php?button=button6"><div class="dow-page"><button class="tab-button" id="btn-" onclick="loadPage('other.php', this)"><i class="fa-solid fa-database"></i> <p>أخرى</p></button></div></a>
                </nav>
                
            </div>
            <!-- <script>
                function loadPage(page, button) {
                    fetch(page)
                    .then(response => response.text())
                    .then(data => {
                        document.getElementById("main-content").innerHTML = data;
                        
                        // إزالة الفئة "active" من جميع الأزرار
                        document.querySelectorAll('.tab-button').forEach(btn => btn.classList.remove('active'));
                        
                        // إضافة الفئة "active" للزر الحالي
                        button.classList.add('active');
                    })
                    .catch(error => console.error("Error loading page:", error));
                }
    
                // تحميل صفحة "لوحة التحكم" تلقائيًا عند فتح الصفحة
                document.addEventListener("DOMContentLoaded", () => {
                    const dashboardButton = document.getElementById("btn-dashboard");
                    loadPage('btn-dashboard.php', dashboardButton);
                    dashboardButton.classList.add('active'); // إضافة active للزر تلقائيًا
                });
            </script> -->
        </header>
        <div class="content" id="main-content">

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>إدارة المعلمين</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        /* * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: sans-serif;
        }
        body {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #f4f4f4;
        }
        main.table {
            width: 82vw;
            height: 90vh;
            background-color: #fff5;
            backdrop-filter: blur(7px);
            box-shadow: 0 .4rem .8rem #0005;
            border-radius: .8rem;
            overflow: hidden;
            padding: 1rem;
        } */
        .table__header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: .8rem 1rem;
            background-color: #fff4;
        }
        .table__body {
            width: 95%;
            max-height: calc(89% - 1.6rem);
            background-color: #fffb;
            margin: .8rem auto;
            border-radius: .6rem;
            overflow: auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        thead th {
            background-color: #d5d1defe;
        }
        tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tbody tr:hover {
            background-color: #f1f1f1;
        }
        .status {
            padding: .4rem 0;
            border-radius: 2rem;
            text-align: center;
        }
        .container {
            width: 90%;
            margin: auto;
            text-align: center;
        }
        input[type="text"], input[type="submit"] {
            padding: 8px;
            margin: 5px 0;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        input[type="submit"] {
            background-color: #6c00bd;
            color: white;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #520089;
        }
        form{
            display: flex;
            /* flex-direction: column; */
            align-items: center;
            justify-content: center;
            gap: 1rem;
            margin-bottom: 1rem;
            margin-top: 1rem;
        }

    </style>
    <?php

// استرجاع المعلمين
$stmt = $pdo->query("SELECT * FROM teachers");
$teachers = $stmt->fetchAll(PDO::FETCH_ASSOC);

$teachers_count = count($teachers);
?>
</head>
<body>
<main class="table">
    <div class="table__header">
        <h2>إضافة معلم جديد</h2>
    </div>
    <div class="container">
        <?php if(isset($message)) echo "<p style='color:green;'>$message</p>"; ?>
        <form method="post">
            <div class="la-tea">
            <label>اسم المعلم:</label>
            <input type="text" name="name" required>
            </div>
            <div class="la-tea">
            <label>رقم الهاتف:</label>
            <input type="text" name="phone" required>
            </div>
            <div class="la-tea">
            <label>اسم الفصل:</label>
            <input type="text" name="class" required>
            </div>

            <br>
        </form>
        <input type="submit" name="add_teacher" value="إضافة المعلم">

        <!-- <h3>قائمة المعلمين</h3> -->
        <div class="table__body">
            <table>
            <?php
// استرجاع المعلمين مع عدد الطلاب
$stmt = $pdo->query("
    SELECT t.*, 
           (SELECT COUNT(*) FROM students WHERE teacher_id = t.id) AS student_count
    FROM teachers t
");
$teachers = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<thead>
    <tr>
        <th>ID</th>
        <th>الاسم</th>
        <th>رقم الهاتف</th>
        <th>الفصل</th>
        <th>عدد الطلاب</th> <!-- إضافة عمود جديد -->
        <th>العمليات</th>
    </tr>
</thead>
<tbody>
    <?php foreach($teachers as $t): ?>
    <tr>
        <td><?= $t['id'] ?></td>
        <td><?= htmlspecialchars($t['name']) ?></td>
        <td><?= $t['phone'] ?></td>
        <td><?= htmlspecialchars($t['class']) ?></td>
        <td><?= $t['student_count'] ?></td> <!-- عرض عدد الطلاب -->
        <td>
            <a href="edit_teacher.php?id=<?= $t['id'] ?>">تعديل</a> |
            <a href="delete_teacher.php?id=<?= $t['id'] ?>" onclick="return confirm('هل أنت متأكد؟')">حذف</a>
        </td>
    </tr>
    <?php endforeach; ?>
</tbody>

            </table>
        </div>
    </div>
</main>
</body>
</html>
        </div>
    </content>













    
</body>
</html>
