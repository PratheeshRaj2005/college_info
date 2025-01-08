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

// Get course name from URL
$course_name = isset($_GET['course_name']) ? $_GET['course_name'] : '';

// Fetch states offering the selected course
$states = [];
if ($course_name) {
    $stmt = $conn->prepare("SELECT DISTINCT state_name FROM states WHERE course_name = ?");
    $stmt->bind_param("s", $course_name);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $states[] = $row['state_name'];
    }
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select a State</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 60%;
            margin: 50px auto;
            padding: 20px;
        }
        label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
        }
        select {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
        }
        button {
            padding: 10px;
            width: 100%;
            background-color: #007BFF;
            color: white;
            border: none;
            cursor: pointer;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Select a State for <?= htmlspecialchars($course_name); ?></h1>
    <form action="college.php" method="GET">
        <input type="hidden" name="course_name" value="<?= htmlspecialchars($course_name); ?>">
        <label for="state">Select a State:</label>
        <select name="state" id="state" required>
            <option value="">Choose a state</option>
            <?php foreach ($states as $state): ?>
                <option value="<?= htmlspecialchars($state); ?>"><?= htmlspecialchars($state); ?></option>
            <?php endforeach; ?>
        </select>
        <button type="submit">Find Colleges</button>
    </form>
</div>

</body>
</html>
