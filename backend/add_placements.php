<?php
// Database connection
$host = "localhost";  // Replace with your host
$username = "root";   // Replace with your MySQL username
$password = "";       // Replace with your MySQL password
$dbname = "reglog";

$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if (isset($_POST['submit'])) {
    // Sanitize form inputs
    $college_name = $conn->real_escape_string($_POST['college_name']);
    $course_name = $conn->real_escape_string($_POST['course_name']);
    $average_package = $conn->real_escape_string($_POST['average_package']);
    $minimum_package = $conn->real_escape_string($_POST['minimum_package']);
    $placement_rate = $conn->real_escape_string($_POST['placement_rate']);
    $participated_students = $conn->real_escape_string($_POST['participated_students']);
    $accepted_offers = $conn->real_escape_string($_POST['accepted_offers']);
    $total_recruiters = $conn->real_escape_string($_POST['total_recruiters']);

    // Handle file upload
    $uploaded_images = [];
    if (isset($_FILES['company_images'])) {
        $target_dir = "../uploads/"; // Set upload directory
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true); // Create directory if it doesn't exist
        }

        foreach ($_FILES['company_images']['tmp_name'] as $key => $tmp_name) {
            $file_name = basename($_FILES['company_images']['name'][$key]);
            $target_file = $target_dir . uniqid() . "_" . $file_name;

            if (move_uploaded_file($tmp_name, $target_file)) {
                $uploaded_images[] = $target_file; // Store file path
            } else {
                echo "Error uploading file: " . $file_name;
                exit;
            }
        }
    }

    // Convert uploaded images array to JSON
    $company_logos_json = json_encode($uploaded_images);

    // Insert data into the database
    $sql = "INSERT INTO placements 
            (college_name, course, average_package, minimum_package, placement_rate, participated_students, accepted_offers, total_recruiters, company_logos)
            VALUES ('$college_name', '$course_name', '$average_package', '$minimum_package', '$placement_rate', '$participated_students', '$accepted_offers', '$total_recruiters', '$company_logos_json')";

    if ($conn->query($sql) === TRUE) {
        echo "Placement details added successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>
