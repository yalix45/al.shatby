<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit;
}
require_once '../../config.php';
?>




<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- icon -->
    <link rel="icon" type="image/png" href="../../photo/icon-for-header.png">
    <!-- css -->
    <link rel="stylesheet" href="../../css/btn-dashboard.css">
    <!-- labrary -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"> -->
    <title></title>
    <style>
        .info-number{
            display: flex;
            width: 100%;
            align-items: center;
            justify-content: center;
            flex-wrap: wrap;
            margin-top: 2rem;
            /* gap: 2%; */
        }
        .info-nuber-ch{
            width: calc(90% / 4);
            background-color:rgba(110, 198, 209, 0.3);
            border-radius: 10px;
            height: 3rem;
            margin-left:0.5rem ;
            margin-right: 0.5rem;
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            align-items: center;
            align-content: center;
        }
        .info-num-tea{
            background-color: #007bff;
            color: #fff;
            font-size: 1.2rem;
        }
        .info-num-stu{
            background-color: #28a745;
            color: #fff;
            font-size: 1.2rem;
        }
        .info-num-pro{
            background-color: #fd7e14;
            color: #fff;
            font-size: 1.2rem;
        }
        .info-num-ord{
            background-color: #dc3545;
            color: #fff;
            font-size: 1.2rem;
        }
        .btn-content a{
            margin: 0;
            padding: 0;
            width: calc((75vw / 3) - 4vw);
        }
    </style>
</head>
<body>



<div class="info-sql">

</div>

<div class="info-number">

    <div class="info-nuber-ch info-num-tea">
        <?php
        $stmt = $pdo->query("SELECT COUNT(*) AS count FROM teachers");
        $teachers_count = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
        ?>
        <p><strong><i class="fa-solid fa-chalkboard-user"></i> | عدد المعلمين  </strong><?= $teachers_count ?></p>

    </div>

    <div class="info-nuber-ch info-num-stu">
        <?php
        $stmt = $pdo->query("SELECT COUNT(*) AS count FROM students");
        $students_count = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
        ?>
        <p><strong><i class="fa-solid fa-user-graduate"></i> | عدد الطلاب  </strong><?= $students_count ?></p>

    </div>

    <div class="info-nuber-ch info-num-pro">
        <?php
        $stmt = $pdo->query("SELECT COUNT(*) AS count FROM products");
        $products_count = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
        ?>
        <p><strong><i class="fa-solid fa-gifts"></i> | عدد المنتجات  </strong><?= $products_count ?></p>

    </div>

    <div class="info-nuber-ch info-num-ord">
        <?php
        $stmt = $pdo->query("SELECT COUNT(*) AS count FROM orders");
        $orders_count = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
        ?>
        <p><strong><i class="fa-solid fa-boxes-stacked"></i> | عدد الطلبات  </strong><?= $orders_count ?></p>
    </div>

</div>

    <content class="btn-content">
        <a href="add-products.php"><button class="btn">
            <div class="card">
                <div class="card-img">
                    <i class="fa-solid fa-gifts" style="color: #1c656e;"></i>
                </div>
                <div class="card-text">
                    <h2>المنتجات</h2>
                    <p>انقر لاضافة المنتجات الجديدة</p>
                </div>
            </div>
        </button></a>
        <a href="add-teacher.php"><button class="btn">
            <div class="card">
                <div class="card-img">
                    <i class="fa-solid fa-user-plus" style="color: #1c656e;"></i>
                </div>
                <div class="card-text">
                    <h2>المعلمون</h2>
                    <p>انقر لاضافة المعلمين أو تعديلهم</p>
                </div>
            </div>
        </button></a>
        <a href="add-student.php"><button class="btn">
            <div class="card">
                <div class="card-img">
                    <i class="fa-solid fa-person-circle-plus" style="color: #1c656e;"></i>
                </div>
                <div class="card-text">
                    <h2>الطلاب</h2>
                    <p>انقر لاضافة الطلاب أو تعديلهم</p>
                </div>
            </div>
        </button></a>
        <a href="dashboard.php?button=button5"><button class="btn">
            <div class="card">
                <div class="card-img">
                    <i class="fa-solid fa-boxes-packing" style="color: #1c656e;"></i>
                </div>
                <div class="card-text">
                    <h2>الطلبات</h2>
                    <p>استعراض الطلبات والموافقة عليها</p>
                </div>
            </div>
        </button></a>
        <a href="dashboard.php?button=button6"><button class="btn">
            <div class="card">
                <div class="card-img">
                    <i class="fa-solid fa-database" style="color: #1c656e;"></i>
                </div>
                <div class="card-text">
                    <h2>البيانات</h2>
                    <p>انقر لاستعراض قاعدة البيانات</p>
                </div>
            </div>
        </button></a>
        <a href="../..//index.php"><button class="btn1">
            <div class="card">
                <div class="card-img">
                    <i class="fa-solid fa-shop" style="color: #1c656e;"></i>
                </div>
                <div class="card-text">
                    <h2>المتجر</h2>
                    <p>انقر لاستعراض المتجر</p>
                </div>
            </div>
        </button></a>
    </content>



</body>
</html>