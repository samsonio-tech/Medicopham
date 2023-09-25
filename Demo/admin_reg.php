<?php
// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "lead_capture_db";

// Establish a connection to the database
$conn = new mysqli($servername, $username, $password, $dbname);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Prepare and execute an SQL INSERT statement
    $insert_query = "INSERT INTO admin (username, password) VALUES (?, ?)";
    $stmt = $conn->prepare($insert_query);
    $stmt->bind_param("ss", $username, $hashedPassword);
    $stmt->execute();
    $stmt->close();

    // Display a success message
    echo "Registration successful!";
}

$conn->close();
?>



<!DOCTYPE html>
<html>
<head>
    <title>Admin Registration</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <style>
        
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 0;
            
            font-size: 16px;
        }

        h2 {
            color: #4CAF50;
        }

      
    
   
        form {
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            width: 100%; /* Take full width on small screens */
            margin: 0 auto;
            max-width: 400px; /* Limit width for larger screens */
        }

        label {
            display: block;
            margin-bottom: 5px;
            color: #4CAF50;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }

        @media screen and (max-width: 600px) {
    body {
        font-size: 14px;
    }
}

    </style>
</head>
<body>
    <h2>Admin Registration</h2>
    <form action="admin_reg.php" method="post">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>
        <br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
        <br>
        <button type="submit">Register</button>
    </form>
</body>
</html>
