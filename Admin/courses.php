<?php
// Database connection
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

// Fetch states from the database
$states = [];
$sql_states = "SELECT state_name FROM states";
$result_states = $conn->query($sql_states);

if ($result_states && $result_states->num_rows > 0) {
    while ($row = $result_states->fetch_assoc()) {
        $states[] = $row;
    }
} else {
    echo "Error fetching states: " . $conn->error;
}

// Fetch courses from the database
$courses = [];
$sql_courses = "SELECT id, course_name, course_image, course_explanation, state_name FROM courses_name";
$result_courses = $conn->query($sql_courses);

if ($result_courses && $result_courses->num_rows > 0) {
    while ($row = $result_courses->fetch_assoc()) {
        $courses[] = $row;
    }
} else {
    echo "Error fetching courses: " . $conn->error;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Add, Edit, and Delete Courses</title>
    <link rel="icon" type="image/x-icon" href="../img/college.png">

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 50%;
            margin: 50px auto;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        h1, h2 {
            text-align: center;
        }

        label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
        }

        input[type="text"], textarea, input[type="file"], select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button {
            padding: 8px 16px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }

        .state-list, .course-list {
            margin-top: 30px;
            padding: 15px;
            background-color: #f9f9f9;
            border-radius: 8px;
        }

        .course-item {
            padding: 8px;
            background-color: #e9e9e9;
            margin-bottom: 5px;
            border-radius: 4px;
        }

        .course-item img {
            max-width: 100px;
            height: auto;
            margin-right: 10px;
            display: inline-block;
            vertical-align: middle;
        }

        .course-details {
            display: inline-block;
            vertical-align: middle;
        }

        .edit-btn, .delete-btn {
            margin: 5px;
            padding: 5px 10px;
            font-size: 14px;
        }
            /* Main Content Area */
            .main-content {
            margin-left: 250px;
            /* padding: 20px; */
            width: calc(100% - 250px);
            overflow-y: auto;
            flex-grow: 1;
        }

        /* Header Styles */
        .header {
            background-color: #343a40;
            color: white;
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header h1 {
            font-size: 1.8rem;
            margin: 0;
        }

        .header .logo {
            width: 40px;
            height: 40px;
            background-color: white;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #007bff;
            font-weight: bold;
            font-size: 1.2rem;
        }

        .header nav a {
            color: white;
            text-decoration: none;
            margin-left: 20px;
            font-size: 1.1rem;
        }

        .header nav a:hover {
            text-decoration: underline;
        }
        .sidebar a{
            padding:16px !important;
        }

    </style>
</head>
<body>
  <!-- Sidebar -->
  <!-- <div class="sidebar">
    <a href="faculty.php" class="btn">Add Faculty</a>
        <a href="courses.php" class="btn">Add Courses</a>
        <a href="states.php" class="btn">Add States</a>
        <a href="add_college.php" class="btn">Add College</a>
        <a href="about_college.php" class="btn">Add About College</a>
        <a href="cutoff.php" class="btn">Add Cutoff</a>
        <a href="rankings.php" class="btn">Add Rankings</a>
        <a href="admission.php" class="btn">Add Admission</a>
        <a href="placement.php" class="btn">Add Placement</a>
        <a href="scholarship.php" class="btn">Add Scholarship</a>
        <a href="fees.php" class="btn">Add Fees</a>
        <a href="infrastructure.php" class="btn">Add Infrastructure</a>
        <a href="news.php" class="btn">Add News</a>
    </div> -->
    <?php include('sidebar.php')?>

      <!-- Main Content -->
      <div class="main-content">
        <!-- Header Section -->
        <div class="header">
            <div class="logo">CI</div>
            <h1>Admin Dashboard</h1>
            <nav>
                <a href="../index.html">Home</a>
        
                <a href="../login.php">Logout</a>
            </nav>
        </div>
<div class="container">
    <h1>Admin Panel - Add/Edit Course</h1>
    <form action="../backend/courses.php" method="POST" enctype="multipart/form-data">
        <!-- Hidden field for course ID -->
        <input type="hidden" name="course_id" id="course_id">

        <!-- Course Image Upload -->
        <label for="course_image">Course Image</label>
        <input type="file" name="course_image" id="course_image" accept="image/*">

        <!-- Course Name -->
        <label for="course_name">Course Name</label>
        <input type="text" name="course_name" id="course_name" required>

        <!-- Course Description -->
        <label for="course_explanation">Course Description</label>
        <textarea name="course_explanation" id="course_explanation" rows="5" required></textarea>

        <!-- State Selection -->
        <label for="state_name">Select State</label>
        <select name="state_name" id="state_name" required>
            <option value="">Choose a state</option>
            <?php foreach ($states as $state): ?>
                <option value="<?= htmlspecialchars($state['state_name']); ?>">
                    <?= htmlspecialchars($state['state_name']); ?>
                </option>
            <?php endforeach; ?>
        </select>

        <!-- Submit Buttons -->
        <button type="submit" name="action" value="add">Add Course</button>
        <button type="submit" name="action" value="edit">Update Course</button>
    </form>

    <!-- Display Fetched Courses -->
    <div class="course-list">
        <h2>Available Courses</h2>
        <?php if (!empty($courses)): ?>
            <?php foreach ($courses as $course): ?>
                <div class="course-item">
                    <img src="<?= htmlspecialchars($course['course_image']); ?>" alt="Course Image">
                    <div class="course-details">
                        <strong>Name:</strong> <?= htmlspecialchars($course['course_name']); ?><br>
                        <strong>Description:</strong> <?= htmlspecialchars($course['course_explanation']); ?><br>
                        <strong>State:</strong> <?= htmlspecialchars($course['state_name']); ?><br>
                        <button 
                            class="edit-btn"
                            onclick='redirectToEditPage(<?= json_encode($course); ?>)'>Edit</button>
                        <form action="../backend/courses.php" method="POST" style="display:inline;">
                            <input type="hidden" name="course_id" value="<?= $course['id']; ?>">
                            <button class="delete-btn" type="submit" name="action" value="delete">Delete</button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No courses available.</p>
        <?php endif; ?>
    </div>
</div>

<script>
    function redirectToEditPage(course) {
        // Redirect and pass data to form via query parameters
        const url = new URL(window.location.href);
        url.searchParams.set('id', course.id);
        url.searchParams.set('name', course.course_name);
        url.searchParams.set('explanation', course.course_explanation);
        url.searchParams.set('state', course.state_name);
        window.location.href = url;
    }

    // Auto-populate form if query parameters are present
    const params = new URLSearchParams(window.location.search);
    if (params.has('id')) {
        document.getElementById('course_id').value = params.get('id');
        document.getElementById('course_name').value = params.get('name');
        document.getElementById('course_explanation').value = params.get('explanation');
        document.getElementById('state_name').value = params.get('state');
    }
</script>

</body>
</html>
