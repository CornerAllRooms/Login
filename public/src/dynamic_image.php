<?php
session_start();
require_once 'decryption_functions.php'; // Your decryption logic

$allowed_images = [
    'secure_dashboard' => 'encrypted_dashboard.img' // Your encrypted file
];

$img_id = $_GET['img'] ?? '';
if (!array_key_exists($img_id, $allowed_images)) {
    die("Invalid image request");
}

// Decrypt on-demand (example)
$decrypted = decrypt_file($allowed_images[$img_id]);
header('Content-Type: image/png');
echo $decrypted;

// Optional: Log access for security
file_put_contents('image_access.log', date('Y-m-d H:i:s')." - $img_id\n", FILE_APPEND);
?>