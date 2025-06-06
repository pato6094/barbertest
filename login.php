<?php session_start(); ?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - Barber Shop</title>
    <link rel="stylesheet" href="login-style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <div class="logo">
                    <div class="logo-icon">BS</div>
                </div>
                <h1>Welcome Back</h1>
                <p>Sign in to your admin account</p>
            </div>
            
            <?php if (isset($_GET['error'])): ?>
                <div class="error-message">
                    <i class="fas fa-exclamation-triangle"></i>
                    Credenziali errate. Riprova.
                </div>
            <?php endif; ?>
            
            <form method="POST" action="login_check.php" class="login-form">
                <div class="form-group">
                    <div class="input-wrapper">
                        <i class="fas fa-user"></i>
                        <input type="text" name="username" placeholder="Username" required value="admin">
                    </div>
                </div>
                
                <div class="form-group">
                    <div class="input-wrapper">
                        <i class="fas fa-lock"></i>
                        <input type="password" name="password" placeholder="Password" required value="admin123">
                    </div>
                </div>
                
                <div class="form-options">
                    <label class="checkbox-wrapper">
                        <input type="checkbox">
                        <span class="checkmark"></span>
                        Remember me
                    </label>
                    <a href="#" class="forgot-password">Forgot Password?</a>
                </div>
                
                <button type="submit" class="login-btn">
                    <span>Sign In</span>
                    <i class="fas fa-arrow-right"></i>
                </button>
            </form>
            
            <div class="login-footer">
                <p>Credenziali di test: admin / admin123</p>
            </div>
        </div>
        
        <div class="background-decoration">
            <div class="decoration-circle circle-1"></div>
            <div class="decoration-circle circle-2"></div>
            <div class="decoration-circle circle-3"></div>
        </div>
    </div>

    <script>
        // Add loading state to login button
        document.querySelector('.login-form').addEventListener('submit', function() {
            const btn = document.querySelector('.login-btn');
            btn.classList.add('loading');
        });
    </script>
</body>
</html>