<?php
// Database configuration
$host = "localhost";
$username = "root";
$password = "";
$dbname = "reglog";

// Create a connection to the database
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize feedback message
$feedback = "";

// Fetch colleges for the dropdown
$colleges_query = "SELECT DISTINCT college_name FROM colleges";
$colleges_result = $conn->query($colleges_query);

// Fetch courses for the selected college
$courses_result = [];
if (isset($_GET['college_name'])) {
    $selected_college = $conn->real_escape_string($_GET['college_name']);
    $courses_query = "SELECT DISTINCT course_name FROM colleges WHERE college_name = ?";
    $stmt = $conn->prepare($courses_query);
    $stmt->bind_param('s', $selected_college);
    $stmt->execute();
    $courses_result = $stmt->get_result();
}

// Handle form submission for adding or updating news
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_cutoff'])) {
    $college_name = $conn->real_escape_string($_POST['college_name']);
    $course_name = $conn->real_escape_string($_POST['course_name']);
    $news_title = $conn->real_escape_string($_POST['news_title']);
    $about_news = $conn->real_escape_string($_POST['about_news']);
    $news_date = $conn->real_escape_string($_POST['news_date']);
    $news_time = $conn->real_escape_string($_POST['news_time']);

    if (isset($_POST['edit_id']) && $_POST['edit_id'] != '') {
        $edit_id = $_POST['edit_id'];
        $sql = "UPDATE news_updates SET college_name=?, course=?, news_title=?, about_news=?, news_date=?, news_time=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ssssssi', $college_name, $course_name, $news_title, $about_news, $news_date, $news_time, $edit_id);
        $stmt->execute();
        $feedback = "<div class='success'>News details updated successfully!</div>";
    } else {
        $sql = "INSERT INTO news_updates (college_name, course, news_title, about_news, news_date, news_time) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ssssss', $college_name, $course_name, $news_title, $about_news, $news_date, $news_time);
        $stmt->execute();
        $feedback = "<div class='success'>News details added successfully!</div>";
    }
}

// Fetch all news updates
$news_query = "SELECT * FROM news_updates ORDER BY news_date DESC, news_time DESC";
$news_result = $conn->query($news_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add/Edit Cutoff</title>
    <link rel="icon" type="image/x-icon" href="../img/college.png">

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f2f5;
            margin:0;
        }
        header {
            background-color: #2c3e50;
            color: white;
            padding: 15px;
            text-align: center;
        }
        .container {
            width: 60%;
            margin: 50px auto;
            background-color: #fff;
            padding: 30px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
        }
        select, input, textarea, button {
            width: 100%;
            padding: 12px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            background-color: #3498db;
            color: white;
            cursor: pointer;
            padding: 10px 15px;
        }
        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
        }
        th {
            background-color: #3498db;
            color: white;
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

        /* Sidebar Styles */
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
<!-- <header>News and Updates Management</header> -->

<div class="container">
    <form action="" method="GET">
        <label for="college_name">College</label>
        <select name="college_name" id="college_name" onchange="this.form.submit()">
            <option value="">Select a College</option>
            <?php
            if ($colleges_result->num_rows > 0) {
                while ($row = $colleges_result->fetch_assoc()) {
                    $selected = isset($_GET['college_name']) && $_GET['college_name'] == $row['college_name'] ? 'selected' : '';
                    echo "<option value='" . $row['college_name'] . "' $selected>" . $row['college_name'] . "</option>";
                }
            }
            ?>
        </select>
    </form>

    <form action="" method="POST">
        <label for="course_name">Course</label>
        <select name="course_name" id="course_name">
            <option value="">Select a Course</option>
            <?php
            if ($courses_result && $courses_result->num_rows > 0) {
                while ($row = $courses_result->fetch_assoc()) {
                    echo "<option value='" . $row['course_name'] . "'>" . $row['course_name'] . "</option>";
                }
            }
            ?>
        </select>

        <label for="news_title">News Title</label>
        <input type="text" name="news_title" id="news_title" required>

        <label for="about_news">About the News</label>
        <textarea name="about_news" id="about_news" rows="4" required></textarea>

        <label for="news_date">Date</label>
        <input type="date" name="news_date" id="news_date" required>

        <label for="news_time">Time</label>
        <input type="time" name="news_time" id="news_time" required>

        <button type="submit" name="submit_cutoff">Submit</button>
    </form>

    <h2>News Updates</h2>
    <table>
        <thead>
        <tr>
            <th>College</th>
            <th>Course</th>
            <th>News Title</th>
            <th>Date</th>
            <th>Time</th>
        </tr>
        </thead>
        <tbody>
        <?php while ($row = $news_result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['college_name'] ?></td>
                <td><?= $row['course'] ?></td>
                <td><?= $row['news_title'] ?></td>
                <td><?= $row['news_date'] ?></td>
                <td><?= $row['news_time'] ?></td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>

</body>
</html>
