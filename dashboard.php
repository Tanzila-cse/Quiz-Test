<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.html");
    exit();
}

// Database connection
$host = "localhost";
$db_username = "root";
$db_password = "";
$db_name = "quiz_website";

$conn = new mysqli($host, $db_username, $db_password, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$username = $_SESSION['username'];
$stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$user_data = $result->fetch_assoc();

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Dashboard</title>
  <link rel="stylesheet" href="dashboard.css" />
  
</head>

<body>
  <h1>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
  <div class="container">
    <p><a href="start_quiz.php">Start Quiz</a></p>
    <p><a href="view_results.php">My Results</a></p>
    <p><a href="view_profile.php">My Profile</a></p>
    <p><a href="logout.php">Logout</a></p>
  </div>
</body>

</html>
