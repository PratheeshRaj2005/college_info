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

// Get search query from the form (if exists)
$course_name = isset($_GET['course_name']) ? $_GET['course_name'] : '';

// Prepare SQL query to fetch courses based on the search
$sql = "SELECT course_name, course_explanation, course_image FROM courses_name WHERE course_name LIKE ?";
$stmt = $conn->prepare($sql);
$searchQuery = "%" . $course_name . "%";
$stmt->bind_param("s", $searchQuery);

// Execute the query
$stmt->execute();
$result = $stmt->get_result();

// Close the connection
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Search</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 80%;
            margin: 20px auto;
            padding: 20px;
        }
        .cards-container {
            display: flex;
            gap: 20px;
        }
        .card {
            width: 100%;
            border: 1px solid #ccc;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .card h3 {
            margin-bottom: 15px;
        }
        .card p {
            color: #555;
        }
        .card img {
            width: 100%;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Search for Courses</h1>

    <!-- Display Courses -->
    <div class="cards-container">
        <?php if ($result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
                <a href="states.php?course_name=<?= urlencode($row['course_name']); ?>">
                    <div class="card">
                        <img src="<?= htmlspecialchars($row['course_image']); ?>" alt="Course Image">
                        <h3><?= htmlspecialchars($row['course_name']); ?></h3>
                        <p><?= htmlspecialchars($row['course_explanation']); ?></p>
                    </div>
                </a>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No courses found.</p>
        <?php endif; ?>
    </div>
</div>

</body>
</html>
