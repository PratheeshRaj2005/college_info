<?php
// Database configuration
$host = "localhost";
$username = "root";
$password = "";
$dbname = "reglog";

// Create a connection to the database
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if college_name is set
if (isset($_POST['college_name'])) {
    $college_name = $conn->real_escape_string($_POST['college_name']);
    $courses_query = "SELECT DISTINCT course_name FROM colleges WHERE college_name = '$college_name'";
    $courses_result = $conn->query($courses_query);

    if ($courses_result->num_rows > 0) {
        $courses = [];
        while ($row = $courses_result->fetch_assoc()) {
            $courses[] = $row['course_name'];
        }
        echo json_encode($courses);
    } else {
        echo json_encode([]);
    }
} else {
    echo json_encode([]);
}
?>
