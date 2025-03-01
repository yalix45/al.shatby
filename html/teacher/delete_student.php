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
$stmt = $pdo->prepare("DELETE FROM students WHERE id = :id AND teacher_id = :teacher_id");
$stmt->execute([
    ':id' => $student_id,
    ':teacher_id' => $teacher['id']
]);
header("Location: index.php");
exit;
?>
