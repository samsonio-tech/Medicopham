<?php
// Error reporting settings
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start a session
session_start();


// Initialize user_amount with a default value
//$user_amount = 0;


// Database connection parameters
$servername = "localhost"; // Change this if your database server is different
$username = "root"; // Change this to your database username
$password = ""; // Change this to your database password
$dbname = "lead_capture_db"; // Change this to your database name

// Create a connection to the database
$conn = new mysqli($servername, $username, $password, $dbname);
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Validate the email address
  if (!empty($_POST["email"]) && filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
      // Check if the email already exists in the database
      $checkEmailQuery = "SELECT COUNT(*) FROM leads WHERE email = ?";
      $stmt = $conn->prepare($checkEmailQuery);
      $stmt->bind_param("s", $_POST["email"]);
      $stmt->execute();
      $stmt->bind_result($emailCount);
      $stmt->fetch();
      $stmt->close();

      if ($emailCount > 0) {
          echo "This email address is already registered.";
          exit; // Stop further execution
      }
  } else {
      echo "Invalid email address.";
      exit; // Stop further execution
  }
  


    // Generate a random user ID
    $user_id = bin2hex(random_bytes(5));

    // Prepare and execute the SQL statement without specifying the user_id
    $stmt = $conn->prepare("INSERT INTO leads (user_id, email) VALUES (?, ?)");

    // Bind the values to the placeholders
    $stmt->bind_param("ss", $user_id, $_POST["email"]);


// Assuming $user_amount should be initialized to 0
$user_amount = 0;

        if ($stmt->execute()) {
            $_SESSION["user_id"] = $user_id; // Set 'user_id' in the session
            $_SESSION["user_amount"] =  $user_amount;; // Set 'user_balance' in the session

      // Display successful information to the user
      echo '<div class="alertme">
      Thank you for signing up! Your user ID is: ' . $user_id . '. You can participate in this contest.
      <h3>Gift Card Offer: Earn Rewards Sent to Your Gmail for Completing Missions!</h3>
      <p>Complete 1 mission and receive a gift card code delivered to your Gmail. </p>
      <p> THE MORE MISSION YOU COMPLETE</p>
      <p> THE MORE YOU EARN</p>
      </div>';
  } else {
      echo "Error during execution: " . $stmt->error;
  }
}

// Initialize $user_id and $user_amount with default values
$user_id = 0;
$user_amount = 0; // Set the default amount to 0

// Check if 'user_id' is set in the session
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id']; // Get the user ID from the session

    // Retrieve the user's amount based on their user_id
    $get_amount_sql = "SELECT amount FROM update_balance WHERE user_id = ?";
    $get_amount_stmt = $conn->prepare($get_amount_sql);
    $get_amount_stmt->bind_param("i", $user_id);
    $get_amount_stmt->execute();
    $result = $get_amount_stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $user_amount = $row['amount'];
    } else {
        $user_amount = 0; // Default amount if no record found
    }

    $get_amount_stmt->close();
}



// Close the database connection
$conn->close();
?>

     




<!DOCTYPE html>
<html>
<head>


<link rel="stylesheet" href="style.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flipclock/0.7.8/flipclock.min.css">
 
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha512-njn1/YIu72rurXJu5kji+AkPwoG3doKq4ZDEm6S4hXj2hpo9MPU7UML2TVPg1AXdZuspxL70W2vETx3k2buWw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-X6JGF8182E"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-X6JGF8182E');
</script>
  
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Claim Your Gift Card Now! Participate in a quick survey and earn Bitcoin rewards. Watch our video explainer and read testimonials from satisfied users.">

  <title>EARN FROM HOME</title>

</head>
<body>
 



  
<div class="container">
<div class="navbar">
        <div class="menu-icon" id="menu-icon">&#9776;</div>
        <ul class="menu" id="menu">
        <li><a href="admin.php" class="disabled-link">Admin</a></li>
            <li><a href="services.html">Services</a></li>
            <li><a href="about.html">About</a></li>
          
        </ul>
        <div class="close-icon" id="close-icon">&times;</div>
    </div>
    <script src="script.js"></script>

    <div class="lead-capture">
  <h2>Claim Your Gift Card Now!</h2>
  <p>Enter your email to get started:</p>
  <form id="leadCaptureForm" method="post" action="index.php" enctype="multipart/form-data">
    <input type="email" name="email" placeholder="Your Email" required id="email">
    
    
    <button type="submit" class="button">Get Started</button>
</form>

</div>

    
     <div class="alert">
      <p>🔥 Participate in a Quick Survey and Gain Access to $30 Worth of Bitcoin! each into your wallet 🔥 EXPLORE OUR PROMOTIONS TO CLAIM THE OFFER.</p>
    </div>
    <h1>Bitcoin Offer: Earn Rewards for Completing Missions!</h1>
    <ul>
        <li>Complete 1 mission and earn $10 in Bitcoin.</li>
        <li>Complete 10 missions and earn $100 in Bitcoin.</li>
    </ul>
    <!-- Video Explainer Section -->
     <div class="video-explainer">
      <h2>Watch Our Video Explainer</h2>
      <div class="video-container">
        <!-- Replace with YouTube or Vimeo embed code -->
        <iframe width="560" height="315" src="https://www.youtube.com/embed/igkpyrO2ydo" frameborder="0" allowfullscreen></iframe>
      </div>
    </div>
    
      <!-- Dashboard -->
    <div class="dashboard">
      <h2>Balance</h2>
      
      <p>User ID: <?php echo $user_id; ?></p>
    <p>Amount: $<?php echo $user_amount; ?></p>

     <p><a href="user_balance.php" class="button">user_balance</a></p>
  <p><a href="withdrawal.php" class="button">Withdraw Earnings</a></p>
</div>
    <!-- Add any additional content or images here -->
    <img src="./img/onpage.jfif" alt="Offer Image" class="responsive-image" height="600">
   
   
  
   
    <!-- Pop-up HTML -->
       <div class="overlay" id="popupOverlay">
        <div class="popup" id="popup">
          
          <h2>Bitcoin Offers</h2>
         
          <div class="additional-offers">
            <div class="additional-offer">
            <img src="./img/crypto1.gif" alt="Bitcoin Offer 3">
              <h3>Claim offer now</h3>
              <p> Experience Unmatched Shopping Delights with Bitcoin Rewards!
                Unlock Your Ticket to Shopping Paradise! Earn Exclusive Bitcoin Rewards by Participating in Our Survey!</p>
                <a href="user_balance.php" >Get Offer</a>

            </div>

            <div class="additional-offer">
            <img src="./img/crypto2.gif" alt="Bitcoin Offer 3">
              <h3>Claim offer now</h3>
              <p> Embark on the Bitcoin Revolution Today!
                Revamp Your Financial Future with Bitcoin - Enter Now and Embrace the Future of Digital Currency! </p>
                <a href="user_balance.php" >Get Offer</a>
            </div>
            <div class="additional-offer">
            <img src="./img/btc8.gif" alt="Bitcoin Offer 3">
              <h3>Claim offer now</h3>
              <p>Uncover the Power of Bitcoin and Embrace a World of Opportunities!
                Prepare for Rewards! Participate in our Survey for a Chance to Win Exciting Prizes!</p>
                <a href="user_balance.php" >Get Offer</a>
            </div>

            <div class="additional-offer">
              <img src="./img/crypto5.gif" alt="Bitcoin Offer 3">
              <h3>Claim offer now</h3>
              <p>Bitcoin Awaits Your Exploration - Participate in Our Survey and Secure a Chance to Win!</p>
              <a href="https://filestrue.com/1513875" class="button get-offer"  data-amount="10">Get Offer</a>
              
            </div>
            <div class="additional-offer">
              <img src="./img/btc1.gif" alt="Bitcoin Offer 3">
              <h3>Claim offer now</h3>
              <p>Elevate Your Prospects with Bitcoin - Take our Survey, Enter to Win, and Embrace the Future!</p>
              <a href="user_balance.php" >Get Offer</a>
              
            </div>

            <div class="additional-offer">
              <img src="./img/btc2.gif" alt="Bitcoin Offer 3">
              <h3>Claim offer now</h3>
              <p>Uncover the Power of Bitcoin and Embrace a World of Opportunities!
                Prepare for Rewards! Participate in our Survey for a Chance to Win Exciting Prizes!</p>
                <a href="user_balance.php" >Get Offer</a>
              
            </div>

            <div class="additional-offer">
              <img src="./img/btc3.gif" alt="Bitcoin Offer 3">
              <h3>Claim offer now</h3>
              <p>Your Bitcoin Journey Starts Now - Participate in Our Survey, Grab Rewards, and Ignite Change!</p>
              <a href="user_balance.php" >Get Offer</a>
              
            </div>

            <div class="additional-offer">
              <img src="./img/btc4.gif" alt="Bitcoin Offer 3">
              <h3>Claim offer now</h3>
              <p>Embrace the Bitcoin Wave: Your Ticket to Empowerment and Prosperity Awaits!</p>
              <a href="user_balance.php" >Get Offer</a>
              
            </div>

            <div class="additional-offer">
              <img src="./img/btc5.gif" alt="Bitcoin Offer 3">
              <h3>Claim offer now</h3>
              <p>Unleash the Potential of Bitcoin - Transform Your Future and Claim Your Rewards!</p>
              <a href="user_balance.php" >Get Offer</a>
              
            </div>

            <div class="additional-offer">
              <img src="./img/btc6.gif" alt="Bitcoin Offer 3">
              <h3>Claim offer now</h3>
              <p>Join the Bitcoin Movement: Survey Participation = Your Shot at Winning Incredible Prizes!</p>
              <a href="user_balance.php" >Get Offer</a>
              
            </div>
          </div>
        </div>
      </div>

     

      
      <div class="claim-section">
        <h2>Claim Your Gift Card</h2>
        <p>Do a simple survey and earn cool cash</p>
 
      </div>

         <!-- Add this section to your existing HTML code -->
<section class="virtual-gift-card-section">
  <h2> Gift Card Offers</h2>
  <div class="gift-card-offers">
    <div class="gift-card-offer">
     
 <a href="giftcardbox.php" >
        <img src="./img/amazon1.png" alt="Gift Card 1">
        <p class="gift-card-info">$5 Amazon Gift Card</p>
        <p class="gift-card-action">Click to Claim</p>
      </a>
      <p class="gift-card-category blue-background">1 point: $5</p>
    </div>
    <div class="gift-card-offer">
    <a href="giftcardbox.php" >
        <img src="./img/amazon2.png" alt="Gift Card 2">
        <p class="gift-card-info">$10 Amazon Gift Card</p>
        <p class="gift-card-action">Click to Claim</p>
      </a>
      <p class="gift-card-category blue-background">10 points: $10</p>
    </div>

    <div class="gift-card-offer">
    <a href="giftcardbox.php" >
        <img src="./img/amazon3.png" alt="Gift Card 2">
        <p class="gift-card-info">$25 Amazon Gift Card</p>
        <p class="gift-card-action">Click to Claim</p>
      </a>
      <p class="gift-card-category blue-background">Category: Email/Zip Submit</p>
    </div>

    <div class="gift-card-offer">
    <a href="giftcardbox.php" >
        <img src="./img/amazon4.png" alt="Gift Card 2">
        <p class="gift-card-info">$50 Amazon Gift Card</p>
        <p class="gift-card-action">Click to Claim</p>
      </a>
      <p class="gift-card-category info-background">50 points: $50</p>
    </div>

    <div class="gift-card-offer">
    <a href="giftcardbox.php" >
        <img src="./img/paypal1.png" alt="Gift Card 2">
        <p class="gift-card-info">$10 paypal Gift Card</p>
        <p class="gift-card-action">Click to Claim</p>
      </a>
      <p class="gift-card-category info-background"> 10 points: $10</p>
    </div>

    <div class="gift-card-offer">
    <a href="giftcardbox.php" >
        <img src="./img/paypal2.png" alt="Gift Card 2">
        <p class="gift-card-info">$10 Paypal Gift Card</p>
        <p class="gift-card-action">Click to Claim</p>
      </a>
      <p class="gift-card-category blue-background">10 points: $10</p>
    </div>

    <div class="gift-card-offer">
    <a href="giftcardbox.php" >
        <img src="./img/steam1.png" alt="Gift Card 2">
        <p class="gift-card-info">$10 Steam Gift Card</p>
        <p class="gift-card-action">Click to Claim</p>
      </a>
      <p class="gift-card-category blue-background">10 points: $10</p>
    </div>

    <div class="gift-card-offer">
    <a href="giftcardbox.php" >
        <img src="./img/payoneer.png" alt="Gift Card 2">
        <p class="gift-card-info">$10 Payoneer Gift Card</p>
        <p class="gift-card-action">Click to Claim</p>
      </a>
      <p class="gift-card-category danger-background">10 points: $10</p>
    </div>

    <div class="gift-card-offer">
    <a href="giftcardbox.php" >
        <img src="./img/zalando.png" alt="Gift Card 2">
        <p class="gift-card-info">$10 Zalando Gift Card</p>
        <p class="gift-card-action">Click to Claim</p>
      </a>
      <p class="gift-card-category blue-background">10 points: $10</p>

    </div>

    <div class="gift-card-offer">
    <a href="giftcardbox.php" >
        <img src="./img/razer.jfif" alt="Gift Card 2">
        <p class="gift-card-info">$20 Razer Gold Gift Card</p>
        <p class="gift-card-action">Click to Claim</p>
      </a>
      <p class="gift-card-category warning-background">20 points: $20</p> <!-- Add the category field -->
    </div>
    <!-- Add more gift card offers here with their respective image URLs and offer links -->
  </div>
</section>
  <!-- Testimonial Section -->
  <div class="testimonial-section">
      <h2>Testimonials</h2>
      <div class="row">
        <div class="col-md-6">
          <div class="card">
            <div class="card-body">
            <img src="./img/test2.jfif" alt="Earning Image Proof 2" class="earning-image">
              <blockquote class="blockquote mb-0">
                <p>Wow! I can't believe my luck. I tried the offers on this website for the first time, and I received a gift card within minutes. It's been a game-changer for me. Thanks to this website, I was able to buy that expensive camera I've been dreaming of!</p>
                <footer class="blockquote-footer"> 
                <span class="name">Dora Smith</span><br>
                <span class="location">New York, NY</span>
                </footer>
              </blockquote>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="card">
            <div class="card-body">
            <img src="./img/test1.jfif" alt="Earning Image Proof 2" class="earning-image">
              <blockquote class="blockquote mb-0">
                <p>I was skeptical at first, but this website has proven me wrong. I earned Bitcoin rewards on my first attempt. It was so easy, and I've already seen a significant increase in my savings. Highly recommended!</p>
                <footer class="blockquote-footer">
                <span class="name">John</span><br>
                <span class="location">Los Angeles, CA</span>
                </footer>
              </blockquote>
            </div>
          </div>
        </div>

        <div class="col-md-6">
          <div class="card">
            <div class="card-body">
            <img src="./img/test3.jfif" alt="Earning Image Proof 2" class="earning-image">
              <blockquote class="blockquote mb-0">
                <p>This website is incredible! I joined and participated in a quick survey, and guess what? I received a gift card instantly. It helped me cover some unexpected expenses, and I couldn't be happier.</p>
                <footer class="blockquote-footer">
                <span class="name"> Emily</span><br>
                <span class="location">Chicago, IL</span>
                </footer>
              </blockquote>
            </div>
          </div>
        </div>

        <div class="col-md-6">
          <div class="card">
            <div class="card-body">
            <img src="./img/test7.jfif" alt="Earning Image Proof 2" class="earning-image">
              <blockquote class="blockquote mb-0">
                <p>I stumbled upon this website and decided to give it a shot. To my surprise, I got a gift card that allowed me to take my family out for a nice dinner. This website has made our day!</p>
                <footer class="blockquote-footer">
                <span class="name"> David</span><br>
                <span class="location">Houston, TX</span>
                </footer>
              </blockquote>
            </div>
          </div>
        </div>

        <div class="col-md-6">
          <div class="card">
            <div class="card-body">
            <img src="./img/test4.jfif" alt="Earning Image Proof 2" class="earning-image">
              <blockquote class="blockquote mb-0">
                <p>I needed some extra cash for a small investment, and this website came to the rescue. I followed the offers, and voilà, I received Bitcoin rewards right away. It's been a fantastic experience.</p>
                <footer class="blockquote-footer">
                <span class="name"> Mia</span><br>
                <span class="location">Miami, FL</span>
                </footer>
              </blockquote>
            </div>
          </div>
        </div>

        <div class="col-md-6">
          <div class="card">
            <div class="card-body">
            <img src="./img/test8.jfif" alt="Earning Image Proof 2" class="earning-image">
              <blockquote class="blockquote mb-0">
                <p>This website is a true blessing. I followed the simple instructions, and I received a gift card that helped me buy a new laptop. It's unbelievable how easy it was!</p>
                <footer class="blockquote-footer">
                <span class="name"> Daniel</span><br>
                <span class="location">San Francisco, CA</span>
                </footer>
              </blockquote>
            </div>
          </div>
        </div>

        <div class="col-md-6">
          <div class="card">
            <div class="card-body">
            <img src="./img/test5.jfif" alt="Earning Image Proof 2" class="earning-image">
              <blockquote class="blockquote mb-0">
                <p>I can't thank this website enough. I joined, participated, and received a gift card on my first try. It helped me pay off a portion of my student loans. What a relief!</p>
                <footer class="blockquote-footer">
                <span class="name"> Olivia</span><br>
                <span class="location">Seattle, WA</span>
                </footer>
              </blockquote>
            </div>
          </div>
        </div>

        <div class="col-md-6">
          <div class="card">
            <div class="card-body">
            <img src="./img/test10.jfif" alt="Earning Image Proof 2" class="earning-image">
              <blockquote class="blockquote mb-0">
                <p>I was skeptical about online offers, but this website is the real deal. I earned Bitcoin rewards effortlessly. It's an excellent way to boost your savings.</p>
                <footer class="blockquote-footer">
                <span class="name">  Ethan</span><br>
                <span class="location"> Austin, TX</span>
                </footer>
              </blockquote>
            </div>
          </div>
        </div>

        <div class="col-md-6">
          <div class="card">
            <div class="card-body">
            <img src="./img/test6.jfif" alt="Earning Image Proof 2" class="earning-image">
              <blockquote class="blockquote mb-0">
                <p>This website is a game-changer. I registered, completed a survey, and got a gift card. It came at the perfect time and allowed me to buy something special for my family.</p>
                <footer class="blockquote-footer">
                <span class="name">  Ava</span><br>
                <span class="location"> Denver, CO</span>
                </footer>
              </blockquote>
            </div>
          </div>
        </div>

        <div class="col-md-6">
          <div class="card">
            <div class="card-body">
            <img src="./img/test9.jfif" alt="Earning Image Proof 2" class="earning-image">
              <blockquote class="blockquote mb-0">
                <p>I had my doubts, but this website exceeded my expectations. I followed the offers and received a gift card. It's made a significant impact on my finances. Thank you!</p>
                <footer class="blockquote-footer">
                <span class="name"> Noah</span><br>
                <span class="location"> Phoenix, AZ</span>
                </footer>
              </blockquote>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- End of Testimonial Section -->


<!-- Pagination controls -->
<div class="pagination">
  <button id="prevBtn" class="arrow-button"><i class="fas fa-chevron-left"></i></button>
  <button id="nextBtn" class="arrow-button"><i class="fas fa-chevron-right"></i></button>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
   
      <!-- Add this line to include the Font Awesome JavaScript -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js" integrity="sha512-njn1/YIu72rurXJu5kji+AkPwoG3doKq4ZDEm6S4hXj2hpo9MPU7UML2TVPg1AXdZuspxL70W2vETx3k2buWw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

      
  
    <!-- Add any additional JavaScript interactivity here -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flipclock/0.7.8/flipclock.min.js"></script>
    <script>



 </script>
      

  </div>
  

<script>
// JavaScript code for testimonial pagination
const testimonialContainer = document.querySelector(".testimonial-section .row");
const prevBtn = document.getElementById("prevBtn");
const nextBtn = document.getElementById("nextBtn");
const testimonials = testimonialContainer.querySelectorAll(".col-md-6");

let currentPage = 0;
const testimonialsPerPage = 2; // Set the number of testimonials per page

function showTestimonials(page) {
  testimonials.forEach((testimonial, index) => {
    if (index >= page * testimonialsPerPage && index < (page + 1) * testimonialsPerPage) {
      testimonial.style.display = "block";
    } else {
      testimonial.style.display = "none";
    }
  });
}

// Show the first page of testimonials initially
showTestimonials(currentPage);

// Event listeners for pagination buttons
prevBtn.addEventListener("click", () => {
  currentPage = Math.max(currentPage - 1, 0);
  showTestimonials(currentPage);
});

nextBtn.addEventListener("click", () => {
  const lastPage = Math.ceil(testimonials.length / testimonialsPerPage) - 1;
  currentPage = Math.min(currentPage + 1, lastPage);
  showTestimonials(currentPage);
});

</script>




  <script>
    

  // JavaScript code for lead capture form submission
  document.getElementById("leadCaptureForm").addEventListener("submit", function(event) {
    event.preventDefault(); // Prevent form submission
    var email = document.getElementById("leadCaptureForm").elements["email"].value;
    //var userAgent = navigator.userAgent; // Get the user agent value
   
    // Perform any necessary validation or processing here
    // Submit the form data to your CPA account backend or email service provider
    document.getElementById("leadCaptureForm").submit(); // Submit the form after setting the user agent and screen resolution
  });
</script>






<script>


// script.js
const menuIcon = document.getElementById("menu-icon");
const closeIcon = document.getElementById("close-icon");
const menu = document.getElementById("menu");

menuIcon.addEventListener("click", () => {
    menu.style.display = "block";
    menuIcon.style.display = "none";
    closeIcon.style.display = "block";
});

closeIcon.addEventListener("click", () => {
    menu.style.display = "none";
    menuIcon.style.display = "block";
    closeIcon.style.display = "none";
});

</script>




</body>


</html>

