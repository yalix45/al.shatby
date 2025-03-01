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
    // header("Location: dashboard.php?button=button2");
    // exit; // لا تنسى الخروج بعد تنفيذ الـ header

    
}

require_once '../../config.php';

// التحقق من وجود المعلم
if (!isset($_GET['id'])) {
    header("Location: teachers.php");
    exit;
}

$teacher_id = (int)$_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM teachers WHERE id = :id");
$stmt->execute([':id' => $teacher_id]);
$teacher = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$teacher) {
    die("المعلم غير موجود.");
}

// تحديث بيانات المعلم
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $phone = trim($_POST['phone']);
    $class = trim($_POST['class']);
    
    $stmt = $pdo->prepare("UPDATE teachers SET name = :name, phone = :phone, class = :class WHERE id = :id");
    $stmt->execute([
        ':name' => $name,
        ':phone' => $phone,
        ':class' => $class,
        ':id' => $teacher_id
    ]);

    // التأكد من عدم إرسال أي مخرجات قبل التوجيه
    header("Location: dashboard.php?button=button2");
    exit;
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


/* النموذج */
.container {
    width: 80%;
    margin: 20px auto;
    background-color: #fff;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

h2, h3 {
    color: #2f5393;
}

input[type="text"], select {
    width: 100%;
    padding: 10px;
    margin: 10px 0;
    border: 1px solid #ccc;
    border-radius: 5px;
    background-color: #f9f9f9;
}

input[type="submit"] {
    background-color: #6c00bd;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

input[type="submit"]:hover {
    background-color: #520089;
}

/* الجداول */
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

th, td {
    padding: 10px;
    text-align: left;
    border-bottom: 1px solid #ddd;
}

thead th {
    background-color: #d5d1de;
}

tbody tr:nth-child(even) {
    background-color: #f9f9f9;
}

tbody tr:hover {
    background-color: #f1f1f1;
}

/* النصوص الخاصة بالرسائل */
.message {
    color: green;
    font-size: 16px;
    margin-top: 10px;
}

/* التنسيق عند التحميل */
.loader {
    display: block;
    margin: 50px auto;
    width: 50px;
    height: 50px;
    border-radius: 50%;
    border: 6px solid #ccc;
    border-top: 6px solid #2d4d86;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.container a{
    margin-top: 0.8rem;
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
    <title>تعديل المعلم</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<div class="container">
    <h2>تعديل المعلم</h2>
    <form method="post">
        <label>اسم المعلم:</label>
        <input type="text" name="name" value="<?= htmlspecialchars($teacher['name']) ?>" required>
        <label>رقم الهاتف:</label>
        <input type="text" name="phone" value="<?= htmlspecialchars($teacher['phone']) ?>" required>
        <label>اسم الفصل:</label>
        <input type="text" name="class" value="<?= htmlspecialchars($teacher['class']) ?>" required>
        <br>
        <input type="submit" value="تعديل المعلم">
    </form>
    <a href="dashboard.php?button=button2">العودة لقائمة المعلمين</a>
</div>
</body>
</html>

        </div>
    </content>













    
</body>
</html>
