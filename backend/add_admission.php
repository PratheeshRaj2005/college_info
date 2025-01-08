<?php
// Database connection
$host = "localhost";  // Replace with your host
$username = "root";   // Replace with your MySQL username
$password = "";       // Replace with your MySQL password
$dbname = "reglog";   // Replace with your database name

$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if (isset($_POST['submit'])) {
    // Sanitize form inputs
    $college_name = $conn->real_escape_string($_POST['college_name']);
    $course = $conn->real_escape_string($_POST['course']);
    $admission_description = $conn->real_escape_string($_POST['admission_description']);
    $particulars = $conn->real_escape_string($_POST['particulars']);
    $highlights = $conn->real_escape_string($_POST['highlights']);

    // Use prepared statements to insert data into the database
    $stmt = $conn->prepare("
        INSERT INTO admission_details (college_name, course, admission_description, particulars, highlights) 
        VALUES (?, ?, ?, ?, ?)
    ");
    $stmt->bind_param("sssss", $college_name, $course, $admission_description, $particulars, $highlights);

    // Execute the query
    if ($stmt->execute()) {
        echo "Admission details added successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}
?>
