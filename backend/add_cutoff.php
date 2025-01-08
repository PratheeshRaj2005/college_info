<?php
// Database connection details
$host = "localhost"; // Update with your host
$username = "root"; // Update with your database username
$password = ""; // Update with your database password
$dbname = "reglog"; // Update with your database name

// Connect to the database
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize form data
    $college_name = $conn->real_escape_string($_POST['college_name']);
    $course_name = $conn->real_escape_string($_POST['course_name']);
    $department_name = $conn->real_escape_string($_POST['department_name']);
    $prev_cutoff = $conn->real_escape_string($_POST['prev_cutoff']);
    $current_cutoff = $conn->real_escape_string($_POST['current_cutoff']);

    // Validation (ensure no empty fields)
    if (empty($college_name) || empty($course_name) || empty($department_name) || empty($prev_cutoff) || empty($current_cutoff)) {
        echo "All fields are required.";
        exit;
    }

    // Insert data into the database
    $query = "INSERT INTO cutoffs (college_name, course_name, department_name, prev_cutoff, current_cutoff) 
              VALUES ('$college_name', '$course_name', '$department_name', '$prev_cutoff', '$current_cutoff')";

    if ($conn->query($query) === TRUE) {
        echo "Cutoff details added successfully.";
    } else {
        echo "Error: " . $query . "<br>" . $conn->error;
    }
}

// Close the database connection
$conn->close();
?>
