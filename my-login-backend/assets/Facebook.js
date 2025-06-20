const axios = require('axios');

app.post('/auth/facebook', async (req, res) => {
    const { token } = req.body;
    try {
        const response = await axios.get(
            `https://graph.facebook.com/v18.0/me?access_token=${token}&fields=id,name,email`
        );
        const userData = response.data;
        console.log('Facebook user data:', userData);
        // Save user data to your database or create a session
        res.json({ success: true, user: userData });
    } catch (error) {
        console.error('Facebook token verification failed:', error);
        res.status(400).json({ success: false, error: 'Invalid token' });
    }
});