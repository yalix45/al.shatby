<?php
session_start();
session_destroy();
header("Location: ../all-login.html");
exit;
?>
