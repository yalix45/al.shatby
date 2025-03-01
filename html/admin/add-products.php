<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit;
}
require_once '../../config.php';

// إضافة منتج جديد مع صورة
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_product'])) {
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $price = (int)$_POST['price'];
    $stock = (int)$_POST['stock'];
    $is_visible = isset($_POST['is_visible']) ? 1 : 0;
    $image = 'default.png'; // في حال لم يتم رفع صورة

    // معالجة رفع الصورة
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $allowed = array('jpg', 'jpeg', 'png', 'gif');
        $fileName = $_FILES['image']['name'];
        $fileTmp = $_FILES['image']['tmp_name'];
        $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        if (in_array($ext, $allowed)) {
            // إنشاء اسم فريد للصورة
            $newFileName = uniqid() . '.' . $ext;
            $uploadDir = '../../uploads/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            if (move_uploaded_file($fileTmp, $uploadDir . $newFileName)) {
                $image = $newFileName;
            }
        }
    }
    
    $stmt = $pdo->prepare("INSERT INTO products (name, description, price, stock, is_visible, image) VALUES (:name, :description, :price, :stock, :is_visible, :image)");
    $stmt->execute([
        ':name' => $name,
        ':description' => $description,
        ':price' => $price,
        ':stock' => $stock,
        ':is_visible' => $is_visible,
        ':image' => $image
    ]);
    $message = "تم إضافة المنتج.";

            // التأكد من عدم وجود أي مخرجات قبل هذا السطر
            header("Location: dashboard.php?button=button4");
            exit; // لا تنسى الخروج بعد تنفيذ الـ header
}

// استرجاع المنتجات
$stmt = $pdo->query("SELECT * FROM products");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
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


<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>إدارة المنتجات</title>
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
        input[type="text"], input[type="number"], input[type="submit"], textarea {
            width: 90%;
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
        h2{
            margin-bottom: 2rem;
        }
        form{
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 1rem;
        }
        .in-add{
            display: flex;
            flex-direction: row;
            align-items: center;
            justify-content: center;
            width: 100%;
            gap: 1rem;
        }
        .in-add label{
            width: 20%;
        }
        .in-add input, .in-add textarea{
            width: 60%;
        }

        .in-add input[type="checkbox"] {
    appearance: none;
    width: 50px;
    height: 25px;
    background: #ccc;
    border-radius: 25px;
    position: relative;
    cursor: pointer;
    transition: 0.3s;
    outline: none;
}

.in-add input[type="checkbox"]::before {
    content: "";
    position: absolute;
    width: 20px;
    height: 20px;
    background: white;
    border-radius: 50%;
    top: 50%;
    left: 3px;
    transform: translateY(-50%);
    transition: 0.3s;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
}

.in-add input[type="checkbox"]:checked {
    background: #1abc9c;
    box-shadow: 0 0 10px rgba(26, 188, 156, 0.5);
}

.in-add input[type="checkbox"]:checked::before {
    left: calc(100% - 23px);
}




input[type="file"] {
    display: none;
}

.custom-file-upload {
    display: inline-block;
    padding: 10px 20px;
    font-size: 16px;
    font-weight: 500;
    color: #fff;
    background: #007bff;
    border-radius: 8px;
    cursor: pointer;
    transition: 0.3s;
    border: 2px solid transparent;
}

.custom-file-upload:hover {
    background: #0056b3;
}

.custom-file-upload:active {
    transform: scale(0.96);
}


.sh-up-btn{
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    margin-top: 2rem;
}


    </style>
</head>
<body>
<div class="container">
    <!-- <h2>إدارة المنتجات</h2> -->
    <?php if(isset($message)) echo "<p style='color:green;'>$message</p>"; ?>
    <form method="post" enctype="multipart/form-data">
        <h2>إضافة منتج جديد</h2>
        <div class="in-add">
            <label>اسم المنتج:</label>
            <input type="text" name="name" required>
        </div>

        <div class="in-add">
            <label>الوصف:</label>
            <textarea name="description" required></textarea>
        </div>

        <div class="in-add">
            <label>السعر (نقاط):</label>
            <input type="number" name="price" required>
        </div>

        <div class="in-add">
            <label>المخزون:</label>
            <input type="number" name="stock" required>
        </div>


        <div class="sh-up-btn">
            <div class="in-add">
                <label>إظهار المنتج:</label>
                <input type="checkbox" name="is_visible" checked>
            </div>


            <br>
            <div class="in-add">
                <input type="file" name="image" id="image" accept="image/*">
                <label style="width: 60%;" for="image" class="custom-file-upload">📷 اختر صورة</label>
            </div>
        </div>



        <br>
        <input type="submit" name="add_product" value="إضافة المنتج">
    </form>

    <h3>قائمة المنتجات</h3>
    <div class="table__body">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>الاسم</th>
                    <th>الصورة</th>
                    <th>السعر</th>
                    <th>المخزون</th>
                    <th>الحالة</th>
                    <th>العمليات</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($products)): ?>
                    <?php foreach ($products as $prod): ?>
                        <tr>
                            <td><?= htmlspecialchars($prod['id']) ?></td>
                            <td><?= htmlspecialchars($prod['name']) ?></td>
                            <td>
                                <img src="../../uploads/<?= htmlspecialchars($prod['image']) ?>" alt="<?= htmlspecialchars($prod['name']) ?>" width="50">
                            </td>
                            <td><?= htmlspecialchars($prod['price']) ?></td>
                            <td><?= htmlspecialchars($prod['stock']) ?></td>
                            <td><?= $prod['is_visible'] ? 'ظاهر' : 'مخفي' ?></td>
                            <td>
                                <a href="edit_product.php?id=<?= $prod['id'] ?>">تعديل</a> | 
                                <a href="delete_product.php?id=<?= $prod['id'] ?>" onclick="return confirm('هل أنت متأكد؟')">حذف</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="7">لا توجد منتجات متاحة</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>

        </div>
    </content>













    
</body>
</html>
