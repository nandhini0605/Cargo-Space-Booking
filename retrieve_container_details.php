<?php

$conn = new mysqli("localhost", "root", "", "cargoexpress");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$response = array('containerDetails' => array(), 'success' => false, 'message' => '');

// Assuming you have a session started, adjust this based on your authentication method
session_start();

// Check if a user is logged in and their role is a service provider
if (isset($_SESSION['username']) && isset($_SESSION['role']) && $_SESSION['role'] === 'service_provider') {
    $username = $_SESSION['username'];

    // Fetch container details for the logged-in service provider
    $stmt = $conn->prepare("SELECT container_id, size, mode FROM containers WHERE username = ?");
    if (!$stmt) {
        $response['message'] = 'Prepare Error: ' . htmlspecialchars($conn->error);
    } else {
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Fetch container details into an array
            while ($row = $result->fetch_assoc()) {
                $containerDetails[] = array(
                    'id' => $row['container_id'],
                    'size' => $row['size'],
                    'mode' => $row['mode']
                );
            }

            $response['containerDetails'] = $containerDetails;
            $response['success'] = true;
        } else {
            $response['message'] = 'No container details found for the logged-in service provider.';
        }

        $stmt->close();
    }
} else {
    // Handle unauthorized access
    $response['message'] = 'Unauthorized access';
}

// Send the JSON response
header('Content-Type: application/json; charset=utf-8');
echo json_encode($response);

$conn->close();
?>