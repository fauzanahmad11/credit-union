<?php
session_start();

// kosongkan nilai pada session yang telah di set
$_SESSION['key'] = '';
$_SESSION['login'] = '';
unset($_SESSION['key']);
unset($_SESSION['login']);
unset($_SESSION['user']);
session_unset();
session_destroy();
header("Location: login.php");
?>