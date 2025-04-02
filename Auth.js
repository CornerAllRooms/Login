document.getElementById('google-login').addEventListener('click', () => {
    // Initialize Google OAuth client
    const client = google.accounts.oauth2.initTokenClient({
        client_id: '940299451426-3eqoo89edvkf42l00fs4dn5i5unis2dm.apps.googleusercontent.com', // Replace with your Client ID
        scope: 'email profile openid',
        callback: async (response) => {
            if (response.error) {
                console.error('Google Auth Error:', response.error);
                return;
            }
            
            // Send token to backend
            try {
                const res = await fetch('http://localhost:26543/storage/78F3-131C/work+%281%29/login/index.php', {
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