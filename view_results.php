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
$user = $result->fetch_assoc();
$user_id = $user['id'];

// Fetch user results
$stmt = $conn->prepare("SELECT * FROM user_results WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$results = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
 
  <title>My Results</title>
  <link rel="stylesheet" href="view_results.css" />
</head>

<body>
  <h1 style="text-align: center;">My Results</h1>
  <div style="text-align: center;">
    <?php if ($results->num_rows > 0) { ?>
      <table border="1" style="margin: auto; padding: 10px;">
        <tr>
          <th>Score</th>
          <th>Date</th>
        </tr>
        <?php while ($row = $results->fetch_assoc()) { ?>
          <tr>
            <td><?php echo $row['score_percentage']; ?></td>
            <td><?php echo $row['date']; ?></td>
          </tr>
        <?php } ?>
      </table>
    <?php } else { ?>
      <p>No results found. Start a quiz to add your results!</p>
    <?php } ?>
  </div>
  <div style="text-align: center; margin-top: 20px;">
    <a href="dashboard.php">Back to Dashboard</a>
  </div>
</body>

</html>
