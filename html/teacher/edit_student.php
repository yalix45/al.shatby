<?php
session_start();
if (!isset($_SESSION['teacher'])) {
    header("Location: teacher_login.php");
    exit;
}
require_once '../../config.php';
$teacher = $_SESSION['teacher'];

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$student_id = (int)$_GET['id'];
// التأكد من أن الطالب يخص المعلم الحالي
$stmt = $pdo->prepare("SELECT * FROM students WHERE id = :id AND teacher_id = :teacher_id");
$stmt->execute([':id' => $student_id, ':teacher_id' => $teacher['id']]);
$student = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$student) {
    die("الطالب غير موجود أو ليس من ضمن طلابك.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $phone = trim($_POST['phone']);
    
    $stmt = $pdo->prepare("UPDATE students SET name = :name, phone = :phone WHERE id = :id AND teacher_id = :teacher_id");
    $stmt->execute([
        ':name' => $name,
        ':phone' => $phone,
        ':id' => $student_id,
        ':teacher_id' => $teacher['id']
    ]);
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>تعديل الطالب</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        
/* navbar */
.navbar{
    display: flex;
    align-content: center;
    justify-content: space-between;
    width: 100vw;
    height: 9vh;
    padding: 0.6rem;
    box-shadow: 1px 9px 11px rgba(0, 0, 0, 0.1);
    border-bottom: solid 1px #5ba3ac;
}
.icon-nav{
    display: flex;
    align-items: center;
    gap: 3vw;
    justify-content: space-between;
    width: 100%;
}
.icon-nav .icon-btn{
    display: flex;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 2vw;
}
.icon-nav .icon-btn i{
    font-size: 1.2rem;
}
.icon-nav .icon-btn-order a{
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: row-reverse;
    text-decoration: none;
    background: #2f5393;
    padding: 0.7rem;
    border-radius: 10px;
    gap: 5px;
}
.icon-nav .icon-btn-order i{
    font-size: 1.2rem;
}
.icon-nav .icon-btn-order p{
    font-size: 1.5vw;
    color: #fff;
}
.navbar .nav-img{
    display: flex;
    flex-direction: row-reverse;
    align-items: center;
    gap: 2rem;
}
    </style>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer">

</head>
<body>
<navbar class="navbar">
        <div class="icon-nav">
            <div class="nav-img">
                <h3>مـجـمـع الـشـاطـبـي</h3>
                <img src="../../photo/or-icon.png" alt="" style="width: 3.5rem;">
            </div>
            <div style="    display: flex; flex-direction: row; gap: 2rem;">

                <div class="icon-btn">
                    <a href="index.php"> <i class="fa-solid fa-house" style="color: #2d4d86;"></i></a>
                    <a href="../store/index.php"> <i class="fa-solid fa-store" style="color: #2f5393;"></i></i></a>
                    <a href="teacher_logout.php"> <i class="fa-solid fa-power-off" style="color: #2f5393;"></i></a>
                </div>
            </div>
        </div>
    </navbar>

<div class="container">
    <h2>تعديل بيانات الطالب</h2>
    <form method="post">
        <label>اسم الطالب:</label>
        <input type="text" name="name" value="<?= htmlspecialchars($student['name']) ?>" required>
        <label>رقم الهاتف:</label>
        <input type="text" name="phone" value="<?= $student['phone'] ?>" required>
        <br>
        <input type="submit" value="تعديل">
    </form>
    <a href="index.php">العودة للوحة التحكم</a>
</div>
</body>
</html>
