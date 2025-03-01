<?php
require_once '../../config.php';

if (!isset($_GET['id'])) {
    header("Location: ../../index.php");
    exit;
}

$product_id = (int)$_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = :id AND is_visible = 1");
$stmt->execute([':id' => $product_id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$product) {
    die("المنتج غير موجود.");
}

$message = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_phone = trim($_POST['student_phone']);
    // التحقق من وجود الطالب
    $stmt = $pdo->prepare("SELECT * FROM students WHERE phone = :phone");
    $stmt->execute([':phone' => $student_phone]);
    $student = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$student) {
        $message = "الطالب غير موجود.";
    } elseif ($student['points'] < $product['price']) {
        $message = "نقاط الطالب غير كافية.";
    } elseif ($product['stock'] <= 0) {
        $message = "المنتج غير متوفر.";
    } else {
        // عملية الشراء: خصم النقاط وتحديث المخزون وتسجيل الطلب
        $pdo->beginTransaction();
        try {
            $stmt = $pdo->prepare("UPDATE students SET points = points - :price WHERE id = :student_id");
            $stmt->execute([':price' => $product['price'], ':student_id' => $student['id']]);
            $stmt = $pdo->prepare("UPDATE products SET stock = stock - 1 WHERE id = :id");
            $stmt->execute([':id' => $product_id]);
            $stmt = $pdo->prepare("INSERT INTO orders (student_phone, product_id, status) VALUES (:phone, :product_id, 'مكتمل')");
            $stmt->execute([':phone' => $student_phone, ':product_id' => $product_id]);
            // تسجيل عملية خصم النقاط
            $stmt = $pdo->prepare("INSERT INTO points_history (student_id, points, type, note) VALUES (:student_id, :points, 'subtraction', 'شراء منتج')");
            $stmt->execute([':student_id' => $student['id'], ':points' => $product['price']]);
            $pdo->commit();
            $message = "تم الطلب بنجاح!";
        } catch (Exception $e) {
            $pdo->rollBack();
            $message = "حدث خطأ أثناء معالجة الطلب.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>تفاصيل المنتج</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
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
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
        }
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #2c3e50;
            padding: 15px 30px;
            color: white;
        }
        .navbar .logo {
            display: flex;
            align-items: center;
            font-size: 24px;
            font-weight: bold;
            margin-left: auto; /* لتحريك النص إلى اليمين */
        }
        .navbar .logo img {
            width: 30px; /* ضبط حجم الأيقونة */
            margin-left: 10px; /* المسافة بين النص والأيقونة */
        }
        .navbar a {
            color: white;
            text-decoration: none;
            font-size: 18px;
        }
        .navbar a:hover {
            text-decoration: underline;
        }
        .login-btn {
            display: flex;
            align-items: center;
            background: #e74c3c;
            padding: 8px 15px;
            border-radius: 5px;
        }
        .login-btn i {
            margin-left: 5px;
        }
        .container {
            max-width: 1200px;
            margin: 20px auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
        }
        .product-details {
            flex: 1;
            margin-right: 20px;
        }
        .product-details img {
            max-width: 100%;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .product-info {
            flex: 1;
            text-align: right;
            display: flex;
            flex-direction: column;
            justify-content: space-around;
        }
        .product-info p {
            margin-bottom: 10px;
        }
        .message {
            color: blue;
            margin-top: 15px;
        }
        .form-actions {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }
        .form-actions a, .form-actions input {
            background-color: #3498db;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 16px;
        }
        .form-actions a:hover, .form-actions input:hover {
            background-color: #2980b9;
        }
        .price{
            color: #28a745;
            font-size: 1.5rem;
            font-weight: 500;
        }
        input[type="text"] {
            width: 80%;
            padding: 10px;
            margin-top: 5px;
            margin-bottom: 10px;
            border-radius: 5px;
            border: 2px solid #ccc;
            font-size: 20px;
            box-sizing: border-box; /* لضمان عدم تجاوز العرض الإجمالي */
        }

        /* عند الشاشات الصغيرة */
        @media (max-width: 768px) {
            .container {
                flex-direction: column; /* جعل العناصر عمودية */
                align-items: center; /* محاذاة العناصر في الوسط */
            }

            .product-details,
            .product-info {
                flex: none; /* لإلغاء توزيع المساحة المتساوي بين العناصر */
                width: 100%; /* عرض كامل لجميع العناصر */
                text-align: center; /* جعل النصوص في الوسط */
            }

            .product-details img {
                margin-bottom: 15px; /* زيادة المسافة أسفل الصورة */
            }

            .form-actions {
                flex-direction: column-reverse; /* جعل الأزرار في عمود */
                align-items: center; /* محاذاة الأزرار في المنتصف */
            }

            .form-actions a,
            .form-actions input {
                width: 90%; /* جعل الأزرار في عرض كامل */
                margin: 10px 0; /* المسافة بين الأزرار */
            }

        }


    </style>
</head>
<body>
    <nav class="navbar">
        <a href="../all-login.html" class="login-btn">تسجيل الدخول <i class="fas fa-user"></i></a>
        <div class="logo">
            <span>المتجر المدرسي</span>
            <img src="../../photo/or-icon.png" alt="Shop Icon">
        </div>
    </nav>
    
    <div class="container">
        <div class="product-details">
            <img src="../../uploads/<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
        </div>

        <div class="product-info">
            <h2><?= htmlspecialchars($product['name']) ?></h2>
            <p><?= htmlspecialchars($product['description']) ?></p>
            <p class="price">السعر: <?= $product['price'] ?> نقطة</p>
            <p>المخزون: <?= $product['stock'] ?></p>
            <?php if($message) echo "<p class='message'>$message</p>"; ?>
        
            <form method="post">
                <label> : أدخل رقم هاتف الطالب</label>
                <input type="text" name="student_phone" required>
                <br>
                
            <div class="form-actions">
                <a href="../../index.php">العودة للمتجر</a>
                <input type="submit" value="طلب المنتج">
            </div>
            </form>


        </div>
    </div>
</body>
</html>
