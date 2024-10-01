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

    // Retrieve container details from the POST data
    $containerData = json_decode(file_get_contents('php://input'), true);

    $containerId = isset($containerData['containerId']) ? $containerData['containerId'] : null; // Check if containerId is set
    $containerOriginalId = isset($containerData['originalId']) ? $containerData['originalId'] : null;
    $containerSize = $conn->real_escape_string($containerData['size']);
    $containerMode = $conn->real_escape_string($containerData['mode']);

    if ($containerId) {
        // Update existing container
        $stmt = $conn->prepare("UPDATE containers SET size=?, mode=? WHERE container_id=? AND username=?");
        $stmt->bind_param("ssis", $containerSize, $containerMode, $containerId, $username);
    } else {
        // Insert new container
        $stmt = $conn->prepare("INSERT INTO containers (username, size, mode) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $containerSize, $containerMode);
    }

    if ($stmt->execute()) {
        $response['success'] = true;
        $response['message'] = $containerId ? 'Container details updated successfully.' : 'Container details added successfully.';
    } else {
        $response['message'] = 'Error updating/adding container details: ' . $stmt->error;
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