<?php
require __DIR__ . '/my-login-backend/bootstrap.php';
session_start();
define('ROOT_INCLUDED', true);
require __DIR__.'/my-login-backend/auth-handler.php';

(new AuthHandler())->handleRequest();
if (isset($_SESSION['user'])) {
    header('Location: homepage.php');
    exit;
}

if (isset($_GET['error'])) {
    $error_message = htmlspecialchars($_GET['error']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="icon" href="/assets/logo.png" type="image/x-icon" />
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register & Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php if (!empty($error_message)): ?>
        <div class="error-message"><?= $error_message ?></div>
    <?php endif; ?>

    <!-- Sign Up Container -->
    <div class="container" id="signup" style="display:none;">
        <h1 class="form-title">Register</h1>
        <form method="post" action="register.php">
            <input type="hidden" name="signUp" value="1">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] = bin2hex(random_bytes(32)) ?>">
            
            <div class="input-group">
                <i class="fas fa-user"></i>
                <input type="text" name="fName" id="fName" placeholder="First Name" required
                       pattern="[A-Za-z]{2,}" title="At least 2 letters">
                <label for="fName"></label>
            </div>
            <div class="input-group">
                <i class="fas fa-user"></i>
                <input type="text" name="lName" id="lName" placeholder="Last Name" required
                       pattern="[A-Za-z]{2,}" title="At least 2 letters">
                <label for="lName"></label>
            </div>
            <div class="input-group">
                <i class="fas fa-envelope"></i>
                <input type="email" name="email" id="email" placeholder="Email" required>
                <label for="email"></label>
            </div>
            <div class="input-group">
                <i class="fas fa-lock"></i>
                <input type="password" name="password" id="signup-password" placeholder="Password" required
                       minlength="8" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}"
                       title="8+ chars with 1 uppercase, 1 lowercase, 1 number">
                <span class="password-toggle" onclick="togglePassword('signup-password')">
                    <i class="fas fa-eye"></i>
                </span>
                <label for="password"></label>
            </div>
            <input type="submit" class="btn" value="Sign Up">
        </form>
        <p class="or">or</p>
        <div class="icons"><form action="google.php" method="get">
            <input type="hidden" name="google_signin" value="1">
            <div id="g_id_onload"
                 data-client_id="<?= $_ENV['GOOGLE_CLIENT_ID'] ?>"
                 data-callback="handleGoogleSignIn">
            </div>
            <div class="g_id_signin" data-type="icon" data-shape="circle"></div>
        </div></form>
        <div class="links">
            <p>Already Have an Account?</p>
            <button id="signInButton">Sign In</button>
        </div>
    </div>

    <!-- Sign In Container -->
    <div class="container" id="signIn">
        <h1 class="form-title">Sign In</h1>
        <form method="post" action="register.php">
            <input type="hidden" name="signIn" value="1">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
            
            <div class="input-group">
                <i class="fas fa-envelope"></i>
                <input type="email" name="email" id="login-email" placeholder="Email" required>
                <label for="email"></label>
            </div>
            <div class="input-group">
                <i class="fas fa-lock"></i>
                <input type="password" name="password" id="signin-password" placeholder="Password" required>
                <span class="password-toggle" onclick="togglePassword('signin-password')">
                    <i class="fas fa-eye"></i>
                </span>
                <label for="password"></label>
            </div>
            <p class="recover">
             <a href="reset.php">Recover Password</a>
            </p>
            <input type="submit" class="btn" value="Sign In">
        </form>
        <p class="or">or</p>
        <div class="icons"><form action="google.php" method="get">
            <input type="hidden" name="google_signin" value="1">
            <div id="g_id_onload"
                 data-client_id="<?= $_ENV['GOOGLE_CLIENT_ID'] ?>"
                 data-callback="handleGoogleSignIn">
            </div>
            <div class="g_id_signin" data-type="icon" data-shape="circle"></div>
        </div>
        <div class="links"></form>
            <p>Don't Have an Account Yet?</p>
            <button id="signUpButton">Sign Up</button>
        </div>
    </div>

    <script>
        function togglePassword(inputId) {
            const passwordInput = document.getElementById(inputId);
            const toggleIcon = passwordInput.nextElementSibling.querySelector('i');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }

        // Toggle between forms
        document.getElementById('signUpButton').addEventListener('click', () => {
            document.getElementById('signIn').style.display = 'none';
            document.getElementById('signup').style.display = 'block';
        });

        document.getElementById('signInButton').addEventListener('click', () => {
            document.getElementById('signup').style.display = 'none';
            document.getElementById('signIn').style.display = 'block';
        });

        // Google Sign-In handler
        function handleGoogleSignIn(response) {
            fetch('goo.js', { // Your existing Google handler
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ credential: response.credential })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = 'homepage.php';
                } else {
                    alert('Error: ' + data.error);
                }
            });
        }
    </script>

    <!-- Keep your original scripts -->
    <script src="script.js"></script>
    <script src="google.js"></script>
    <script src="goo.js"></script>
    <script src="https://accounts.google.com/gsi/client" async defer></script>
</body>
</html>