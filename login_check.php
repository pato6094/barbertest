<?php
session_start();
if ($_POST['username'] === 'admin' && $_POST['password'] === 'admin123') {
    $_SESSION['logged'] = true;
    header("Location: admin.php");
} else {
    echo "Credenziali errate.";
}
?>