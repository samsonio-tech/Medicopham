


const express = require('express');
const app = express();
const bodyParser = require('body-parser');

app.use(bodyParser.urlencoded({ extended: false }));

// Serve static files
app.use(express.static('public'));

// Handle withdrawal form submission
app.post('/withdraw', (req, res) => {
    const amount = req.body.amount;
    // Process withdrawal logic here
    // Integrate with Bitcoin flashing software API

    // Send response to the user
    res.send(`Withdrawal request for ${amount} BTC received.`);
});

const PORT = 3000;
app.listen(PORT, () => {
    console.log(`Server is running on port ${PORT}`);
});