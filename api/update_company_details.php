<?php

$conn = new mysqli("localhost", "root", "", "cargoexpress");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$response = array('success' => false, 'message' => '');

// Assuming you have a session started, adjust this based on your authentication method
session_start();

// Check if a user is logged in and their role is a service provider
if (isset($_SESSION['username']) && isset($_SESSION['role']) && $_SESSION['role'] === 'service_provider') {
    $username = $_SESSION['username'];

    // Retrieve edited company details from the POST data
    $editedData = json_decode(file_get_contents('php://input'), true);

    $editedCompanyName = $conn->real_escape_string($editedData['editedcompanyname']);
    $editedRegistrationNumber = $conn->real_escape_string($editedData['editedregistrationnumber']);
    $editedServicesProvided = $conn->real_escape_string($editedData['editedservicesprovided']);
    $editedCoverageArea = $conn->real_escape_string($editedData['editedcoveragearea']);
    $editedExperience = $conn->real_escape_string($editedData['editedexperience']);

    // Update company details in the database
    $stmt = $conn->prepare("UPDATE service_providers SET companyName=?, registrationNumber=?, servicesProvided=?, coverageArea=?, experience=? WHERE username=?");
    $stmt->bind_param("ssssss", $editedCompanyName, $editedRegistrationNumber, $editedServicesProvided, $editedCoverageArea, $editedExperience, $username);

    if ($stmt->execute()) {
        $response['success'] = true;
        $response['message'] = 'Company details updated successfully.';
    } else {
        $response['message'] = 'Error updating company details: ' . $stmt->error;
    }

    $stmt->close();
} else {
    // Handle unauthorized access
    $response['message'] = 'Unauthorized access';
}

// Send the JSON response
header('Content-Type: application/json');
echo json_encode($response);

$conn->close();
?>