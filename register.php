<?php
declare(strict_types=1);

require 'vendor/autoload.php';

// Load environment variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

class AuthHandler {
    private MongoDB\Collection $collection;
    
    public function __construct() {
        try {
            $client = new MongoDB\Client($_ENV['MONGODB_URI']);
            $this->collection = $client->roomie13->roomie;
        } catch (Exception $e) {
            $this->handleError("Database connection failed", 500);
        }
    }

    public function handleRequest(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->handleError("Invalid request method", 405);
        }

        try {
            if (isset($_POST['signUp'])) {
                $this->handleSignUp();
            } elseif (isset($_POST['signIn'])) {
                $this->handleSignIn();
            } else {
                $this->handleError("Invalid action", 400);
            }
        } catch (Exception $e) {
            $this->handleError("An error occurred: " . $e->getMessage(), 500);
        }
    }

    private function handleSignUp(): void {
        $data = $this->validateSignUpData();
        $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
        
        if ($this->collection->findOne(['email' => $data['email']])) {
            $this->handleError("Email address already exists", 409);
        }

        $result = $this->collection->insertOne([
            ...$data,
            'createdAt' => new MongoDB\BSON\UTCDateTime(),
            'lastLogin' => null,
            'role' => 'user',
            'status' => 'active'
        ]);

        if ($result->getInsertedCount() === 1) {
            $this->startSession($data['email']);
            header('Location: homepage.php');
            exit();
        }

        $this->handleError("Registration failed", 500);
    }

    private function handleSignIn(): void {
        $email = $this->sanitizeEmail($_POST['email']);
        $password = $_POST['password'] ?? '';
        
        $user = $this->collection->findOne(['email' => $email]);
        
        if (!$user || !password_verify($password, $user['password'])) {
            $this->handleError("Invalid email or password", 401);
        }

        // Update last login
        $this->collection->updateOne(
            ['_id' => $user['_id']],
            ['$set' => ['lastLogin' => new MongoDB\BSON\UTCDateTime()]]
        );

        $this->startSession($email);
        header('Location: homepage.php');
        exit();
    }

    private function validateSignUpData(): array {
        $required = ['fName', 'lName', 'email', 'password'];
        foreach ($required as $field) {
            if (empty($_POST[$field])) {
                $this->handleError("Missing required field: $field", 400);
            }
        }

        $email = $this->sanitizeEmail($_POST['email']);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->handleError("Invalid email format", 400);
        }

        if (strlen($_POST['password']) < 8) {
            $this->handleError("Password must be at least 8 characters", 400);
        }

        return [
            'firstName' => $this->sanitizeInput($_POST['fName']),
            'lastName' => $this->sanitizeInput($_POST['lName']),
            'email' => $email,
            'password' => $_POST['password']
        ];
    }

    private function startSession(string $email): void {
        session_start();
        session_regenerate_id(true);
        
        $_SESSION = [
            'email' => $email,
            'ip' => $_SERVER['REMOTE_ADDR'],
            'ua' => $_SERVER['HTTP_USER_AGENT'],
            'created' => time()
        ];
    }

    private function sanitizeEmail(string $email): string {
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);
        return trim($email);
    }

    private function sanitizeInput(string $input): string {
        return htmlspecialchars(strip_tags(trim($input)));
    }

    private function handleError(string $message, int $code = 400): void {
        http_response_code($code);
        
        // For API responses
        if (strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false) {
            header('Content-Type: application/json');
            echo json_encode(['error' => $message, 'code' => $code]);
            exit();
        }
        
        // For regular form submissions
        session_start();
        $_SESSION['auth_error'] = $message;
        header('Location: index.php?error=' . urlencode($message));
        exit();
    }
}

// Initialize and handle the request
(new AuthHandler())->handleRequest();
