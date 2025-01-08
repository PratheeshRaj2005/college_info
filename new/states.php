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

// Fetch all states from the database
$states = [];
$sql = "SELECT state_name FROM states"; // Fetch all state names
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $states[] = $row['state_name'];
    }
}

// Fetch all courses from the courses_name table
$courses = [];
$sql_courses = "SELECT course_name FROM courses_name"; // Fetch all course names
$course_result = $conn->query($sql_courses);

if ($course_result->num_rows > 0) {
    while ($row = $course_result->fetch_assoc()) {
        $courses[] = $row['course_name'];
    }
}

// Fetch colleges based on selected state and course
$colleges = [];
if (isset($_GET['state']) && isset($_GET['course'])) {
    $state = $_GET['state'];
    $course = $_GET['course'];

    // Fetch colleges based on selected state and course
    $sql_colleges = "SELECT college_name FROM colleges WHERE state = '$state' AND course_name = '$course'"; 
    $college_result = $conn->query($sql_colleges);

    if ($college_result->num_rows > 0) {
        while ($row = $college_result->fetch_assoc()) {
            $colleges[] = $row['college_name'];
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select State, Course, and View Colleges</title>
    <link rel="icon" type="image/x-icon" href="../img/college.png">

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
        .colleges-list {
            margin-top: 20px;
        }
        .college {
            padding: 10px;
            border: 1px solid #ddd;
            margin-bottom: 5px;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Select a State and Course</h1>
    <form action="college.php" method="GET">
        <label for="states">Select a State:</label>
        <select name="state" id="states" required>
            <option value="">Choose a state</option>
            <?php foreach ($states as $state): ?>
                <option value="<?= htmlspecialchars($state); ?>" <?= (isset($_GET['state']) && $_GET['state'] == $state) ? 'selected' : ''; ?>>
                    <?= htmlspecialchars($state); ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="courses">Select a Course:</label>
        <select name="course" id="courses" required>
            <option value="">Choose a course</option>
            <?php foreach ($courses as $course): ?>
                <option value="<?= htmlspecialchars($course); ?>" <?= (isset($_GET['course']) && $_GET['course'] == $course) ? 'selected' : ''; ?>>
                    <?= htmlspecialchars($course); ?>
                </option>
            <?php endforeach; ?>
        </select>
        
        <button type="submit">Find Colleges</button>
    </form>

    <?php if (isset($_GET['state']) && isset($_GET['course'])): ?>
        <div class="colleges-list">
            <h2>Colleges offering the course: <?= htmlspecialchars($_GET['course']); ?> in <?= htmlspecialchars($_GET['state']); ?></h2>
            <?php if (count($colleges) > 0): ?>
                <ul>
                    <?php foreach ($colleges as $college): ?>
                        <li class="college"><?= htmlspecialchars($college); ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>No colleges found for this course in the selected state.</p>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>

</body>
</html>
