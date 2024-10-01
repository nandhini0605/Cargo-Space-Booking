<?php

session_start();

$response = array('success' => false, 'message' => '');

if (isset($_SESSION['username']) && isset($_SESSION['role']) && $_SESSION['role'] === 'service_provider') {
    $username = $_SESSION['username'];

    $data = json_decode(file_get_contents("php://input"), true);

    $size = $data['size'];
    $mode = $data['mode'];
    $originalId = $data['originalId']; // Add this line

    $conn = new mysqli("localhost", "root", "", "cargoexpress");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("INSERT INTO containers (username, size, mode, original_id) VALUES (?, ?, ?, ?)");
    if (!$stmt) {
        $response['message'] = 'Prepare Error: ' . htmlspecialchars($conn->error);
    } else {
        $stmt->bind_param("ssss", $username, $size, $mode, $originalId);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            $response['success'] = true;
            $response['message'] = 'Container added successfully';
        } else {
            $response['message'] = 'Error adding container';
        }

        $stmt->close();
    }

    $conn->close();
} else {
    $response['message'] = 'Unauthorized access';
}

header('Content-Type: application/json; charset=utf-8');
echo json_encode($response);
?>