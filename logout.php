<?php
ob_start();
session_start();
$_SESSION['is_login'] = false;
include 'admin/inc/config.php';
unset($_SESSION['customer']);
header("location: " . BASE_URL . 'login.php');
