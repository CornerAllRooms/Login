// Create indexes (run once in MongoDB Playground)
use('roomie13');

// Unique email index (critical for auth)
db.users.createIndex({ email: 1 }, { unique: true });

// Case-insensitive name search index
db.users.createIndex(
  { name: "text", surname: "text" },
  { collation: { locale: "en", strength: 2 } }
);

// TTL index for inactive users (optional)
db.users.createIndex(
  { lastLogin: 1 },
  { expireAfterSeconds: 365 * 24 * 3600 } // 1 year inactivity
);