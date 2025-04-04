
document.getElementById('google-login').addEventListener('click', handleGoogleLogin);

function handleGoogleLogin() {
    // Initialize Google OAuth client
    const client = google.accounts.oauth2.initTokenClient({
        client_id: process.env.VITE_GOOGLE_CLIENT_ID, // From .env
        scope: 'email profile openid',
        callback: async (response) => {
            if (response.error) {
                console.error('Google OAuth error:', response.error);
                return;
            }
            
            // Send the token to your backend for verification
            try {
                const res = await fetch(process.env.VITE_BACKEND_AUTH_ENDPOINT, { // From .env
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