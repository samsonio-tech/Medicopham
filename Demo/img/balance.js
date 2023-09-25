// balance.js
document.addEventListener('DOMContentLoaded', function () {
    // Generate a random user ID (for simplicity)
    const userId = Math.floor(Math.random() * 1000);
    document.getElementById('capture-form').addEventListener('submit', function (e) {
        e.preventDefault();
        const email = document.getElementById('email').value;
        // Send email and userId to the server via AJAX or fetch API
        // For simplicity, we'll omit this step here
        // Assume server responds with $0 balance initially
        document.getElementById('balance').textContent = `Balance: $0 (User ID: ${userId})`;
    });

    // Update balance when the "Update Balance" link is clicked
    document.getElementById('update-balance').addEventListener('click', function (e) {
        e.preventDefault();
        // Redirect to the update_balance.php page
        window.location.href = 'update_balance.php';
    });
});
