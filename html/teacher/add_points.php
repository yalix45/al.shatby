<?php
session_start();
if (!isset($_SESSION['teacher'])) {
    header("Location: teacher_login.php");
    exit;
}
require_once '../../config.php';
$teacher = $_SESSION['teacher'];

// استرجاع الطلاب التابعين للمعلم
$stmt = $pdo->prepare("SELECT * FROM students WHERE teacher_id = :teacher_id");
$stmt->execute([':teacher_id' => $teacher['id']]);
$students = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_points'])) {
    $student_id = $_POST['student_id'];
    $points = (int)$_POST['points'];
    // تحديث نقاط الطالب
    $stmt = $pdo->prepare("UPDATE students SET points = points + :points WHERE id = :student_id AND teacher_id = :teacher_id");
    $stmt->execute([ 
        ':points' => $points,
        ':student_id' => $student_id,
        ':teacher_id' => $teacher['id']
    ]);
    // تسجيل العملية في سجل النقاط
    $stmt = $pdo->prepare("INSERT INTO points_history (student_id, points, type, note) VALUES (:student_id, :points, 'addition', 'تمت الإضافة من المعلم')");
    $stmt->execute([
        ':student_id' => $student_id,
        ':points' => $points
    ]);
    $message = "تم إضافة $points نقطة للطالب.";
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إضافة نقاط للطلاب</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        @import url("https://fonts.googleapis.com/css2?family=Rubik:wght@300..900&display=swap");

        * {
            font-family: "Rubik", sans-serif;
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            display: flex;
            flex-direction: column;
            background: white;
            color: #333;
            text-align: center;
            height: 100vh;
            align-items: center;
            gap: 5vh;
        }

        .container {
            background: rgba(255, 255, 255, 1);
            padding: 35px;
            border-radius: 15px;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
            width: 90%;
            max-width: 600px;
        }

        h2 {
            margin-bottom: 20px;
            font-size: 26px;
            color: #1c646e;
        }

        label {
            font-size: 18px;
            margin-bottom: 10px;
            display: block;
            color: #555;
        }

        select {
            padding: 12px;
            font-size: 16px;
            border-radius: 5px;
            width: 100%;
            border: 2px solid #ddd;
            background: rgba(255, 255, 255, 0.9);
            color: #333;
            transition: 0.3s;
        }

        select:focus {
            border-color: #1c646e;
            outline: none;
        }

        /* أزرار اختيار النقاط */
        .all-btn {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(100px, 1fr));
            gap: 15px;
            margin: 20px 0;
        }

        button {
            width: 100%;
            height: 80px;
            background: white;
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            font-size: 22px;
            font-weight: bold;
            color: #333;
            border: 2px solid #ddd;
            cursor: pointer;
            transition: 0.3s;
        }

        button:hover {
            background: #f0f8f8;
        }

        /* التأثير عند اختيار الزر */
        .selected {
            color: #1c646e !important;
            border-color: #1c646e !important;
        }

        /* زر الإضافة */
        .submit-btn {
            background: #28a745;
            border: none;
            padding: 14px 20px;
            font-size: 18px;
            border-radius: 8px;
            cursor: pointer;
            width: 100%;
            transition: 0.3s;
            font-weight: bold;
            color: white;
        }

        .submit-btn:hover {
            background: #1c646e;
        }

        /* زر الرجوع */
        .back-btn {
            display: block;
            margin-top: 15px;
            background: #dc3545;
            color: white;
            padding: 12px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 16px;
            font-weight: bold;
            transition: 0.3s;
        }

        .back-btn:hover {
            background: #c82333;
        }

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
                    <a href="../../index.php"> <i class="fa-solid fa-store" style="color: #2f5393;"></i></i></a>
                    <a href="teacher_logout.php"> <i class="fa-solid fa-power-off" style="color: #2f5393;"></i></a>
                </div>
            </div>
        </div>
    </navbar>

<div class="container">
    <h2>إضافة نقاط للطلاب</h2>
    
    <?php if (isset($message)) echo "<p style='color:green;'>$message</p>"; ?>

    <form method="post">
        <label>اختر الطالب:</label>
        <select name="student_id" required>
            <option value="">-- اختر الطالب --</option>
            <?php foreach ($students as $s): ?>
                <option value="<?= $s['id'] ?>"><?= htmlspecialchars($s['name']) . " (" . $s['phone'] . ")" ?></option>
            <?php endforeach; ?>
        </select>

        <!-- أزرار اختيار النقاط -->
        <div class="all-btn">
            <button type="button" onclick="setPoints(50, this)">50</button>
            <button type="button" onclick="setPoints(100, this)">100</button>
            <button type="button" onclick="setPoints(150, this)">150</button>
            <button type="button" onclick="setPoints(200, this)">200</button>
            <button type="button" onclick="setPoints(250, this)">250</button>
            <button type="button" onclick="setPoints(300, this)">300</button>
            <button type="button" onclick="setPoints(400, this)">400</button>
            <button type="button" onclick="setPoints(500, this)">500</button>
        </div>

        <!-- إدخال مخفي لحفظ النقاط المحددة -->
        <input type="hidden" name="points" id="pointsInput">

        <input type="submit" name="add_points" class="submit-btn" value="إضافة النقاط">
    </form>

    <a href="index.php" class="back-btn">العودة</a>
</div>

<script>
    function setPoints(value, btn) {
        document.getElementById('pointsInput').value = value;
        
        // إزالة التأثير من جميع الأزرار
        document.querySelectorAll(".all-btn button").forEach(button => {
            button.classList.remove("selected");
        });

        // إضافة التأثير للزر المحدد
        btn.classList.add("selected");
    }
</script>

</body>
</html>
