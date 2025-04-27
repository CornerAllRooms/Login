require('dotenv').config();
const express = require('express');
const session = require('express-session');
const { MongoClient } = require('mongodb');
const { OAuth2Client } = require('google-auth-library');
const cors = require('cors');

const app = express();

// Middleware
app.use(cors({ origin: process.env.FRONTEND_URL, credentials: true }));
app.use(express.json());
app.use(session({
    secret: process.env.SESSION_SECRET,
    resave: false,
    saveUninitialized: true,
    cookie: { secure: process.env.NODE_ENV === 'production' }
}));

// MongoDB Connection
let db;
(async () => {
    try {
        const client = await MongoClient.connect(process.env.MONGODB_URI);
        db = client.db(process.env.DB_NAME);
        console.log('Connected to MongoDB');
    } catch (err) {
        console.error('MongoDB connection error:', err);
    }
})();

// Google OAuth Client
const googleClient = new OAuth2Client(process.env.GOOGLE_CLIENT_ID);

// Google Auth Endpoint
app.post('/api/auth/google', async (req, res) => {
    try {
        const { token } = req.body;
        const ticket = await googleClient.verifyIdToken({
            idToken: token,
            audience: process.env.GOOGLE_CLIENT_ID
        });
        const payload = ticket.getPayload();

        // Check if user exists in MongoDB
        let user = await db.collection('users').findOne({ email: payload.email });

        if (!user) {
            // Create new user
            user = {
                googleId: payload.sub,
                email: payload.email,
                name: payload.name,
                picture: payload.picture,
                createdAt: new Date()
            };
            const result = await db.collection('users').insertOne(user);
            user._id = result.insertedId;
        }

        // Create session
        req.session.user = {
            id: user._id,
            email: user.email,
            name: user.name
        };

        res.json({ success: true, user: req.session.user });
    } catch (err) {
        console.error('Google auth error:', err);
        res.status(400).json({ success: false, error: 'Authentication failed' });
    }
});

// Start server
const PORT = process.env.PORT || 3001;
app.listen(PORT, () => console.log(`Server running on port ${PORT}`));
   git init
   git add .
   git commit -m "Initial commit"
   git branch -M main
   git remote add origin https://github.com/CornerAllRooms/Login.git
   git push -u origin main
