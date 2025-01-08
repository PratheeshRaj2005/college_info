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

// Check if state is set in the URL
if (isset($_GET['state'])) {
    $selected_state = $_GET['state'];

    // Fetch courses based on the selected state
    $courses = [];
    $sql = "SELECT id, course_name, course_explanation, course_image FROM courses_name WHERE state_name = ?";  // Ensure "state_name" is the correct column in your courses table
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $selected_state); // Bind the state parameter to the SQL query
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $courses[] = $row; // Store the entire row (course id, name, explanation, and image)
        }
    } else {
        $no_courses_message = "No courses found for the selected state.";
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
    <title>Courses in <?php echo htmlspecialchars($selected_state); ?></title>
    <link rel="icon" type="image/x-icon" href="../img/college.png">

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            width: 80%;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
        }
        .courses-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr); /* 3 columns */
            gap: 20px;
            margin-top: 20px;
        }
        .course-container {
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background-color: #fff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
            cursor: pointer;
        }
        .course-container:hover {
            transform: translateY(-5px); /* Slight hover effect */
        }
        .course-container h3 {
            font-size: 18px;
            color: #333;
            margin-bottom: 10px;
        }
        .course-container p {
            font-size: 14px;
            color: #555;
            margin-bottom: 15px;
        }
        .course-container img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .no-courses {
            text-align: center;
            color: #888;
            font-size: 18px;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Courses in <?php echo htmlspecialchars($selected_state); ?></h1>

    <?php if (!empty($courses)): ?>
        <div class="courses-grid">
            <?php foreach ($courses as $course): ?>
                <!-- Wrap each course container in an anchor tag that links to college.php -->
                <a href="college.php?id=<?php echo htmlspecialchars($course['id']); ?>" style="text-decoration: none;">
                    <div class="course-container">
                        <h3><?php echo htmlspecialchars($course['course_name']); ?></h3>
                            <img src="<?php echo htmlspecialchars($course['course_image']); ?>" alt="Course Image" />
                            <p><?php echo htmlspecialchars($course['course_explanation']); ?></p>
                        <?php if (!empty($course['course_image'])): ?>
                        <?php endif; ?>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p class="no-courses"><?php echo isset($no_courses_message) ? htmlspecialchars($no_courses_message) : ''; ?></p>
    <?php endif; ?>
</div>

</body>
</html>
