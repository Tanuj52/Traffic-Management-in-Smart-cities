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

$phone = "";
$error = false;
$message = "";
$complaint_details = null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize input
    $phone = htmlspecialchars(stripslashes(trim($_POST["phone"])));

    // Validate phone number format
    if (!preg_match('/^[0-9]{10}$/', $phone)) {
        $error = true;
        $message = "Invalid phone number format.";
    }

    if (!$error) {
        // Fetch complaint details based on phone number
        $stmt = $conn->prepare("SELECT fullname, email, complaint, submitted_at FROM complaints WHERE phone = ?");
        $stmt->bind_param("s", $phone);
        $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($fullname, $email, $complaint, $submitted_at);
            $stmt->fetch();
            $complaint_details = array(
                'fullname' => $fullname,
                'email' => $email,
                'complaint' => $complaint,
                'submitted_at' => $submitted_at
            );
        } else {
            $error = true;
            $message = "No complaint found for this phone number.";
        }

        $stmt->close();
    }
}

$conn->close();

// Prepare JSON response
$response = array(
    'success' => !$error,
    'message' => $message,
    'complaint_details' => $complaint_details
);

// Output JSON response
header('Content-Type: application/json');
echo json_encode($response);
?>
