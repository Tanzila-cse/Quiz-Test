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
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    // Check if the username already exists
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        echo json_encode(["status" => false, "message" => "Username already exists."]);
        $stmt->close();
        $conn->close();
        exit();
    }

    // Check if the email already exists
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        echo json_encode(["status" => false, "message" => "Email already exists."]);
        $stmt->close();
        $conn->close();
        exit();
    }

    // Check if password has already been used by another account
    $stmt = $conn->prepare("SELECT * FROM users WHERE password = ?");
    $stmt->bind_param("s", $password);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        echo json_encode(["status" => false, "message" => "Password already used by another account."]);
        $stmt->close();
        $conn->close();
        exit();
    }

    // Insert user into database
    $stmt = $conn->prepare("INSERT INTO users (name, email, username, password) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $email, $username, $password);

    if ($stmt->execute()) {
        echo json_encode(["status" => true, "message" => "Account successfully created!"]);
    } else {
        echo json_encode(["status" => false, "message" => "Error: Unable to create account."]);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(["status" => false, "message" => "Invalid request."]);
}
?>
