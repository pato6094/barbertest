<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head><title>Login</title><link rel="stylesheet" href="style.css"></head>
<body>
<div class="form-container">
<form method="POST" action="login_check.php">
    <h1>Login Admin</h1>
    <input type="text" name="username" placeholder="Username" required>
    <input type="password" name="password" placeholder="Password" required>
    <button type="submit">Accedi</button>
</form>
</div>
</body>
</html>