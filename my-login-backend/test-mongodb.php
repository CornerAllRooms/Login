<?php
require __DIR__ . '/vendor/autoload.php';

try {
    $client = new MongoDB\Client();
    echo "MongoDB connection successful!";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
