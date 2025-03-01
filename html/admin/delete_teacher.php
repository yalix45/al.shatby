<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit;
}
require_once '../../config.php';

if (!isset($_GET['id'])) {
    header("Location: teachers.php");
    exit;
}

$teacher_id = (int)$_GET['id'];
$stmt = $pdo->prepare("DELETE FROM teachers WHERE id = :id");
$stmt->execute([':id' => $teacher_id]);
header("Location: dashboard.php?button=button2");
exit;
?>
