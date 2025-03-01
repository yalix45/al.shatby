<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit;
}

require_once '../../config.php';

// إضافة طالب جديد
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_student'])) {
    $name = trim($_POST['name']);
    $phone = trim($_POST['phone']);
    $teacher_id = (int)$_POST['teacher_id'];
    
    $stmt = $pdo->prepare("INSERT INTO students (name, phone, teacher_id) VALUES (:name, :phone, :teacher_id)");
    $stmt->execute([
        ':name' => $name,
        ':phone' => $phone,
        ':teacher_id' => $teacher_id
    ]);
    $message = "تم إضافة الطالب.";
        // التأكد من عدم وجود أي مخرجات قبل هذا السطر
        header("Location: dashboard.php?button=button3");
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
        </header>
        <div class="content" id="main-content">

        <?php

require_once '../../config.php';

// استرجاع الطلاب مع معلومات المعلم
$stmt = $pdo->query("SELECT s.*, t.name AS teacher_name FROM students s LEFT JOIN teachers t ON s.teacher_id = t.id");
$students = $stmt->fetchAll(PDO::FETCH_ASSOC);

// استرجاع قائمة المعلمين للربط عند الإضافة
$stmt = $pdo->query("SELECT * FROM teachers");
$teachers = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>إدارة الطلاب</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
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
            margin-top: 1rem;
            padding: 8px;
            margin: 5px 0;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        input[type="submit"] {
            background-color: #6c00bd;
            color: white;
            cursor: pointer;
            margin-bottom: 1rem;
        }
        input[type="submit"]:hover {
            background-color: #520089;
        }










        select {
    width: 75%;
    padding: 10px;
    font-size: 16px;
    border: 2px solid #ccc;
    border-radius: 30px;
    background-color: #f9f9f9;
    cursor: pointer;
    transition: 0.3s;
    margin-top: 1rem;
    margin-bottom: 1rem;
}

select:hover {
    border-color: #6c00bd;
}

select:focus {
    border-color: #6c00bd;
    box-shadow: 0 0 5px rgba(108, 0, 189, 0.5);
}










    </style>
</head>
<body>
<div class="container">
    <!-- <h2>إدارة الطلاب</h2> -->
    <?php if(isset($message)) echo "<p style='color:green;'>$message</p>"; ?>
    <form method="post">
        <h3 style="margin-bottom: 1rem;">إضافة طالب جديد</h3>
        <label>اسم الطالب:</label>
        <input type="text" name="name" required>
        <label>رقم الهاتف:</label>
        <input type="text" name="phone" required>
        <!-- <label>اختر المعلم:</label> -->
        <select name="teacher_id" required>
            <option value="">-- اختر المعلم --</option>
            <?php foreach($teachers as $t): ?>
                <option value="<?= $t['id'] ?>"><?= htmlspecialchars($t['name']) . " - " . $t['class'] ?></option>
            <?php endforeach; ?>
        </select>
        <br>
        <input type="submit" name="add_student" value="إضافة الطالب">
    </form>

    <h3>قائمة الطلاب</h3>
    <table>
        <tr style="background: #d5d1de;">
            <th>ID</th>
            <th>الاسم</th>
            <th>رقم الهاتف</th>
            <th>المعلم</th>
            <th>النقاط</th>
            <th>العمليات</th>
        </tr>
        <?php foreach($students as $s): ?>
        <tr>
            <td><?= $s['id'] ?></td>
            <td><?= htmlspecialchars($s['name']) ?></td>
            <td><?= $s['phone'] ?></td>
            <td><?= htmlspecialchars($s['teacher_name']) ?></td>
            <td><?= $s['points'] ?></td>
            <td>
                <a href="edit_student.php?id=<?= $s['id'] ?>">تعديل</a> | 
                <a href="delete_student.php?id=<?= $s['id'] ?>" onclick="return confirm('هل أنت متأكد؟')">حذف</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>
</body>
</html>

        </div>
    </content>













    
</body>
</html>
