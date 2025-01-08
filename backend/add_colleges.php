<?php
// Start the session if needed
session_start();

// Database connection
$host = 'localhost'; // Change if necessary
$db = 'reglog'; // Database name
$user = 'root'; // Database username
$password = ''; // Database password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and sanitize form data
    $collegeName = htmlspecialchars(trim($_POST['college_name']));
    $aboutCollege = htmlspecialchars(trim($_POST['about_college']));
    $state = htmlspecialchars(trim($_POST['state']));
    $course = htmlspecialchars(trim($_POST['course']));
    $file = $_FILES['college_image'];

    // Validate inputs
    if (empty($collegeName) || empty($aboutCollege) || empty($state) || empty($course) || empty($file)) {
        die("All fields are required.");
    }

    // File upload handling
    $uploadDir = '../uploads/'; // Directory for storing uploaded images
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true); // Create directory if it doesn't exist
    }

    $fileName = basename($file['name']);
    $fileTmpPath = $file['tmp_name'];
    $fileType = pathinfo($fileName, PATHINFO_EXTENSION);
    $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];

    if (!in_array(strtolower($fileType), $allowedTypes)) {
        die("Invalid file type. Only JPG, JPEG, PNG, and GIF are allowed.");
    }

    $uniqueFileName = uniqid('college_', true) . '.' . $fileType;
    $destinationPath = $uploadDir . $uniqueFileName;

    if (!move_uploaded_file($fileTmpPath, $destinationPath)) {
        die("Failed to upload file.");
    }

    // Insert data into the database
    $sql = "INSERT INTO colleges (college_name, college_image, about_college, state, course) 
            VALUES (:college_name, :college_image, :about_college, :state, :course)";
    $stmt = $pdo->prepare($sql);

    try {
        $stmt->execute([
            ':college_name' => $collegeName,
            ':college_image' => $uniqueFileName,
            ':about_college' => $aboutCollege,
            ':state' => $state,
            ':course' => $course,
        ]);
        echo "College added successfully!";
    } catch (PDOException $e) {
        die("Error adding college: " . $e->getMessage());
    }
} else {
    die("Invalid request method.");
}
?>
