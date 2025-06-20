/* global use, db */
// Select your database
use('roomie13');

// Insert user documents with name, surname, email, and password (always hash passwords!)
db.getCollection('roomie').insertMany([
  {
    name: "John",
    surname: "Doe",
    email: "john@example.com",
    password: "$2b$10$hashedpasswordplaceholder", // Always store hashed passwords!
    createdAt: new Date()
  },
  {
    name: "Jane",
    surname: "Smith",
    email: "jane@example.com",
    password: "$2b$10$anotherhashedplaceholder",
    createdAt: new Date()
  }
]);

// Create index for faster email lookups (and ensure uniqueness)
db.getCollection('users').createIndex({ email: 1 }, { unique: true });

// Find all users (first 20)
const allUsers = db.getCollection('users').find({});
console.log("All users:", allUsers.toArray());

// Find a specific user by email
const user = db.getCollection('users').findOne({ email: "john@example.com" });
console.log("John's account:", user);