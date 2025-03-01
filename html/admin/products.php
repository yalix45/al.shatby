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
}

// استرجاع المنتجات
$stmt = $pdo->query("SELECT * FROM products");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// استعلام لإحصاء عدد المنتجات
$stmtCount = $pdo->query("SELECT COUNT(*) FROM products");
$productCount = $stmtCount->fetchColumn();
?>
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
            background-color: #6c00bd;
            color: white;
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
        .edit-btn:hover {
            background-color: #218838;
        }
        .delete-btn:hover {
            background-color: #c82333;
        }
        .fa-trash, .fa-pen-fancy {
            margin-left: 5px;
        }
        .add-btn {
            display: inline-block;
            background-color: #6c00bd;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            margin: 15px 0;
            transition: 0.3s;
        }
        .add-btn:hover {
            background-color: #520089;
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
        .info-num-pro{
            background-color: #fd7e14;
            color: #fff;
            font-size: 1.2rem;
            margin: auto;
        }
    </style>
</head>
<body>
<div class="container">
    <!-- <h2>إدارة المنتجات</h2> -->




    <div class="info-nuber-ch info-num-pro">
        <?php
        $stmt = $pdo->query("SELECT COUNT(*) AS count FROM products");
        $products_count = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
        ?>
        <p><strong><i class="fa-solid fa-gifts"></i> | عدد المنتجات  </strong><?= $products_count ?></p>

    </div>



    <a href="add-products.php" class="add-btn">إضافة المنتج <i class="fa-solid fa-plus"></i></a>

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
                                <a href="edit_product.php?id=<?= $prod['id'] ?>" class="action-btn edit-btn">تعديل <i class="fa-solid fa-pen-fancy"></i></a> | 
                                <a href="delete_product.php?id=<?= $prod['id'] ?>" class="action-btn delete-btn" onclick="return confirm('هل أنت متأكد؟')">حذف <i class="fa-solid fa-trash"></i></a>
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
