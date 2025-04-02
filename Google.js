document.getElementById('google-login').addEventListener('click', handleGoogleLogin);

function handleGoogleLogin() {
    // Initialize Google OAuth client
    const client = google.accounts.oauth2.initTokenClient({
        client_id: '940299451426-3eqoo89edvkf42l00fs4dn5i5unis2dm.apps.googleusercontent.com', // Replace with your actual Client ID
        scope: 'email profile openid',
        callback: async (response) => {
            if (response.error) {
                console.error('Google OAuth error:', response.error);
                return;
            }
            
            // Send the token to your backend for verification
            try {
                const res = await fetch('/auth/google', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ token: response.access_token }),
                });
                
                const data = await res.json();
                if (data.success) {
                    console.log('User logged in:', data.user);
                    // Redirect or update UI (e.g., show user's name)
                    window.location.href = '/dashboard'; // Example redirect
                } else {
                    console.error('Login failed:', data.error);
                }
            } catch (err) {
                console.error('Failed to verify token:', err);
            }
        },
    });
    
    // Request the token
    client.requestAccessToken();
}