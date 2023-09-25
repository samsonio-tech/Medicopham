<?php
session_start();


// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "lead_capture_db";

// Create a connection to the database
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}



$user_id = @$_SESSION['user_id'];



if ($_SERVER["REQUEST_METHOD"] == "POST") {
  //$user_id = $_POST['user_id'];
  $withdrawalAmount = $_POST['withdrawalAmount'];
  $withdrawalMethod = $_POST['withdrawalMethod'];
  $withdrawalAccount = $_POST['withdrawalAccount'];



  // Insert the form data into the "withdrawrequest" table
  $sql = "INSERT INTO withdrawrequest (user_id, amount, method, account, status)
  VALUES (?, ?, ?, ?, 'Pending')"; // Set the status to 'Pending' by default


// Create a prepared statement
$stmt = $conn->prepare($sql);

// Bind parameters and execute
$stmt->bind_param("ssss", $user_id, $withdrawalAmount, $withdrawalMethod, $withdrawalAccount);


if ($stmt->execute()) {
    // Success
    $_SESSION["withdrawal_success"] = true;




    // Deduct the withdrawal amount from user_amount
    $update_balance_query = "UPDATE update_balance SET amount = amount - ? WHERE user_id = ?";
    $stmt_update_balance = $conn->prepare($update_balance_query);
    $stmt_update_balance->bind_param("di", $withdrawalAmount, $user_id);

    if ($stmt_update_balance->execute()) {
      // Successfully deducted the withdrawal amount
      // Now, fetch the updated user_amount
      $select_balance_query = "SELECT amount FROM update_balance WHERE user_id = ?";
      $stmt_balance = $conn->prepare($select_balance_query); // Define $stmt_balance here
      $stmt_balance->bind_param("i", $user_id);

      if ($stmt_balance->execute()) {
        $stmt_balance->bind_result($user_amount);
        $stmt_balance->fetch();
      } else {
        // Error handling for fetching user_amount
        echo "Error fetching user balance: " . $stmt_balance->error;
      }

      $stmt_balance->close(); // Close the $stmt_balance statement
    } else {
      // Error handling for updating user_amount
      echo "Error updating user balance: " . $stmt_update_balance->error;
    }


    header("Location: withdrawal.php"); // Redirect to a success page
    exit();
} else {
    // Error handling
    echo "Error: " . $stmt->error;
}
$stmt->close();
} 
   // Query to fetch user_amount based on user_id
   $select_balance_query = "SELECT amount FROM update_balance WHERE user_id = ?";
   $stmt_balance = $conn->prepare($select_balance_query);
   $stmt_balance->bind_param("i", $user_id);
   $stmt_balance->execute();
   $stmt_balance->bind_result($user_amount);

   // Fetch the user_amount
if ($stmt_balance->fetch()) {
  $stmt_balance->close(); // Close the statement
} else {
  echo "User not found.";
  exit;
}

?>


<!DOCTYPE html>
<html>
<head>
  <link rel="stylesheet" href="withdraw.css">
  

  <meta name="viewport" content="width=device-width, initial-scale=1">
  
  <title>Withdrawal Request</title>

</head>

<body>
<div class="container">
  <div class="alert alert-warning" role="alert">
      <!-- Add the alert notice here -->
      <!-- Inside the <div class="container"> element -->
<?php
if (isset($_SESSION["withdrawal_success"]) && $_SESSION["withdrawal_success"]) {
    echo '<div class="alert alert-success" role="alert">';
    echo 'Your withdrawal request has been submitted and is being reviewed.';
    echo '</div>';
    // Reset the session variable
    $_SESSION["withdrawal_success"] = false;
}
?>

<p>Notice: New participants must reach a minimum balance of $100 before their withdrawal requests can be processed. Please read before proceeding.</p>
  </div>

    <h1>Withdrawal Request</h1>
    <p>This is the withdrawal request page. You can submit a withdrawal request from your earnings dashboard here.</p>
    <div class="dashboard">
    <p>User ID: <?php echo $user_id; ?></p>
    <p>Amount: $<?php echo $user_amount; ?></p>

   
      <!-- Withdrawal request form goes here -->

      <form id="withdrawalForm" action="withdrawal.php" method="POST">

             <!-- Add a hidden input field for user_id -->
    <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">

        <div class="form-group">
          <label for="withdrawalAmount">Withdrawal Amount</label>
          <input type="text" id="withdrawalAmount" name="withdrawalAmount" placeholder="Enter withdrawal amount" required>
          <div class="progress-bar-container">
            <div id="progressBar" class="progress-bar"></div>
          </div>
        </div>
        <div class="form-group custom-dropdown">
          <label for="withdrawalMethod">Withdrawal Method</label>
          <div class="custom-input">
            <input type="text" id="withdrawalMethod" name="withdrawalMethod" placeholder="Select withdrawal method" required>
            <div class="dropdown">
              <div class="dropdown-option" data-value="PayPal">PayPal</div>
              <div class="dropdown-option" data-value="Bank Transfer">Bank Transfer</div>
              <div class="dropdown-option" data-value="Trust wallet">Trust wallet</div>
              <!-- Add more withdrawal methods as needed -->
            </div>
          </div>
        </div>

        <div class="form-group custom-dropdown">
          <label for="withdrawalAccount">Account Details</label>
          <input type="text" id="withdrawalAccount" name="withdrawalAccount" placeholder="Enter account details" required>
        </div>
     

    <button type="submit" class="button">Submit Request</button>
</form>

  
   

      <div class="withdrawal-history">
        <h2>Withdrawal History</h2>
        <table id="withdrawalHistoryTable">
            <thead>
              <tr>
                <th>Date</th>
                <th>Amount</th>
                <th>Method</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>2023-07-01</td>
                <td>$50.00</td>
                <td>PayPal</td>
                <td>Completed</td>
              </tr>
              <tr>
                <td>2023-06-15</td>
                <td>$75.00</td>
                <td>Bank Transfer</td>
                <td>Completed</td>
              </tr>
              <tr>
                <td>2023-05-20</td>
                <td>$30.00</td>
                <td>PayPal</td>
                <td>Completed</td>
              </tr>
              <tr>
                <td>2023-04-10</td>
                <td>$45.00</td>
                <td>PayPal</td>
                <td>Completed</td>
              </tr>
              <tr>
                <td>2023-03-05</td>
                <td>$60.00</td>
                <td>Bank Transfer</td>
                <td>Completed</td>
              </tr>
              <tr>
                <td>2023-02-15</td>
                <td>$25.00</td>
                <td>PayPal</td>
                <td>Completed</td>
              </tr>
              <tr>
                <td>2023-01-20</td>
                <td>$40.00</td>
                <td>PayPal</td>
                <td>Completed</td>
              </tr>
              <tr>
                <td>2022-12-10</td>
                <td>$55.00</td>
                <td>Bank Transfer</td>
                <td>Completed</td>
              </tr>
              <tr>
                <td>2022-11-05</td>
                <td>$70.00</td>
                <td>PayPal</td>
                <td>Completed</td>
              </tr>
              <tr>
                <td>2022-10-15</td>
                <td>$35.00</td>
                <td>PayPal</td>
                <td>Completed</td>
              </tr>
              <tr>
                <td>2022-09-20</td>
                <td>$50.00</td>
                <td>Bank Transfer</td>
                <td>Completed</td>
              </tr>
              <tr>
                <td>2022-08-10</td>
                <td>$65.00</td>
                <td>PayPal</td>
                <td>Completed</td>
              </tr>
              <tr>
                <td>2022-07-05</td>
                <td>$80.00</td>
                <td>Bank Transfer</td>
                <td>Completed</td>
              </tr>
              <tr>
                <td>2022-06-15</td>
                <td>$45.00</td>
                <td>PayPal</td>
                <td>Completed</td>
              </tr>
              <tr>
                <td>2022-05-20</td>
                <td>$60.00</td>
                <td>PayPal</td>
                <td>Completed</td>
              </tr>
              <tr>
                <td>2022-04-10</td>
                <td>$75.00</td>
                <td>Bank Transfer</td>
                <td>Completed</td>
              </tr>
              <tr>
                <td>2022-03-05</td>
                <td>$90.00</td>
                <td>PayPal</td>
                <td>Completed</td>
              </tr>
              <tr>
                <td>2022-02-15</td>
                <td>$55.00</td>
                <td>PayPal</td>
                <td>Completed</td>
              </tr>
              <tr>
                <td>2022-01-20</td>
                <td>$70.00</td>
                <td>Bank Transfer</td>
                <td>Completed</td>
              </tr>
              <tr>
                <td>2021-12-10</td>
                <td>$85.00</td>
                <td>PayPal</td>
                <td>Completed</td>
              </tr>
            </tbody>
          </table>
          
    </div>


   

    </body>
</html>
