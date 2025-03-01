<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit;
}
require_once '../../config.php';

if (!isset($_GET['id'])) {
    header("Location: dashboard.php?button=button4");
    exit;
}

$product_id = (int)$_GET['id'];
$stmt = $pdo->prepare("DELETE FROM products WHERE id = :id");
$stmt->execute([':id' => $product_id]);
header("Location: dashboard.php?button=button4");
exit;
?>
