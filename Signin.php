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
    $full_name = $_POST['Full_Name'];
    $email = $_POST['Email'];
    $phone_number = $_POST['Phone_Number'];
    $password = $_POST['Password'];
    $confirm_password = $_POST['Confirm_Password'];
    $gender = $_POST['gender'];

    // Basic validation
    if ($password !== $confirm_password) {
        echo "Passwords do not match!";
        exit();
    }

    if (strlen($password) < 8) {
        echo "Password must be at least 8 characters long!";
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format!";
        exit();
    }

    // Check if username or email already exists
    $check_query = "SELECT id FROM users WHERE username = ? OR email = ?";
    $stmt = $conn->prepare($check_query);
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        echo "Username or Email already exists!";
        $stmt->close();
        $conn->close();
        exit();
    }
    $stmt->close();

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO users (username, full_name, email, phone_number, password, gender) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $username, $full_name, $email, $phone_number, $hashed_password, $gender);

    // Execute the query
    if ($stmt->execute()) {
        echo "New record created successfully";
        // Redirect to login page after successful registration
        header("Location: login.html");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();
}

// Close connection
$conn->close();
?>
