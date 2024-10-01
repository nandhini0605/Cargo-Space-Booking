<?php

$conn = new mysqli("localhost", "root", "", "cargoexpress");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$response = array('success' => false, 'data' => array());

// Fetch service providers' data from the database
$result = $conn->query("SELECT companyName, servicesProvided, coverageArea FROM service_providers");

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $response['data'][] = $row;
    }
    $response['success'] = true;
} else {
    $response['message'] = 'Error fetching data from the database: ' . $conn->error;
}

// Send the JSON response
header('Content-Type: application/json');
echo json_encode($response);

$conn->close();
?>