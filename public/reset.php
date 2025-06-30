// Password reset request handler
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['forgot_password'])) {
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    
    $user = $users->findOne(['profile.email' => $email]);
    
    if ($user) {
        $resetToken = bin2hex(random_bytes(32));
        $expires = new MongoDB\BSON\UTCDateTime((time() + 3600) * 1000); // 1 hour
        
        $users->updateOne(
            ['_id' => $user['_id']],
            ['$set' => [
                'auth.local.resetToken' => $resetToken,
                'auth.local.resetExpires' => $expires
            ]]
        );
        
        // Send email with reset link
        $resetLink = "https://lobby.cornerroom.co.za/reset-password?token=$resetToken";
        sendResetEmail($user['profile']['email'], $resetLink);
    }
    
    // Always return success to prevent email enumeration
    header('Location: /forgot-password?success=email_sent');
}

// Password reset handler
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reset_password'])) {
    $token = filter_input(INPUT_POST, 'token', FILTER_SANITIZE_STRING);
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
    
    $user = $users->findOne([
        'auth.local.resetToken' => $token,
        'auth.local.resetExpires' => ['$gt' => new MongoDB\BSON\UTCDateTime()]
    ]);
    
    if ($user) {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        
        $users->updateOne(
            ['_id' => $user['_id']],
            ['$set' => [
                'auth.local.password' => $hashedPassword,
                'auth.local.lastPasswordChange' => new MongoDB\BSON\UTCDateTime(),
                'auth.local.resetToken' => null,
                'auth.local.resetExpires' => null
            ]]
        );
        
        header('Location: /login?success=password_reset');
    } else {
        header('Location: /reset-password?error=invalid_token');
    }
}