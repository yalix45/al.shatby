<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
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
                    <div class="dow-page"><button class="tab-button" id="btn-dashboard" onclick="loadPage('btn-dashboard.php', this)"><i class="fa-solid fa-house-user"></i> <p>لوحة التحكم</p></button></div>
                    <div class="dow-page"><button class="tab-button" id="button2" onclick="loadPage('teachers.php', this)"><i class="fa-solid fa-chalkboard-user"></i> <p>المعلمون</p></button></div>
                    <div class="dow-page"><button class="tab-button" id="button3" onclick="loadPage('students.php', this)"><i class="fa-solid fa-users"></i> <p>الطلاب</p></button></div>
                    <div class="dow-page"><button class="tab-button" id="button4" onclick="loadPage('products.php', this)"><i class="fa-solid fa-shop"></i> <p>المتجر</p></button></div>
                    <div class="dow-page"><button class="tab-button" id="button5" onclick="loadPage('orders.php', this)"><i class="fa-solid fa-boxes-stacked"></i> <p>الطلبات</p></button></div>
                    <div class="dow-page"><button class="tab-button" id="button6" onclick="loadPage('other.php', this)"><i class="fa-solid fa-database"></i> <p>أخرى</p></button></div>
                </nav>
                
            </div>
            <script>
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



        // عند تحميل الصفحة، تحقق من معلمة URL "button" لتحديد الزر
        window.onload = function() {
            const urlParams = new URLSearchParams(window.location.search);
            const buttonId = urlParams.get('button'); // نأخذ قيمة معلمة "button"
            
            if (buttonId) {
                const button = document.getElementById(buttonId); // العثور على الزر باستخدام id
                if (button) {
                    button.click(); // الضغط على الزر تلقائيًا
                }
            }
        }

            </script>
        </header>
        <div class="content" id="main-content">
            <!-- سيتم تحميل الصفحات هنا -->
        </div>
    </content>













    
</body>
</html>
