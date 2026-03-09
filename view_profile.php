<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.html");
    exit();
}

// Database connection
$mysqli = new mysqli("localhost", "root", "", "quiz_website");

if ($mysqli->connect_error) {
    die("Connection Failed: " . $mysqli->connect_error);
}

// Fetch user data based on the session username
$stmt = $mysqli->prepare("SELECT name, email, username, password FROM users WHERE username = ?");
$stmt->bind_param("s", $_SESSION['username']);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    header("Location: login.html");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>My Profile</title>
  <link rel="stylesheet" href="view_profile.css" />
</head>
<body>
  <div class="container">
    <h1>My Profile</h1>
    <div>
      <p><strong>Name:</strong> <?php echo htmlspecialchars($user['name']); ?></p>
      <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
      <p><strong>Username:</strong> <?php echo htmlspecialchars($user['username']); ?></p>
      <p><strong>Password:</strong> <?php echo htmlspecialchars($user['password']); ?></p>
    </div>
    <a href="dashboard.php">Back to Dashboard</a>
  </div>
</body>
</html>

