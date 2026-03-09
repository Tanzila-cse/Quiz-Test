<?php
session_start();

// Check if user is an admin
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.html");
    exit();
}

// Database connection
$conn = new mysqli("localhost", "root", "", "quiz_website");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch user ID from URL
$user_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : 0;

// Fetch user quiz records
$result = $conn->query("SELECT * FROM user_results WHERE user_id = $user_id");

// Fetch username
$user_result = $conn->query("SELECT username FROM users WHERE id = $user_id");
$user = $user_result->fetch_assoc();
$username = $user['username'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Records</title>
    <link rel="stylesheet" href="quiz_records.css">
</head>
<body>
    <div class="container">
        <h1>Quiz Records for <?= htmlspecialchars($username) ?></h1>
        <table>
            <thead>
                <tr>
                    <th>Quiz ID</th>
                    <th>Score (%)</th>
                    <th>Date & Time</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= $row['score'] ?>%</td>
                    <td><?= $row['date'] ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <a href="admin.php" class="back-btn">Back to Admin Dashboard</a>
    </div>
</body>
</html>
