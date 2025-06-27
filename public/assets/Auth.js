
document.getElementById('google-login').addEventListener('click', () => {
    // Initialize Google OAuth client
    const client = google.accounts.oauth2.initTokenClient({
        client_id: process.env.GOOGLE_CLIENT_ID, // Retrieved from .env
        scope: 'email profile openid',
        callback: async (response) => {
            if (response.error) {
                console.error('Google Auth Error:', response.error);
                return;
            }
            
            // Send token to backend
            try {
                const res = await fetch(process.env.BACKEND_AUTH_ENDPOINT, { // Retrieved from .env
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ token: response.access_token }),
                });
                
                const data = await res.json();
                
                if (data.success) {
                    // Display user info
                    document.getElementById('user-info').innerHTML = `
                        <p>Welcome, ${data.user.name}!</p>
                        <img src="${data.user.picture}" width="50" style="border-radius: 50%">
                    `;
                } else {
                    alert('Login failed: ' + data.error);
                }
            } catch (err) {
                console.error('Error:', err);
                alert('Failed to connect to server');
            }
        },
    });
    
    // Request token
    client.requestAccessToken();
});