<?php
session_start();

// Hardcoded admin password
define('ADMIN_PASSWORD', 'admin123');

// Check if admin is already logged in
if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true) {
    header("Location: admin_dashboard.php");
    exit();
}

// Handle login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = $_POST['password'];

    if ($password === ADMIN_PASSWORD) {
        $_SESSION['is_admin'] = true;
        header("Location: admin_dashboard.php");
        exit();
    } else {
        $error = "Invalid admin password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <div class="admin-login-container">
        <h2>Admin Login</h2>
        <?php if (isset($error)): ?>
            <p class="error-message"><?= $error ?></p>
        <?php endif; ?>
        <form method="POST">
            <label for="password">Enter Admin Password:</label>
            <br><br>
            <input type="password" name="password" id="password" required>
            <button type="submit">Login</button>
            <a href="index.html">Home</a>
        </form>
    </div>
</body>
</html>
