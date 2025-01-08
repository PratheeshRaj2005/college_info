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
    $about_fees = $conn->real_escape_string($_POST['about_fees']);
    $particular = $conn->real_escape_string($_POST['particular']);
    $fee_amount = $conn->real_escape_string($_POST['fee_amount']);

    // Use prepared statements to insert data into the database
    $stmt = $conn->prepare("
        INSERT INTO fee_details (college_name, course, about_fees, particular, fee_amount) 
        VALUES (?, ?, ?, ?, ?)
    ");
    $stmt->bind_param("sssss", $college_name, $course, $about_fees, $particular, $fee_amount);

    // Execute the query
    if ($stmt->execute()) {
        echo "Fee details added successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}
?>
