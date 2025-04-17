<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Add icon link -->
    <link rel="icon" href="logo.png" type="image/x-icon" />
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register & Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <form action="auth.php" method="POST">
</form>
</head>
<body>

    <!-- Sign Up Container -->
    <div class="container" id="signup" style="display:none;">
        <h1 class="form-title">Register</h1>
        <form method="post" action="register.php">
            <div class="input-group">
                <i class="fas fa-user"></i>
                <input type="text" name="fName" id="fName" placeholder="First Name" required>
                <label for="fName"></label>
            </div>
            <div class="input-group">
                <i class="fas fa-user"></i>
                <input type="text" name="lName" id="lName" placeholder="Last Name" required>
                <label for="lName"></label>
            </div>
            <div class="input-group">
                <i class="fas fa-envelope"></i>
                <input type="email" name="email" id="email" placeholder="Email" required>
                <label for="email"></label>
            </div>
            <div class="input-group">
                <i class="fas fa-lock"></i>
                <input type="password" name="password" id="signup-password" placeholder="Password" required>
                <span class="password-toggle" onclick="togglePassword('signup-password')">
                    <i class="fas fa-eye"></i>
                </span>
                <label for="password"></label>
            </div>
            <input type="submit" class="btn" value="Sign Up" name="signUp">
        </form>
        <p class="or">or</p>
        <div class="icons">   <i class="fab fa-google" id="google-login"></i>
            <i clss="fab fa-facebook"></i>
        </div>
        <div class="links">
            <p>Already Have an Account?</p>
            <button id="signInButton">Sign In</button>
        </div>
    </div>

    <!-- Sign In Container -->
    <div class="container" id="signIn">
        <h1 class="form-title">Sign In</h1>
        <form method="post" action="register.php">
            <div class="input-group">
                <i class="fas fa-envelope"></i>
                <input type="email" name="email" id="email" placeholder="Email" required>
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
                <a href=: "<%= process.env.VITE_FIREBASE_AUTH_DOMAIN %">Recover Password</a>
            </p>
            <input type="submit" class="btn" value="Sign In" name="signIn">
        </form>
        <p class="or">or</p>
        
        <div class="icons">
            <i class="fab fa-google" id="google-login"></i>
            <i clss="fab fa-facebook"; href="facebook.js:"></i>
        </div>
        <div class="links">
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
    </script>

    <!-- All your original scripts remain untouched -->
    <script src="script.js"></script>
    <script src="google.js"></script>
    <script src="goo.js"></script>
    <script src="server.js"></script>
    <script src="google2.js"></script>
    <script src="app.js"></script>
    <script src="goo.js"></script>
    <script src="facebook.js"></script>
    <script src="auth.js"></script>                  

    <!-- Facebook SDK -->
    <script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_US/sdk.js"></script>

    <!-- Google OAuth client library -->
    <script src="https://accounts.google.com/gsi/client" async defer></script>

    <!-- Firebase (now using .env) -->
    <script>
    document.getElementById('recovered').addEventListener('onclick', () => {
        // Load Firebase config from environment variables
        const firebaseConfig = {
            apiKey: "<%= process.env.VITE_FIREBASE_API_KEY %>",
            authDomain: "<%= process.env.VITE_FIREBASE_AUTH_DOMAIN %>",
        };
        firebase.initializeApp(firebaseConfig);
    </script>
</body>
</html>
