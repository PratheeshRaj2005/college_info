<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "reglog";

$message = "Register successful";

// Create connection 
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if POST request has all required fields
if (isset($_POST['email'], $_POST['name'], $_POST['password'], $_POST['user_type'])) {
    // Sanitize user inputs to prevent XSS and SQL injection
    $email = $conn->real_escape_string($_POST['email']);
    $name = $conn->real_escape_string($_POST['name']);
    $password = $conn->real_escape_string($_POST['password']); // Consider hashing for real-world usage
    $user_type = $conn->real_escape_string($_POST['user_type']); // Get user type

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || !preg_match('/@gmail\.com$/', $email)) {
        echo "<script>alert('Invalid email format. Please provide a valid Gmail address.'); window.history.back();</script>";
        exit();
    }

    // Validate name (only allows alphabetic characters and spaces)
    if (!preg_match("/^[a-zA-Z ]+$/", $name)) {
        echo "<script>alert('Invalid username. Username should contain only letters and spaces.'); window.history.back();</script>";
        exit();
    }

    // Validate user type (must be either 'admin' or 'user')
    if ($user_type !== 'admin' && $user_type !== 'user') {
        echo "<script>alert('Invalid user type selected. Please select a valid option.'); window.history.back();</script>";
        exit();
    }

    // Prepare SQL query using prepared statements
    $sql = $conn->prepare("INSERT INTO register (email, name, password, user_type) VALUES (?, ?, ?, ?)");

    // Bind parameters as strings ("ssss" means all parameters are strings)
    $sql->bind_param("ssss", $email, $name, $password, $user_type);

    // Execute the query and check for success
    if ($sql->execute()) {
        echo "<script type='text/javascript'>alert('$message');window.location.href='../login.php';</script>";
    } else {
        echo "Error: " . $sql->error;
    }

    // Close statement and connection
    $sql->close();
} else {
    echo "Error: Missing required fields.";
}

$conn->close();
?>
