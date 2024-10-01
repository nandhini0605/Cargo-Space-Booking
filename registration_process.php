<?php
$conn = new mysqli('localhost', 'root', '', 'cargoexpress');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Common data
$username = $conn->real_escape_string($_POST['username']);
$password = password_hash($_POST['password'], PASSWORD_BCRYPT);
$email = $conn->real_escape_string($_POST['email']);
$phone = $conn->real_escape_string($_POST['phone']);

// Check if it's a trader registration
if (isset($_POST['fullName'])) {
    $fullName = $conn->real_escape_string($_POST['fullName']);

    // Trader registration
    $stmt = $conn->prepare("INSERT INTO traders (username, password, fullName, email, phone) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $username, $password, $fullName, $email, $phone);

    if ($stmt->execute()) {
        header("Location: login.html");
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
} else {
    // Service provider registration
    $companyName = $conn->real_escape_string($_POST['companyName']);
    $registrationNumber = $conn->real_escape_string($_POST['registrationNumber']);
    $servicesProvided = $conn->real_escape_string($_POST['servicesProvided']);
    $coverageArea = $conn->real_escape_string($_POST['coverageArea']);
    $experience = $conn->real_escape_string($_POST['experience']);
    $businessLicense = $conn->real_escape_string($_POST['businessLicense']);
    $termsConditions = isset($_POST['termsConditions']) ? 1 : 0;

    $stmt = $conn->prepare("INSERT INTO service_providers (username, password, companyName, registrationNumber, servicesProvided, coverageArea, experience, businessLicense, termsConditions) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssssi", $username, $password, $companyName, $registrationNumber, $servicesProvided, $coverageArea, $experience, $businessLicense, $termsConditions);

    if ($stmt->execute()) {
        header("Location: login.html");
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>