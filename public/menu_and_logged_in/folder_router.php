<?php
// menu_and_logged_in/folder_router.php
session_start();

// 1. Check if user is logged in
if (!isset($_SESSION['logged_in'])) {
 
<?php
// menu_and_logged_in/folder_router.php
session_start();

if (!isset($_SESSION['logged_in'])) {
    die(json_encode(['status' => 'error', 'message' => 'Not logged in']));
}

$allowed = [
    'fitness' => [
        'path' => 'CR@fitness/index.html',
        'id' => 'fitnessLink' // Match your <a> tag's ID
    ],
    'aitrial' => [
        'path' => 'Aitrainer_free_trial/index.html',
        'id' => 'aiTrainerLink' 
    ]
];

header('Content-Type: application/json');
$request = file_get_contents('php://input');
$data = json_decode($request, true);

if (isset($data['app']) && isset($allowed[$data['app']])) {
    echo json_encode([
        'status' => 'success',
        'redirect' => $allowed[$data['app']]['path']
    ]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid app']);
}
?>