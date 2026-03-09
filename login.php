<?php
// Database connection details
$host = "localhost";
$db_username = "root";
$db_password = "";
$db_name = "quiz_website";

// Create connection
$conn = new mysqli($host, $db_username, $db_password, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    // Query to match the credentials
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Successful Login
        session_start();
        $_SESSION['username'] = $username;
        header("Location: dashboard.php"); // Redirect to a user dashboard
    } else {
        // Failed Login
        header("Location: login.html?error=Invalid Credentials");
    }

    $stmt->close();
    $conn->close();
} else {
    header("Location: login.html");
}
?>
