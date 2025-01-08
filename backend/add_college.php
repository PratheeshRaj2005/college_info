<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "reglog");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['submit'])) {
    // Collect form data
    $college_name = $conn->real_escape_string($_POST['college_name']);
    $about_college = $conn->real_escape_string($_POST['about_college']);
    $state = $conn->real_escape_string($_POST['state']);
    $course = $conn->real_escape_string($_POST['course']);

    // Handle file upload
    $target_dir = "../uploads/colleges/";
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true); // Create directory if not exists
    }
    $file_name = basename($_FILES["college_image"]["name"]);
    $target_file = $target_dir . time() . "_" . $file_name;
    $upload_ok = true;
    $image_file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Validate file
    $check = getimagesize($_FILES["college_image"]["tmp_name"]);
    if ($check === false) {
        echo "File is not an image.";
        $upload_ok = false;
    }

    // Allow certain file formats
    if (!in_array($image_file_type, ["jpg", "jpeg", "png", "gif"])) {
        echo "Only JPG, JPEG, PNG & GIF files are allowed.";
        $upload_ok = false;
    }

    // Check if $upload_ok is set to false
    if (!$upload_ok) {
        echo "Sorry, your file was not uploaded.";
    } else {
        // Try to upload file
        if (move_uploaded_file($_FILES["college_image"]["tmp_name"], $target_file)) {
            $college_image = $target_file; // Save the file path

            // Insert into database
            $sql = "INSERT INTO colleges (college_name, college_image, about_college, state, course_name) 
                    VALUES ('$college_name', '$college_image', '$about_college', '$state', '$course')";

            if ($conn->query($sql) === TRUE) {
                echo "College added successfully.";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
}

$conn->close();
?>
