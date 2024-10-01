<?php
session_start(); // Ensure the session is started

// Check if the user is logged in
if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];

    // Now fetch the company details including the username
    // Modify the following code according to your database structure and query
    $conn = new mysqli("localhost", "root", "", "cargoexpress");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $query = "SELECT * FROM service_providers WHERE username = '$username'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $companyDetails = array(
            'companyName' => $row['companyName'],
            'registrationNumber' => $row['registrationNumber'],
            'servicesProvided' => $row['servicesProvided'],
            'coverageArea' => $row['coverageArea'],
            'experience' => $row['experience'],
            'username' => $row['username'], // Include the username
        );

        echo json_encode(array('companyDetails' => $companyDetails));
    } else {
        http_response_code(404); // Set HTTP response code to 404 Not Found
        echo json_encode(array('error' => 'Company details not found'));
    }

    $conn->close();
} else {
    http_response_code(401); // Set HTTP response code to 401 Unauthorized
    echo json_encode(array('error' => 'User not logged in'));
}
?>