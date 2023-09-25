<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);


// Database connection parameters
$servername = "localhost"; // Change this if your database server is different
$username = "root"; // Change this to your database username
$password = ""; // Change this to your database password
$dbname = "lead_capture_db"; // Change this to your database name

// Create a connection to the database
$conn = new mysqli($servername, $username, $password, $dbname);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate the email address
    if (filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
        // Generate a random user ID
        $user_id = bin2hex(random_bytes(5));

        // Prepare the SQL statement to insert the email into the database
        $stmt = $conn->prepare("INSERT INTO leads (email, avatar, user_id) VALUES (?, ?, ?)");
    
        // Declare the $avatar variable before the if condition
        $avatar = "";

        // Check if an avatar picture was uploaded
        if ($_FILES["avatar"]["error"] === 0) {
            $targetDir = "avatars/"; // Folder where you want to store the avatars

            // Check if the uploaded file is an image (you can add more image type checks if required)
            $imageFileType = strtolower(pathinfo($_FILES["avatar"]["name"], PATHINFO_EXTENSION));
            $allowedExtensions = array("jpg", "jpeg", "png", "gif");
            if (!in_array($imageFileType, $allowedExtensions)) {
                die("Error: Only JPG, JPEG, PNG, and GIF files are allowed.");
            }

            // Create a unique avatar name to avoid clashes
            $avatar = $targetDir . $user_id . "." . $imageFileType;

            // Move the uploaded file to the target directory
            if (!move_uploaded_file($_FILES["avatar"]["tmp_name"], $avatar)) {
                die("Error uploading avatar.");
            }
      
    // Update the profile picture display with the new uploaded picture
    echo '<img src="' . $avatar . '" alt="Profile Picture" class="profile-image">';
  } else {
      // If no avatar was uploaded or an error occurred, use a default avatar path
      $avatar = "default-avatar.png";
  }

        // Bind parameters and execute the SQL statement
        $stmt->bind_param("sss", $_POST["email"], $avatar, $user_id);
        if ($stmt->execute()) {
          // Retrieve the user ID from the database
          $user_id = $conn->insert_id;
          
          // Store the user ID in a session variable
          session_start();
          $_SESSION["user_id"] = $user_id;
      
          // Display successful information to the user
          echo '<div class="alertme">
               Thank you for signing up! Your user ID is: ' . $user_id . '. You can participate in this contest, and the gift card code will be sent to your Gmail address once you complete the gift card offers.
             </div>';
      } else {
          echo "Error: " . $stmt->error;
      }
      
    } else {
      // Log the email validation error to a file for debugging
      error_log("Invalid Email: " . $_POST["email"]);
      echo "Invalid email format.";
  }

// Close the database connection
$conn->close();
 

// ... Existing PHP code ...


  }
 
?>


   <!-- Profile picture section -->
   <div class="profile-picture">
    <input type="file" accept="image/*" name="avatar" id="avatarInput" style="display: none;">
    <label for="avatarInput">
      <?php
      if (isset($_FILES["avatar"]) && $_FILES["avatar"]["error"] === 0) {
        echo '<img src="' . $avatar . '" alt="Profile Picture" class="profile-image">';
      } else {
        echo '<img src="./img/avatar.png" alt="Default Avatar" class="profile-image">';
      }
      ?>
      <i class="fas fa-camera"></i> <!-- Add the photo icon here -->
    </label>
  </div>