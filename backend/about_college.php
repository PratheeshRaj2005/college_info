<?php
// Database connection
$host = "localhost";
$username = "root";
$password = ""; // Set your database password here
$database = "reglog";

$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect form data
    $college_name = $conn->real_escape_string($_POST['college_name']);
    $college = $conn->real_escape_string($_POST['college']);
    $course = $conn->real_escape_string($_POST['course']);
    $contact = $conn->real_escape_string($_POST['contact']);
    $location = $conn->real_escape_string($_POST['location']);
    $about_college = $conn->real_escape_string($_POST['about_college']);

    // File upload paths
    $upload_dir = "../uploads/colleges/";
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    // Upload college logo
    $logo_name = $_FILES['logo']['name'];
    $logo_tmp = $_FILES['logo']['tmp_name'];
    $logo_path = $upload_dir . "logo_" . time() . "_" . basename($logo_name);
    move_uploaded_file($logo_tmp, $logo_path);

    // Upload images
    $image1_path = $upload_dir . "image1_" . time() . "_" . basename($_FILES['image1']['name']);
    move_uploaded_file($_FILES['image1']['tmp_name'], $image1_path);

    $image2_path = $upload_dir . "image2_" . time() . "_" . basename($_FILES['image2']['name']);
    move_uploaded_file($_FILES['image2']['tmp_name'], $image2_path);

    $image3_path = $upload_dir . "image3_" . time() . "_" . basename($_FILES['image3']['name']);
    move_uploaded_file($_FILES['image3']['tmp_name'], $image3_path);

    $image4_path = $upload_dir . "image4_" . time() . "_" . basename($_FILES['image4']['name']);
    move_uploaded_file($_FILES['image4']['tmp_name'], $image4_path);

    // Insert data into database
    $sql = "INSERT INTO about_college (
                college_name, 
                college, 
                course, 
                logo, 
                contact, 
                location, 
                image1, 
                image2, 
                image3, 
                image4, 
                about_college
            ) VALUES (
                '$college_name', 
                '$college', 
                '$course', 
                '$logo_path', 
                '$contact', 
                '$location', 
                '$image1_path', 
                '$image2_path', 
                '$image3_path', 
                '$image4_path', 
                '$about_college'
            )";

    if ($conn->query($sql) === TRUE) {
        echo "College added successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    // Close connection
    $conn->close();
}
?>
