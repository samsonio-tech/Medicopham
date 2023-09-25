

<?php
// Assuming you have a database connection
// Replace DB_HOST, DB_USER, DB_PASSWORD, and DB_NAME with your database credentials
$conn = mysqli_connect("DB_HOST", "DB_USER", "DB_PASSWORD", "DB_NAME");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Initialize session
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the form is submitted
    if (isset($_POST['email'])) {
        // Get user input (email)
        $email = $_POST['email'];

        // Insert a new user with an initial balance of $0
        $insertQuery = "INSERT INTO users (email, balance) VALUES (?, 0)";
        $stmt = mysqli_prepare($conn, $insertQuery);
        mysqli_stmt_bind_param($stmt, "s", $email);

        if (mysqli_stmt_execute($stmt)) {
            // Successfully inserted new user
            $userId = mysqli_insert_id($conn); // Get the user's ID

            // Store the user ID in a session
            $_SESSION['userId'] = $userId;

            // Redirect to the same page
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        } else {
            echo "Error creating user: " . mysqli_error($conn);
        }
    } elseif (isset($_POST['update_balance'])) {
        // Check if the update balance button is clicked
        $userId = $_SESSION['userId']; // Get the user's ID
        // Retrieve the current balance
        $selectQuery = "SELECT balance FROM users WHERE user_id = ?";
        $stmt = mysqli_prepare($conn, $selectQuery);
        mysqli_stmt_bind_param($stmt, "i", $userId);

        if (mysqli_stmt_execute($stmt)) {
            $result = mysqli_stmt_get_result($stmt);
            $row = mysqli_fetch_assoc($result);
            $currentBalance = $row['balance'];

            // Update the balance by $10
            $newBalance = $currentBalance + 10;
            $updateQuery = "UPDATE users SET balance = ? WHERE user_id = ?";
            $stmt = mysqli_prepare($conn, $updateQuery);
            mysqli_stmt_bind_param($stmt, "ii", $newBalance, $userId);

            if (mysqli_stmt_execute($stmt)) {
                // Successfully updated balance
                header("Location: " . $_SERVER['PHP_SELF']);
                exit();
            } else {
                echo "Error updating balance: " . mysqli_error($conn);
            }
        } else {
            echo "Error retrieving balance: " . mysqli_error($conn);
        }
    }
}

mysqli_close($conn);
?>


<!DOCTYPE html>
<html>
<head>
    <title>Email Lead Capture</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
<div class="container">
    <h1>Email Lead Capture</h1>
    <form method="post">
        <input type="email" name="email" placeholder="Enter your Gmail" required>
        <input type="submit" value="Submit">
    </form>
    <div class="dashboard">
    <div id="balance">
        <?php
        if (isset($_SESSION['userId'])) {
            // Display the user's balance
            $userId = $_SESSION['userId'];
            $selectQuery = "SELECT balance FROM users WHERE user_id = ?";
            $stmt = mysqli_prepare($conn, $selectQuery);
            mysqli_stmt_bind_param($stmt, "i", $userId);

            if (mysqli_stmt_execute($stmt)) {
                $result = mysqli_stmt_get_result($stmt);
                $row = mysqli_fetch_assoc($result);
                echo "Balance: $" . $row['balance'];
            } else {
                echo "Error retrieving balance: " . mysqli_error($conn);
            }
        }
        ?>
    </div>
    <form method="post">
        <input type="submit" name="update_balance" value="Update Balance">
    </form>
</div>
<script src="balance.js"></script>
</body>
</html>