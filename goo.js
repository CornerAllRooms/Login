// Google Login
document.getElementById('google-Login').addEventListener('click', function () {
    google.accounts.id.initialize({
        client_id: '940299451426-3eqoo89edvkf42l00fs4dn5i5unis2dm.apps.googleusercontent.com', // Replace with your Google Client ID
        callback: handleGoogleResponse,
    });
    google.accounts.id.prompt(); // Show the Google login popup
});

function handleGoogleResponse(response) {
    console.log('Google login successful', response);
    // Send the ID token to your server for verification
    fetch('/auth/google', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ token: response.credential }),
    })
        .then((response) => response.json())
        .then((data) => {
            console.log('Server response:', data);
            // Redirect or update UI
        });
}