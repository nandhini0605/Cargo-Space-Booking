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

    // Retrieve container ID from the POST data
    $containerData = json_decode(file_get_contents('php://input'), true);
    $containerId = isset($containerData['containerId']) ? $containerData['containerId'] : null;

    if ($containerId) {
        // Delete container
        $stmt = $conn->prepare("DELETE FROM containers WHERE container_id = ? AND username = ?");
        $stmt->bind_param("is", $containerId, $username);

        if ($stmt->execute()) {
            $response['success'] = true;
            $response['message'] = 'Container deleted successfully.';
        } else {
            $response['message'] = 'Error deleting container: ' . $stmt->error;
        }

        $stmt->close();
    } else {
        $response['message'] = 'Container ID not provided.';
    }
} else {
    // Handle unauthorized access
    $response['message'] = 'Unauthorized access';
}

// Send the JSON response
header('Content-Type: application/json');
echo json_encode($response);

$conn->close();
?>