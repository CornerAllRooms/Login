document.getElementById('google-login').addEventListener('click', () => {
    // Initialize Google client
    google.accounts.id.initialize({
        client_id: process.env.GOOGLE_CLIENT_ID,
        callback: handleGoogleResponse
    });
    
    // Show Google login popup
    google.accounts.id.prompt();
});

async function handleGoogleResponse(response) {
    try {
        const res = await fetch('/api/auth/google', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ token: response.credential })
        });
        
        const data = await res.json();
        
        if (data.success) {
            window.location.href = '/homepage.php'; // Redirect after successful login
        } else {
            alert('Login failed: ' + (data.error || 'Unknown error'));
        }
    } catch (err) {
        console.error('Login error:', err);
        alert('Login failed. Please try again.');
    }
}
