<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit;
}
require_once '../../config.php';

// استرجاع الطلبات مع بيانات المنتج والطالب
$stmt = $pdo->query("
    SELECT o.*, p.name AS product_name, p.image AS product_image, s.name AS student_name 
    FROM orders o 
    LEFT JOIN products p ON o.product_id = p.id 
    LEFT JOIN students s ON o.student_phone = s.phone 
    ORDER BY o.order_date DESC
");
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>إدارة الطلبات</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        .container {
            width: 90%;
            margin: auto;
            text-align: center;
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
        .delete-btn {
            background-color: #dc3545;
        }
        .delete-btn:hover {
            background-color: #c82333;
        }
        .product-img {
            width: 50px;
            height: auto;
            border-radius: 5px;
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
        .info-num-ord{
            background-color: #dc3545;
            color: #fff;
            font-size: 1.2rem;
            margin: auto;
        }
    </style>
</head>
<body>
<div class="container">
    <!-- <h2>إدارة الطلبات</h2> -->
    <?php $orders_count = count($orders); ?>


    <div class="info-nuber-ch info-num-ord">
        <?php
        $stmt = $pdo->query("SELECT COUNT(*) AS count FROM orders");
        $orders_count = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
        ?>
        <p><strong><i class="fa-solid fa-boxes-stacked"></i> | عدد الطلبات  </strong><?= $orders_count ?></p>
    </div>



    <div class="table__body">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>اسم الطالب</th>
                    <th>هاتف الطالب</th>
                    <th>اسم المنتج</th>
                    <th>صورة المنتج</th>
                    <th>تاريخ الطلب</th>
                    <th>الحالة</th>
                    <th>العمليات</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($orders)): ?>
                    <?php foreach ($orders as $order): ?>
                        <tr>
                            <td><?= $order['id'] ?></td>
                            <td><?= htmlspecialchars($order['student_name'] ?? 'غير معروف') ?></td>
                            <td><?= htmlspecialchars($order['student_phone']) ?></td>
                            <td><?= htmlspecialchars($order['product_name']) ?></td>
                            <td>
                                <img src="../../uploads/<?= htmlspecialchars($order['product_image']) ?>" 
                                    alt="<?= htmlspecialchars($order['product_name']) ?>" class="product-img">
                            </td>
                            <td><?= $order['order_date'] ?></td>
                            <td><?= htmlspecialchars($order['status']) ?></td>
                            <td>
                                <a href="delete_order.php?id=<?= $order['id'] ?>" class="action-btn delete-btn" onclick="return confirm('هل أنت متأكد من حذف الطلب؟')">
                                    تم <i class="fa-solid fa-check"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="8">لا توجد طلبات متاحة</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>
