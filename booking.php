<?php

// Assuming you have a database connection established
$conn = new mysqli('localhost', 'root', '', 'cargoexpress');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

session_start();

if (isset($_SESSION["trader_id"])) {
    $traderId = $_SESSION["trader_id"];
} else {
    // Handle the case when trader_id is not set in the session
    echo "Error: Trader ID is not set in the session.";
    exit(); // Exit to prevent further execution
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $source = $conn->real_escape_string($_POST["source"]);
    $destination = $conn->real_escape_string($_POST["destination"]);
    $deliveryLocation = $conn->real_escape_string($_POST["delivery-location"]);
    $typeOfCargo = $conn->real_escape_string($_POST["type-of-cargo"]);
    $cargoDimensions = $conn->real_escape_string($_POST["cargo-dimensions"]);
    $cargoWeight = $conn->real_escape_string($_POST["cargo-weight"]);
    $cargoDescription = $conn->real_escape_string($_POST["cargo-description"]);
    $date = $conn->real_escape_string($_POST["date"]);
    $time = $conn->real_escape_string($_POST["time"]);

    if (!empty($traderId)) {
        // Insert the booking details into the database
        $sql = "INSERT INTO bookings (trader_id, source, destination, delivery_location, type_of_cargo, cargo_dimensions, cargo_weight, cargo_description, date, time)
                VALUES ('$traderId', '$source', '$destination', '$deliveryLocation', '$typeOfCargo', '$cargoDimensions', '$cargoWeight', '$cargoDescription', '$date', '$time')";
    } else {
        // Handle the case when $traderId is empty
        echo "Error: Trader ID is empty.";
        exit(); // Exit to prevent further execution
    }
    
    if ($conn->query($sql) === TRUE) {
        // Booking successful, redirect to space.html
        header("Location: space.html");
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
} else {
    // Redirect or handle the case when the form is not submitted via POST
}
$conn->close();
?>