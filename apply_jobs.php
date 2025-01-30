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
    $job_position = $_POST['Job_Position'];
    
    // Check if the user has already applied for this job position
    $check_query = "SELECT id FROM jobs_applied WHERE username = ? AND job_position = ?";
    $stmt = $conn->prepare($check_query);
    $stmt->bind_param("ss", $username, $job_position);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        echo "You have already applied for this job position.";
        $stmt->close();
        $conn->close();
        exit();
    }
    $stmt->close();

    // Check if the file is uploaded
    if (isset($_FILES['Resume']) && $_FILES['Resume']['error'] == UPLOAD_ERR_OK) {
        $resume_name = $_FILES['Resume']['name'];
        $resume_tmp_name = $_FILES['Resume']['tmp_name'];
        $resume_size = $_FILES['Resume']['size'];
        $resume_type = $_FILES['Resume']['type'];

        // Check file size (example limit: 5MB)
        $max_file_size = 5 * 1024 * 1024; // 5MB in bytes
        if ($resume_size > $max_file_size) {
            echo "Error: File size exceeds the limit of 5MB.";
            exit();
        }

        // Allowed file extensions
        $allowed_extensions = array('pdf', 'doc', 'docx', 'txt');
        $file_extension = strtolower(pathinfo($resume_name, PATHINFO_EXTENSION));

        // Validate file extension
        if (!in_array($file_extension, $allowed_extensions)) {
            echo "Error: Only PDF, DOC, DOCX, or TXT files are allowed.";
            exit();
        }

        // Target directory for resume uploads (absolute path)
        $target_dir = "/Applications/XAMPP/xamppfiles/htdocs/Project/uploads/";
        $target_file = $target_dir . uniqid('', true) . '.' . $file_extension;

        // Move the uploaded file to the target directory
        if (move_uploaded_file($resume_tmp_name, $target_file)) {
            // Prepare and bind
            $stmt = $conn->prepare("INSERT INTO jobs_applied (username, job_position, resume) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $username, $job_position, $target_file);

            // Execute the query
            if ($stmt->execute()) {
                echo "Application submitted successfully.";
            } else {
                echo "Error: " . $stmt->error;
            }

            // Close the statement
            $stmt->close();
        } else {
            echo "Sorry, there was an error uploading your resume.";
        }
    } else {
        echo "No file was uploaded or there was an upload error.";
    }
}

// Close connection
$conn->close();
?>
