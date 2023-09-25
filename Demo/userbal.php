<!DOCTYPE html>
<html>
<head>
    <title>Update User Balance</title>
</head>
<body>
    <h1>User Balance:</h1>
    <?php
    // Connect to the database
    $servername = "localhost"; // Change this if your database server is different
       $username = "root"; // Change this to your database username
       $password = ""; // Change this to your database password
       $dbname = "user_balance"; // Change this to your database name

    $db = new mysqli($servername, $username, $password, $dbname);

    if ($db->connect_error) {
        die('Connection failed: ' . $db->connect_error);
    }

    // Get the user's current balance
    $sql = "SELECT balance FROM users WHERE username = 'example_user'";
    $result = $db->query($sql);
  
    if ($result) {
        $row = $result->fetch_assoc();
        if ($row) {
            $currentBalance = $row['balance'];
        } else {
            // Handle the case where the user 'example_user' was not found
            $currentBalance = 0.00; // Set a default balance or display an error message
        }
    } else {
        // Handle the case where the SQL query failed
        $currentBalance = 0.00; // Set a default balance or display an error message
    }
    

    // Define $newBalance with a default value
    $newBalance = $currentBalance;

    // Process the button click
    if (isset($_POST['update_balance'])) {
        // Update the balance
        $earningsToAdd = 10.00;
        $newEarnings = $currentBalance + $earningsToAdd;


        // Insert earnings into the earnings table
        $insertSql = "INSERT INTO users (username, balance) VALUES ('example_user', ?)";
        $stmt = $db->prepare($insertSql);
        $stmt->bind_param("d", $earningsToAdd); // "d" represents a double (for a numeric value)
        $stmt->execute();

        $newBalance = $currentBalance + 10;
        
         // Update the balance in the database
           $updateSql = "UPDATE users SET balance = ? WHERE username = 'example_user'";
           $stmt = $db->prepare($updateSql);
            $stmt->bind_param("d", $newBalance); // Bind $newBalance to the placeholder in the SQL query
             $stmt->execute();

    }
    

    // Close the database connection
    $db->close();
    ?>

    <form method="post">
        <?php
        // Display the current balance in the input field
       
        // Display the updated balance
        echo "<p>Balance updated! New Balance: $" . number_format($newBalance, 2) . "</p>";
        ?>
        <input type="submit" name="update_balance" value="Update Balance by $10">
    </form>
</body>
</html>
