<?php
session_start();

/* 1. Saari session variables remove */
$_SESSION = [];

/* 2. Session destroy */
session_destroy();

/* 3. Login page par redirect */
header("Location: login.php");
exit();
?>