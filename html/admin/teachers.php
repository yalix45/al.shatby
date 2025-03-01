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
}

// استرجاع المعلمين
$stmt = $pdo->query("SELECT * FROM teachers");
$teachers = $stmt->fetchAll(PDO::FETCH_ASSOC);

$teachers_count = count($teachers);

// استرجاع عدد الطلاب لكل معلم
foreach ($teachers as $index => $teacher) {
    $teacher_id = $teacher['id'];
    $stmt_students = $pdo->prepare("SELECT COUNT(*) AS count FROM students WHERE teacher_id = :teacher_id");
    $stmt_students->execute([':teacher_id' => $teacher_id]);
    $students_count = $stmt_students->fetch(PDO::FETCH_ASSOC)['count'];
    $teachers[$index]['students_count'] = $students_count; // إضافة عدد الطلاب إلى بيانات المعلم
}
?>
<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>إدارة المعلمين</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        .container {
            width: 90%;
            margin: auto;
            text-align: center;
        }
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
            text-align: center;
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
            margin: auto;
        }
    </style>
</head>
<body>
<main class="table">
    <div class="table__header">
        <!-- <h2>إدارة المعلمين</h2> -->
    </div>
    <div class="container">

        <div class="info-nuber-ch info-num-tea">
            <p><strong><i class="fa-solid fa-chalkboard-user"></i> | عدد المعلمين  </strong><?= $teachers_count ?></p>
        </div>

        <a href="add-teacher.php" class="add-btn">إضافة المعلم <i class="fa-solid fa-plus"></i></a>
        
        <h3>قائمة المعلمين</h3>
        <div class="table__body">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>الاسم</th>
                        <th>رقم الهاتف</th>
                        <th>الفصل</th>
                        <th>عدد الطلاب</th>
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
                        <td><?= $t['students_count'] ?></td>
                        <td>
                            <a href="edit_teacher.php?id=<?= $t['id'] ?>" class="action-btn edit-btn">
                                تعديل <i class="fa-solid fa-pen-fancy"></i>
                            </a>
                            <a href="delete_teacher.php?id=<?= $t['id'] ?>" class="action-btn delete-btn" onclick="return confirm('هل أنت متأكد؟')">
                                حذف <i class="fa-solid fa-trash"></i>
                            </a>
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
