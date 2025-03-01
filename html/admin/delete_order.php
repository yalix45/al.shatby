<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit;
}
require_once '../../config.php';

if (!isset($_GET['id'])) {
    header("Location: dashboard.php?button=button5");
    exit;
}

$order_id = (int)$_GET['id'];
$stmt = $pdo->prepare("DELETE FROM orders WHERE id = :id");
$stmt->execute([':id' => $order_id]);
header("Location: dashboard.php?button=button5");
exit;
?>
