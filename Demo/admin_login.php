<!-- admin_login.php -->

<?php
session_start();

// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "lead_capture_db";

// Create a connection to the database
$conn = new mysqli($servername, $username, $password, $dbname);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Query to fetch admin credentials from the database
  
    $select_query = "SELECT id, username, password FROM admin WHERE username = ?";

    $stmt = $conn->prepare($select_query);
    $stmt->bind_param("s", $username); // Bind only the username parameter
    $stmt->execute();
    $stmt->bind_result($adminId, $fetchedUsername, $hashedPasswordFromDB); // Fetch all columns
    $stmt->fetch();
    

   
    if ($hashedPasswordFromDB !== null && password_verify($password, $hashedPasswordFromDB)) {
        $_SESSION["admin_logged_in"] = true; // Set the admin session variable
        header("Location: admin.php"); // Redirect to admin panel
        exit;
    } else {
        echo "Invalid credentials.";
    }

    $stmt->close();
}


$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Login</title>
    <link rel="stylesheet" href="adminlogin.css">
</head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Include your CSS and other head elements here -->
</head>
<body>
<div class="container">
    <h1>Admin Login</h1>
    <form method="post" action="admin_login.php">
        <label for="username">Username:</label>
        <input type="text" name="username" id="username" required><br>
        <label for="password">Password:</label>
        <input type="password" name="password" id="password" required><br>
        <button type="submit">Log In</button>
    </form>
    </div>
</body>
</html>
