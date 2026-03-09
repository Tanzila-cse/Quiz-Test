<?php
session_start();

// Ensure admin is logged in
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header("Location: admin_login.php");
    exit();
}

// Database connection
$conn = new mysqli("localhost", "root", "", "quiz_website");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch user ID from query string
$user_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : 0;

// Fetch user details
$user_result = $conn->query("SELECT username FROM users WHERE id = $user_id");
$user = $user_result->fetch_assoc();

// Fetch user quiz results
$results = $conn->query("SELECT score_percentage, date FROM user_results WHERE user_id = $user_id");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Quiz Results</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <div class="user-results-container">
        <h2>Quiz Results for <?= $user['username'] ?></h2>
        <table>
            <thead>
                <tr>
                    <th>Score Percentage</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $results->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['score_percentage'] ?>%</td>
                        <td><?= $row['date'] ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <a href="admin_dashboard.php" class="back-button">Back to Dashboard</a>
    </div>
</body>
</html>
