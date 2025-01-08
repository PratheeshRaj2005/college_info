<?php
// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "reglog";

// Create a connection to the database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve the state name from the form
    $state_name = $conn->real_escape_string(trim($_POST['state_name']));

    // Check if the state name is not empty
    if (!empty($state_name)) {
        // Prepare and execute the SQL statement to insert the state
        $stmt = $conn->prepare("INSERT INTO states (state_name) VALUES (?)");
        $stmt->bind_param("s", $state_name);

        if ($stmt->execute()) {
            // Success: Redirect to the form with a success message
            header("Location: ../new/states.php");
            exit();
        } else {
            // Error: Redirect to the form with an error message
            header("Location: ../frontend/add_state_form.php?error=1");
            exit();
        }

        $stmt->close();
    } else {
        // Error: Redirect to the form with an error message for empty input
        header("Location: ../frontend/add_state_form.php?error=empty");
        exit();
    }
}

// Close the database connection
$conn->close();
?>
