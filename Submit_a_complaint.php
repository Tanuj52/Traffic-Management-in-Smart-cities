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
    die(json_encode(array('success' => false, 'message' => 'Connection failed: ' . $conn->connect_error)));
}

// Initialize variables for form data and error handling
$fullname = $email = $phone = $complaint = "";
$error = false;
$message = "";

// Validate and sanitize input data
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Validate email format
function validate_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// Validate phone number format (simplified for demonstration)
function validate_phone($phone) {
    return preg_match('/^[0-9]{10}$/', $phone); // Adjust as per your phone number format
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize inputs
    $fullname = sanitize_input($_POST["fullname"]);
    $email = sanitize_input($_POST["email"]);
    $phone = sanitize_input($_POST["phone"]);
    $complaint = sanitize_input($_POST["complaint"]);

    // Validate email
    if (!validate_email($email)) {
        $error = true;
        $message = "Invalid email format.";
    }
    
    // Validate phone number
    if (!validate_phone($phone)) {
        $error = true;
        $message = "Invalid phone number format.";
    }

    // Insert into complaints table if no errors
    if (!$error) {
        $stmt = $conn->prepare("INSERT INTO complaints (fullname, email, phone, complaint) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $fullname, $email, $phone, $complaint);

        if ($stmt->execute()) {
            $message = "Your complaint has been submitted successfully.";
        } else {
            $error = true;
            $message = "Error: " . $stmt->error;
        }

        $stmt->close();
    }
}

$conn->close();

// Prepare JSON response
$response = array(
    'success' => !$error,
    'message' => $message
);

// Output JSON response
header('Content-Type: application/json');
echo json_encode($response);
?>
