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
    $news_title = $conn->real_escape_string($_POST['news_title']);
    $about_news = $conn->real_escape_string($_POST['about_news']);
    $news_date = $conn->real_escape_string($_POST['news_date']);
    $news_time = $conn->real_escape_string($_POST['news_time']);

    // Use prepared statements to insert data into the database
    $stmt = $conn->prepare("
        INSERT INTO news_updates (college_name, course, news_title, about_news, news_date, news_time) 
        VALUES (?, ?, ?, ?, ?, ?)
    ");
    $stmt->bind_param("ssssss", $college_name, $course, $news_title, $about_news, $news_date, $news_time);

    // Execute the query
    if ($stmt->execute()) {
        echo "News update added successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}
?>
