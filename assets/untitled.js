// Initialize Facebook SDK
window.fbAsyncInit = function () {
    FB.init({
        appId: 'YOUR_FACEBOOK_APP_ID', // Replace with your Facebook App ID
        cookie: true,
        xfbml: true,
        version: 'v18.0'
    });
};

// Facebook Login
document.getElementById('facebookLogin').addEventListener('click', function () {
    FB.login(function (response) {
        if (response.authResponse) {
            // User is logged in and authorized
            console.log('Facebook login successful', response);
            // Send the access token to your server for verification
            fetch('/auth/facebook', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ token: response.authResponse.accessToken }),
            })
                .then((response) => response.json())
                .then((data) => {
                    console.log('Server response:', data);
                    // Redirect or update UI
                });
        } else {
            console.log('User cancelled login or did not fully authorize.');
        }
    }, { scope: 'email,public_profile' }); // Requested permissions
});