<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit;
}
require_once '../../config.php';

if (!isset($_GET['id'])) {
    header("Location: students.php");
    exit;
}

$student_id = (int)$_GET['id'];
$stmt = $pdo->prepare("DELETE FROM students WHERE id = :id");
$stmt->execute([':id' => $student_id]);
header("Location: dashboard.php?button=button3");
exit;
?>
