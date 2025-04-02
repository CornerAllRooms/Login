const express = require('express');
const { OAuth2Client } = require('google-auth-library');
const cors = require('cors');
const bodyParser = require('body-parser');

const app = express();
app.use(cors()); // Allow frontend requests
app.use(bodyParser.json());

const client = new OAuth2Client('EKWUpggsVtWYavwJTzwdWtvtXO5U99zB'); // Replace with your Client ID

app.post('/auth/google', async (req, res) => {
    const { token } = req.body;
    try {
        const ticket = await client.verifyIdToken({
            idToken: token,
            audience: 'EKWUpggsVtWYavwJTzwdWtvtXO5U99zB',
        });
        const payload = ticket.getPayload();
        console.log('Google user data:', payload);
        
        // Save user to DB or create a session
        // Example: Store in session
        req.session.user = payload; // Requires express-session
        
        res.json({ success: true, user: payload });
    } catch (error) {
        console.error('Google token verification failed:', error);
        res.status(400).json({ success: false, error: 'Invalid token' });
    }
});

const PORT = 3000;
app.listen(PORT, () => console.log(`Server running on http://localhost:${PORT}`));