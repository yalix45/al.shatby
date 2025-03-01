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
}

// استرجاع الطلاب مع معلومات المعلم
$stmt = $pdo->query("SELECT s.*, t.name AS teacher_name FROM students s LEFT JOIN teachers t ON s.teacher_id = t.id");
$students = $stmt->fetchAll(PDO::FETCH_ASSOC);

// استرجاع قائمة المعلمين للربط عند الإضافة
$stmt = $pdo->query("SELECT * FROM teachers");
$teachers = $stmt->fetchAll(PDO::FETCH_ASSOC);

// حساب عدد الطلاب
$stmt = $pdo->query("SELECT COUNT(*) AS total_students FROM students");
$total_students = $stmt->fetch(PDO::FETCH_ASSOC)['total_students'];
?>
<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>إدارة الطلاب</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        .container {
            width: 90%;
            margin: auto;
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        thead th {
            background-color: #6c00bd;
            color: white;
            padding: 12px;
        }

        /* إضافة زوايا دائرية فقط للترويسة العلوية */
        thead tr:first-child th:first-child {
            border-top-right-radius: 8px;
        }

        thead tr:first-child th:last-child {
            border-top-left-radius: 8px;
        }

        th, td {
            padding: 12px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }

        tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tbody tr:hover {
            background-color: #f1f1f1;
        }

        /* زر الحذف والتعديل مع الأيقونات */
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

        /* تنسيق زر الإضافة */
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
        .info-num-stu{
            background-color: #28a745;
            color: #fff;
            font-size: 1.2rem;
            margin: auto;
        }
    </style>
</head>
<body>
<div class="container">
    <!-- <h2>إدارة الطلاب</h2> -->
    <?php if(isset($message)) echo "<p style='color:green;'>$message</p>"; ?>

    <div class="info-nuber-ch info-num-stu">
        <?php
        $stmt = $pdo->query("SELECT COUNT(*) AS count FROM students");
        $students_count = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
        ?>
        <p><strong><i class="fa-solid fa-user-graduate"></i> | عدد الطلاب  </strong><?= $students_count ?></p>

    </div>
    
    <a href="add-student.php" class="add-btn">إضافة الطالب <i class="fa-solid fa-plus"></i></a>

    <h3>قائمة الطلاب</h3>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>الاسم</th>
                <th>رقم الهاتف</th>
                <th>المعلم</th>
                <th>النقاط</th>
                <th>العمليات</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($students as $s): ?>
            <tr>
                <td><?= $s['id'] ?></td>
                <td><?= htmlspecialchars($s['name']) ?></td>
                <td><?= $s['phone'] ?></td>
                <td><?= htmlspecialchars($s['teacher_name']) ?></td>
                <td><?= $s['points'] ?></td>
                <td>
                    <a href="edit_student.php?id=<?= $s['id'] ?>" class="action-btn edit-btn">
                        تعديل <i class="fa-solid fa-pen-fancy"></i>
                    </a>
                    <a href="delete_student.php?id=<?= $s['id'] ?>" class="action-btn delete-btn" onclick="return confirm('هل أنت متأكد؟')">
                        حذف <i class="fa-solid fa-trash"></i>
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>
