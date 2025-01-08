<?php
// Database connection
$servername = "localhost";
$username = "root"; // Replace with your MySQL username
$password = ""; // Replace with your MySQL password
$dbname = "reglog"; // Database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and fetch form data
    $college_name = $conn->real_escape_string($_POST['college_name']);
    $course = $conn->real_escape_string($_POST['course_name']);
    $ranking_title = $conn->real_escape_string($_POST['ranking_title']);
    $about_rankings = $conn->real_escape_string($_POST['about_rankings']);
// Handle the image upload
if (isset($_FILES['ranking_logo']) && $_FILES['ranking_logo']['error'] == 0) {
    // Define the target directory for uploads
    $targetDir = "../uploads/";
    // Create the directory if it doesn't exist
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    // Get the image name and set the target file path
    $imageName = basename($_FILES["ranking_logo"]["name"]);
    $targetFilePath = $targetDir . $imageName;
    $imageFileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));

    // Check if the uploaded file is an image of allowed types
    $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
    if (in_array($imageFileType, $allowedTypes)) {
        if (move_uploaded_file($_FILES["ranking_logo"]["tmp_name"], $targetFilePath)) {
            // Prepare the SQL insert statement
            $sql = "INSERT INTO rankings (college_name, course,ranking_logo, ranking_title, about_rankings)
                    VALUES ('$college_name', '$course', '$targetFilePath', '$ranking_title', '$about_rankings')";

            // Execute the query and check for succes
            if ($conn->query($sql) === TRUE) {
                echo "<script>alert('New ranking added successfully.');
                window.location.href = '../admin/addranking.php'; </script>";
            } else {
                echo "<script>alert('Error: " . $sql . "\\n" . $conn->error . "');</script>";
            }
        } else {
            echo "<script>alert('Sorry, there was an error uploading your file.');</script>";
        }
    } else {
        echo "<script>alert('Sorry, only JPG, JPEG, PNG & GIF files are allowed.');</script>";
    }
} else {
    echo "<script>alert('Please upload an image file.');</script>";
}
// Close the connection
    $conn->close();
}
?>
