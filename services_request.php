<?php
// Enable error reporting for debugging purposes
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Database configuration
$servername = "localhost";
$username = "root"; // Use your database username
$password = ""; // Use your database password
$dbname = "traffic_management"; // Use your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['Username'];
    $service_type = $_POST['Service_Type'];

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO services_requested (username, service_type) VALUES (?, ?)");
    $stmt->bind_param("ss", $username, $service_type);

    // Execute the query
    if ($stmt->execute()) {
        echo "Service request submitted successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();
}

// Close connection
$conn->close();
?>
