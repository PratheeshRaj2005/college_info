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
    $college = $conn->real_escape_string($_POST['college']);
    $course = $conn->real_escape_string($_POST['course']);
    $scholarship_title = $conn->real_escape_string($_POST['scholarship_title']);
    $about_scholarship = $conn->real_escape_string($_POST['about_scholarship']);

    // Use prepared statements to insert data into the database
    $stmt = $conn->prepare("
        INSERT INTO scholarships (college, course, scholarship, about_scholarship) 
        VALUES (?, ?, ?, ?)
    ");
    $stmt->bind_param("ssss", $college, $course, $scholarship_title, $about_scholarship);

    // Execute the query
    if ($stmt->execute()) {
        echo "Scholarship details added successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}
?>
