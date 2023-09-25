<?php
session_start();

if (!isset($_SESSION["admin_logged_in"]) || $_SESSION["admin_logged_in"] !== true) {
    header("Location: admin_login.php"); // Redirect if admin is not logged in
    exit;
}

// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "lead_capture_db";

// Create a connection to the database
$conn = new mysqli($servername, $username, $password, $dbname);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST["action"]; // Get the action (approve or reject)
    $withdrawal_id = $_POST["withdrawal_id"]; // Get the withdrawal ID

    // Update the withdrawal status based on the action
    $status = ($action == "approve") ? "Approved" : "Rejected";
    $update_query = "UPDATE withdrawal_requests SET status = '$status' WHERE id = $withdrawal_id";

    if ($conn->query($update_query) === TRUE) {
        echo "Withdrawal request $withdrawal_id has been $status.";
    } else {
        echo "Error updating record: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
        }
        
        h1 {
            color: #45a049;
            text-align: center;
            padding: 20px 0;
        }
        
        .withdrawal-requests {
            margin: 20px auto;
            max-width: 800px;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        
        h2 {
            color: #45a049;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        
        th, td {
            padding: 10px;
            text-align: left;
        }
        
        th {
            background-color: #45a049;
            color: white;
        }
        
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        
        .withdrawal-request {
            border: 1px solid #ccc;
            padding: 10px;
            margin-top: 10px;
            border-radius: 5px;
        }
        
        .withdrawal-request p {
            margin: 5px 0;
        }
        
        .withdrawal-request button {
            background-color: #45a049;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
            margin-right: 5px;
        }
        
        .withdrawal-request button:hover {
            background-color: #368036;
        }

         /* Responsive Styles */
         @media screen and (max-width: 600px) {
            .withdrawal-requests {
                padding: 10px;
            }

            table {
                font-size: 14px;
            }

            th, td {
                padding: 8px;
            }
        }
    </style>
</head>
<body>
    <!-- Add your admin panel interface HTML code here -->
    <h1>Welcome to the Admin Panel</h1>
    
    <div class="withdrawal-requests">
    <h2>Withdrawal Requests</h2>
    <table>
      <thead>
        <tr>
          <th>User_id</th>
          <th>Amount</th>
          <th>Method</th>
          <th>Account</th>
          <th>Status</th>
          <th>Action</th>
        </tr>
      </thead>
     
  <tbody id="withdrawalRequestsTableBody">
       
        <?php
        // Fetch and display withdrawal requests
        $withdrawal_query = "SELECT * FROM withdrawrequest WHERE status = 'Pending'";
        $result = $conn->query($withdrawal_query); // Execute the query
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr class='withdrawal-request'>";
                echo "<td>" . $row["user_id"] . "</td>";
                echo "<td>$" . $row["amount"] . "</td>";
                echo "<td>" . $row["method"] . "</td>";
                echo "<td>" . $row["account"] . "</td>";
                echo "<td>" . $row["status"] . "</td>";
                echo "<td>";
                echo "<form method='post' action='admin.php'>";
                echo "<input type='hidden' name='withdrawal_id' value='" . $row["id"] . "'>";
                echo "<button type='submit' name='action' value='approve'>Approve</button>";
                echo "<button type='submit' name='action' value='reject'>Reject</button>";
                echo "</form>";
                echo "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='6'>No pending withdrawal requests.</td></tr>";
        }
        ?>
            
        </tbody>
    </table>
    </div>
    
    <!-- Include your other admin panel interface elements here -->
</body>
</html>

<?php
$conn->close();
?>
