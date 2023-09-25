
<?php
// Error reporting settings
error_reporting(E_ALL);
ini_set('display_errors', 1);

// user_balance.php
session_start();

$user_id = ""; // You can set a default value here
// Check if the 'user_id' is set in the session
if (!isset($_SESSION['user_id'])) {
    // You should set 'user_id' when the user logs in or when the session is initiated.
    // For example, if the user logs in, you can set it like this:
    // $_SESSION['user_id'] = $loggedInUserId; // Replace $loggedInUserId with the actual user ID.
    $_SESSION["user_id"] = $user_id;
}

// Database connection parameters
$servername = "localhost"; // Change this if your database server is different
$username = "root"; // Change this to your database username
$password = ""; // Change this to your database password
$dbname = "lead_capture_db"; // Change this to your database name

// Create a connection to the database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form has been submitted
if (isset($_POST['update_balance'])) {
    $user_id = $_SESSION['user_id']; // Get the user ID from the session
    $amountToAdd = 10; // The amount to add to the balance





    // Check if the user already has a balance record
    $check_sql = "SELECT * FROM update_balance WHERE user_id = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("i", $user_id);
    $check_stmt->execute();
    $result = $check_stmt->get_result();

    if ($result->num_rows > 0) {
        // User already has a balance record, update it
        $update_sql = "UPDATE update_balance SET amount = amount + ? WHERE user_id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("di", $amountToAdd, $user_id);


        if ($update_stmt->execute()) {
            $update_stmt->close();
            $check_stmt->close();
            $conn->close();
            // Redirect back to the previous page with a success message
            header("Location: user_balance.php?success=1");
            exit();
        } else {
            echo "Error updating balance: " . $conn->error;
        }
    } else {
        // User doesn't have a balance record, insert a new one
        $insert_sql = "INSERT INTO update_balance (user_id, amount) VALUES (?, ?)";
        $insert_stmt = $conn->prepare($insert_sql);
        $insert_stmt->bind_param("id", $user_id, $amountToAdd);

        if ($insert_stmt->execute()) {
            $insert_stmt->close();
            $check_stmt->close();
            $conn->close();
            // Redirect back to the previous page with a success message
            header("Location: user_balance.php?success=1");
            exit();
        } else {
            echo "Error updating balance: " . $conn->error;
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
<script type="text/javascript" src="https://filestrue.com/script_include.php?id=1535197"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <style>
/* Add this CSS to style the success message */
       .success-message {
               background-color: #4CAF50; /* Green background color */
                 color: #ffffff; /* White text color */
                 padding: 10px; /* Padding around the message */
                 border-radius: 5px; /* Rounded corners */
                margin-top: 10px; /* Add some space above the message */
           }
        
        body {
            font-family: Arial, sans-serif;
            background-color: #222;
            color: #fff;
            text-align: center;
            margin: 0;
            padding: 0;
        }

        .container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        .dialog-box {
            background-color: #333;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
            text-align: center;
        }

        .congratulations {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 20px;
            color: #ffcc00;
        }

        .snake-button {
            background-color: #ffcc00;
            border: none;
            padding: 10px 20px;
            font-size: 18px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .snake-button:hover {
            background-color: #ff9900;
        }


        
        /* Coin animation */
        .coin-effect {
            position: absolute;
            display: none;
            width: 40px;
            height: 40px;
            background-image: url('./img/Golden\ $_0.png'); /* Replace 'coin.png' with your coin image */
            background-size: cover;
            animation: collect-coin 1s linear;
        }

        @keyframes collect-coin {
            0% {
                opacity: 1;
                transform: translate(-50%, -50%) scale(1);
            }
            100% {
                opacity: 0;
                transform: translate(-50%, -150%) scale(0.5);
            }
        }

        a {
    text-decoration: none; /* Remove underline */
    color: black; /* Set text color to black */
}



        /* Media query for smaller screens */
        @media screen and (max-width: 768px) {
            .container {
                padding: 20px;
            }

            .dialog-box {
                padding: 10px;
            }

            .congratulations {
                font-size: 20px;
            }

            .snake-button {
                font-size: 16px;
            }
        }

        #anchor{
            background-color: #ffcc00;
            border: none;
            padding: 1px 9px; /* Adjust the padding as needed */
            font-size: 18px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
            
        }
    </style>
</head>
</head>
<body>

<div class="container">
        <div class="dialog-box">
        <div class="congratulations">Congratulations!</div>
<!-- Form to update the balance -->
<form method="post" action="user_balance.php">
<p id="dynamicText">You've come a long way—claim your $10 bitcoin now, and your earnings will be visible on the dashboard.</p>

       <!-- Other form elements for updating earnings -->
    <input type="submit"  name="update_balance" value="$10" class="snake-button" id="coinButton"><br>
    <br>
    
    <a href="cryptocalc.html"  id="anchor">Explore our cryptocurrency investment calculator.</a>
</form>

<div class="coin-effect" id="coin"></div>

        </div>
    </div>
    <audio id="coinSound" src="./audio/523547__matrixxx__retro-underwater-coin.wav"></audio>

<?php
    // Display a success message if 'success' query parameter is set
    if (isset($_GET['success']) && $_GET['success'] == 1) {
        echo "<p  class='success-message'>Balance updated successfully!</p>";
    }
    ?>

<script>
    function playCoinSound() {
        var audio = document.getElementById("coinSound");
        audio.play();
    }
</script>


<script>
     const coinButton = document.getElementById('coinButton');
const coinEffect = document.getElementById('coin');
const coinSound = document.getElementById('coinSound');

coinButton.addEventListener('click', () => {
    // Play the coin sound
    coinSound.play();

    // Clone the coin effect 10 times
    for (let i = 0; i < 10; i++) {
        const clonedCoin = coinEffect.cloneNode(true);
        document.body.appendChild(clonedCoin);

        setTimeout(() => {
            // Remove the cloned coin after the animation
            clonedCoin.remove();
        }, 1000); // Remove the cloned coin after 1 second

        // Adjust the position of the cloned coin to scatter them
        const randomX = Math.random() * window.innerWidth;
        const randomY = Math.random() * window.innerHeight;
        clonedCoin.style.left = `${randomX}px`;
        clonedCoin.style.top = `${randomY}px`;

        // Trigger the animation
        setTimeout(() => {
            clonedCoin.style.display = 'block';
        }, i * 100); // Delay each clone's animation by 100 milliseconds
    }
});

    </script>


<script>
    // Get the "$10" button element
    const coinButton = document.getElementById('coinButton');

    // Get the paragraph element
    const dynamicText = document.getElementById('dynamicText');

    // Add a click event listener to the button
    coinButton.addEventListener('click', function () {
        // Update the text to "Try more offers and earn"
        dynamicText.textContent = "Try more offers and earn.";
    });
</script>

</body>
</html>