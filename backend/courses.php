<?php
// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "reglog";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted
if (isset($_POST['submit'])) {
    // Get form data
    $course_name = $_POST['course_name'];
    $course_explanation = $_POST['course_explanation'];
    $state_name = $_POST['state_name']; // Corrected to match 'state_name' in the form

    // Handle file upload
    if (isset($_FILES['course_image']) && $_FILES['course_image']['error'] === UPLOAD_ERR_OK) {
        $target_dir = "../uploads/"; // Directory to store the uploaded images
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true); // Create directory if not exists
        }

        $target_file = $target_dir . time() . "_" . basename($_FILES['course_image']['name']);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Validate the file is an image
        $check = getimagesize($_FILES['course_image']['tmp_name']);
        if ($check === false) {
            die("File is not an image.");
        }

        // Move uploaded file to the target directory
        if (move_uploaded_file($_FILES['course_image']['tmp_name'], $target_file)) {
            // Insert course data into the database
            $sql = "INSERT INTO courses_name (course_name, course_image, course_explanation, state_name) 
                    VALUES (?, ?, ?, ?)";

            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssss", $course_name, $target_file, $course_explanation, $state_name);

            // Execute the query
            if ($stmt->execute()) {
                echo "Course added successfully!";
            } else {
                echo "Error: " . $stmt->error;
            }

            $stmt->close();
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    } else {
        echo "No image uploaded or an error occurred during upload.";
    }
}

// Close connection
$conn->close();
?>
