<?php
session_start();
if ($_POST['username'] === 'admin' && $_POST['password'] === 'admin123') {
    $_SESSION['logged'] = true;
    header("Location: admin.php");
    exit();
} else {
    header("Location: login.php?error=1");
    exit();
}
?>