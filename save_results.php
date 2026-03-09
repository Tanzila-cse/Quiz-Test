<?php
header('Content-Type: application/json');
$data = json_decode(file_get_contents("php://input"), true);

session_start();
if (!isset($_SESSION['username'])) {
    echo json_encode(['error' => 'User not logged in']);
    exit();
}

// Database connection
$conn = new mysqli("localhost", "root", "", "quiz_website");

if ($conn->connect_error) {
    echo json_encode(['error' => 'Connection Error']);
    exit();
}

$user_id = $data['user_id'];
$percentage = $data['percentage'];

$stmt = $conn->prepare("INSERT INTO user_results (user_id, score_percentage) VALUES (?, ?)");
$stmt->bind_param("ii", $user_id, $percentage);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['error' => 'Query failed']);
}

$stmt->close();
$conn->close();
?>
