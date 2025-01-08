<?php
// Start the session if needed
session_start();

// Database connection (adjust the credentials accordingly)
$host = 'localhost';
$db = 'your_database_name';
$user = 'your_database_user';
$password = 'your_database_password';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and validate inputs
    $courseName = trim($_POST['course_name']);
    $courseExplanation = trim($_POST['course_explanation']);
    $file = $_FILES['course_image'];

    // Input validation
    if (empty($courseName) || empty($courseExplanation) || empty($file)) {
        die("All fields are required.");
    }

    // File upload handling
    $uploadDir = '../uploads/'; // Ensure this directory exists and is writable
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

    $uniqueFileName = uniqid('course_', true) . '.' . $fileType;
    $destinationPath = $uploadDir . $uniqueFileName;

    if (!move_uploaded_file($fileTmpPath, $destinationPath)) {
        die("Failed to upload file.");
    }

    // Save course data to the database
    $sql = "INSERT INTO courses (course_name, course_description, course_image) VALUES (:name, :description, :image)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':name', $courseName);
    $stmt->bindParam(':description', $courseExplanation);
    $stmt->bindParam(':image', $uniqueFileName);

    try {
        $stmt->execute();
        echo "Course added successfully!";
    } catch (PDOException $e) {
        die("Error saving course: " . $e->getMessage());
    }
}
?>
