<?php
// Strict error reporting
declare(strict_types=1);
ini_set('display_errors', 0);
error_reporting(E_ALL);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/../logs/php-errors.log');

// Secure session initialization
session_start([
    'name' => 'SecureSession',
    'cookie_lifetime' => 86400,
    'cookie_secure' => true,
    'cookie_httponly' => true,
    'cookie_samesite' => 'Strict',
    'use_strict_mode' => true,
    'use_only_cookies' => 1
]);

// CSRF token generation
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Environment variables
require __DIR__.'/../vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__.'/..');
$dotenv->load();

// Generate secure app key
$app_key = 'base64:' . base64_encode(random_bytes(32));
?>
try {
    $mongoClient = new MongoDB\Client(
        $_ENV['MONGODB_URI'],
        [
            'tls' => true,
            'retryWrites' => true,
            'w' => 'majority'
        ]
    );
    $collection = $mongoClient->selectCollection('roomie13', 'users');
    $user = $collection->findOne(['email' => $_SESSION['user']['email']]);

    if (!$user) {
        session_destroy();
        header("Location: /index.php?error=User not found");
        exit;
    }
} catch (Exception $e) {
    error_log('Database Error: ' . $e->getMessage());
    http_response_code(500);
    include __DIR__.'/500.html';
    exit;
}
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <link rel="icon" href="logo.png" type="image/x-icon" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta charset="utf-8"/>
    <title>Lobby</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://fonts.cdnfonts.com/css/echelon-2" rel="stylesheet">
    <style type="text/css">
        /* Base Styles */
        body {
            margin: 0;
            font-family: 'Echelon-2', Arial, sans-serif;
            background-color: #44444A;
            color: white;
        }

        /* Header Section Styles */
        .header-container {
            position: relative;
            width: 100%;
            height: 280px; /* 30% smaller than original 400px */
            overflow: hidden;
        }

        .header-background {
            width: 100%;
            height: 100%;
            background-size: cover;
            background-position: center;
            background-image: url('/assets'); /* Replace with your image path */
        }

        .header-background::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 100px;
            background: linear-gradient(to bottom, transparent, #44444A);
        }

        .header-title {
            position: absolute;
            bottom: 30px;
            left: 40px;
            text-align: left;
        }

        .header-title h1 {
            color: #FF5000; /* New orange color */
            font-size: 3.5rem;
            font-weight: bold;
            margin: 0;
            font-family: 'Horizon', sans-serif;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* Welcome Message Styles */
        .welcome-container {
            text-align: center;
            padding: 50px 15%;
            opacity: 0;
            animation: fadeIn 2s ease-in-out forwards;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .welcome-message {
            font-size: 1.8rem;
            font-weight: bold;
            color: white;
            line-height: 1.6;
            font-family: 'Horizon', sans-serif;
        }

        /* Homepage Button Styles */
        .homepage-btn-container {
            text-align: center;
            margin: 30px 0;
        }

        .homepage-btn {
            display: inline-block;
            padding: 15px 40px;
            background-color: white;
            color: #FF5000;
            text-decoration: none;
            font-size: 1.2rem;
            font-weight: bold;
            border: none;
            border-radius: 30px; /* Sleeker rounded shape */
            position: relative;
            overflow: hidden;
            transition: all 0.3s;
            font-family: 'Horizon', sans-serif;
            text-transform: uppercase;
            letter-spacing: 1px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .homepage-btn:hover {
            background-color: #f0f0f0; /* Light grey on hover */
            transform: translateY(-3px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }

        /* Background fade effect */
        .content-wrapper {
            background-color: #44444A;
            position: relative;
            z-index: 1;
        }
    </style>
</head>
<body>
    <!-- Header Section with Background Image -->
    <div class="header-container">
        <div class="header-background"></div>
        <div class="header-title">
            <h1>Lobby</h1>
        </div>
    </div>

    <!-- Content Wrapper with #44444A background -->
    <div class="content-wrapper">
        <!-- Welcome Message Section -->
        <div class="welcome-container">
            <p class="welcome-message">
                Hello <?php
                if(isset($_SESSION['email'])) {
                    $email = $_SESSION['email'];
                    $filter = ['email' => $email];
                    $options = [];
                    $query = $collection->find($filter, $options);

                    foreach ($query as $document) {
                        echo $document['firstName'].' '.$document['lastName'];
                    }
                }
                ?>,
                enjoy fitness made easier. We are motivated to see quick results as much as you do
            </p>
        </div>

        <!-- Homepage Button -->
        <div class="homepage-btn-container">
            <a href="/menu_and_logged_in/log_out.html" class="homepage-btn">HOMEPAGE</a>
        </div>
    </div>

    <!-- Rest of your existing content -->
    <!-- ... -->
</body>
</html>