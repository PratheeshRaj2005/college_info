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
    $college_name = $_POST['college_name'];
    $course = $_POST['course'];
    $particular = $_POST['particular'];
    $particular_text = $_POST['particular_text'];
    $highlights = $_POST['highlights'];

    // Handle file upload
    if (isset($_FILES['infrastructure_images']) && $_FILES['infrastructure_images']['error'] === UPLOAD_ERR_OK) {
        $target_dir = "../uploads/"; // Directory to store the uploaded images
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true); // Create directory if not exists
        }

       

        // Validate the file is an image
        $check = getimagesize($_FILES['infrastructure_images']['tmp_name']);
        if ($check === false) {
            die("File is not an image.");
        }

        // Check if the file already exists
        if (file_exists($target_file)) {
            echo "Sorry, file already exists.";
        } else {
            // Move uploaded file to the target directory
            if (move_uploaded_file($_FILES['infrastructure_images']['tmp_name'], $target_file)) {
                // Insert data into the database
                $stmt = $conn->prepare("
                    INSERT INTO infrastructure 
                    (college_name, course, particular, particular_text, highlights_text, infrastructure_images) 
                    VALUES (?, ?, ?, ?, ?, ?)
                ");
                $stmt->bind_param("ssssss", $college_name, $course, $particular, $particular_text, $highlights, $target_file);

                // Execute the query
                if ($stmt->execute()) {
                    echo "Infrastructure details added successfully!";
                } else {
                    echo "Error: " . $stmt->error;
                }

                $stmt->close();
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        }
    } else {
        echo "No image uploaded or an error occurred during upload.";
    }
}

// Close connection
$conn->close();
?>
