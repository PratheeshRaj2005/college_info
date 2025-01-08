<?php
// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "reglog";

// Establish a database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check for a connection error
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the form inputs
    $faculty_name = $_POST['faculty_name'];
    $qualification = $_POST['qualification'];
    $designation = $_POST['designation'];
    $work_location = $_POST['work_location'];
    $personal_number = $_POST['personal_number'];

    // Validate inputs
    if (!preg_match("/^[a-zA-Z ]+$/", $faculty_name)) {
        echo "<script>alert('Faculty name should only contain alphabets and spaces.'); window.history.back();</script>";
        exit();
    }

    if (!preg_match("/^[a-zA-Z ]+$/", $qualification)) {
        echo "<script>alert('Qualification should only contain alphabets and spaces.'); window.history.back();</script>";
        exit();
    }

    if (!preg_match("/^[a-zA-Z ]+$/", $designation)) {
        echo "<script>alert('Designation should only contain alphabets and spaces.'); window.history.back();</script>";
        exit();
    }

    if (!preg_match("/^\d{10}$/", $personal_number)) {
        echo "<script>alert('Phone number should be exactly 10 digits.'); window.history.back();</script>";
        exit();
    }

    // Handle the image upload
    $image = $_FILES['image']['name'];
    $image_temp = $_FILES['image']['tmp_name'];
    $image_extension = strtolower(pathinfo($image, PATHINFO_EXTENSION));
    $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
    $image_path = 'uploads/' . basename($image);

    // Check if the uploaded file is an image
    if (!in_array($image_extension, $allowed_extensions)) {
        echo "<script>alert('Only image files (JPG, JPEG, PNG, GIF) are allowed.'); window.history.back();</script>";
        exit();
    }

    // Check if the upload folder exists, if not, create it
    if (!is_dir('uploads')) {
        mkdir('uploads', 0777, true);
    }

    // Move the uploaded image to the 'uploads' folder
    if (move_uploaded_file($image_temp, $image_path)) {
        // Prepare SQL query to insert data into the faculty table
        $sql = "INSERT INTO faculty (image, faculty_name, qualification, designation, work_location, personal_number) 
                VALUES ('$image_path', '$faculty_name', '$qualification', '$designation', '$work_location', '$personal_number')";

        // Execute the query
        if ($conn->query($sql) === TRUE) {
            echo "<script>alert('New faculty member added successfully!'); window.location.href='faculty_form.html';</script>";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } else {
        echo "<script>alert('Error uploading image.'); window.history.back();</script>";
    }
}

$conn->close();
?>
