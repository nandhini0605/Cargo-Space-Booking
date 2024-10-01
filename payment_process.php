<?php
session_start();

if (isset($_SESSION["trader_id"])) {
    $traderId = $_SESSION["trader_id"];
} else {
    // Handle the case when trader_id is not set in the session
    echo "Error: Trader ID is not set in the session.";
    exit(); // Exit to prevent further execution
}

if (isset($_SESSION["username"])) {
    $username = $_SESSION["username"];
} else {
    // Handle the case when username is not set in the session
    echo "Error: Username is not set in the session.";
    exit(); // Exit to prevent further execution
}

$servername = "localhost";
$usernameDB = "root"; 
$passwordDB = ""; 
$dbname = "cargoexpress";

// Create connection
$conn = new mysqli($servername, $usernameDB, $passwordDB, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $upiId = $conn->real_escape_string($_POST["upiId"]);

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO payment (trader_id, username, upi_id) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $traderId, $username, $upiId);

    // Execute the prepared statement
    if ($stmt->execute()) {
        echo "Payment Successful!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
} else {
    // Redirect or handle the case when the form is not submitted via POST
}

$conn->close();
?>