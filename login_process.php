<?php

$conn = new mysqli("localhost", "root", "", "cargoexpress");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$response = array('success' => false, 'message' => '', 'userType' => '');

// Retrieve and sanitize data from the login form
$username = $conn->real_escape_string($_POST['username']);
$password = $_POST['password']; // No need to sanitize as it is not stored in the database

// Check if the user is a trader or a service provider
$query = "SELECT * FROM traders WHERE username='$username'";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    // User is a trader
    $row = $result->fetch_assoc();
    if (password_verify($password, $row['password'])) {
        // Password is correct, perform trader login actions (e.g., set sessions)
        $response['success'] = true;
        $response['userType'] = 'trader';
        $response['username'] = $username;

        // Set session variables for the logged-in trader
        session_start();
        $_SESSION['trader_id'] = $row['id'];  // Assuming 'id' is the trader's unique identifier in the traders table
        $_SESSION['username'] = $username;
    } else {
        // Password is incorrect for the trader
        $response['message'] = 'Password incorrect. Please try again.';
    }
} else {
    // User is a service provider
    $query = "SELECT * FROM service_providers WHERE username='$username'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            // Password is correct, perform service provider login actions (e.g., set sessions)
            $response['success'] = true;
            $response['userType'] = 'serviceProvider';
            $response['username'] = $username;

            // Set session variables for the logged-in service provider
        session_start();
        $_SESSION['username'] = $username;
        $_SESSION['role'] = 'service_provider'; // Corrected from 'serviceProvider'

        } else {
            // Password is incorrect for the service provider
            $response['message'] = 'Password incorrect. Please try again.';
        }
    }
}

// If execution reaches here, the username is not found
if (!$response['success'] && empty($response['message'])) {
    $response['message'] = 'Username not found. Please try again.';
}

// Send the JSON response
header('Content-Type: application/json');
echo json_encode($response);

$conn->close();
?>