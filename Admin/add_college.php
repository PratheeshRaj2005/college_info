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

// Fetch courses from the database
$sql_courses = "SELECT DISTINCT course_name FROM courses_name";
$result_courses = $conn->query($sql_courses);

// Fetch states from the database
$sql_states = "SELECT DISTINCT state_name FROM states";
$result_states = $conn->query($sql_states);

// Handle form submission
if (isset($_POST['submit'])) {
    // Collect form data
    $college_name = $conn->real_escape_string($_POST['college_name']);
    $about_college = $conn->real_escape_string($_POST['about_college']);
    $state = $conn->real_escape_string($_POST['state']);
    $course = $conn->real_escape_string($_POST['course']);

    // Handle file upload
    $target_dir = "../uploads/colleges/";
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true); // Create directory if not exists
    }
    $file_name = basename($_FILES["college_image"]["name"]);
    $target_file = $target_dir . time() . "_" . $file_name;
    $upload_ok = true;
    $image_file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Validate file
    $check = getimagesize($_FILES["college_image"]["tmp_name"]);
    if ($check === false) {
        echo "File is not an image.";
        $upload_ok = false;
    }

    // Allow certain file formats
    if (!in_array($image_file_type, ["jpg", "jpeg", "png", "gif"])) {
        echo "Only JPG, JPEG, PNG & GIF files are allowed.";
        $upload_ok = false;
    }

    // Check if $upload_ok is set to false
    if (!$upload_ok) {
        echo "Sorry, your file was not uploaded.";
    } else {
        // Try to upload file
        if (move_uploaded_file($_FILES["college_image"]["tmp_name"], $target_file)) {
            $college_image = $target_file; // Save the file path

            // Insert into database using prepared statement
            $stmt = $conn->prepare("INSERT INTO colleges (college_name, college_image, about_college, state, course_name) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $college_name, $college_image, $about_college, $state, $course);

            if ($stmt->execute()) {
                echo "College added successfully.";
            } else {
                echo "Error: " . $stmt->error;
            }
            $stmt->close();
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
}

// Fetch data from `colleges` table
$sql_colleges = "SELECT * FROM colleges";
$result_colleges = $conn->query($sql_colleges);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Manage Colleges</title>
    <link rel="icon" type="image/x-icon" href="../img/college.png">

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 80%;
            margin: 50px auto;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
        }
        select, input, textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            width: 100%;
            padding: 12px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
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
   <?php include('sidebar.php') ?>
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
    <h1>Admin Panel - Add College</h1>
    <form action="" method="POST" enctype="multipart/form-data">

        <!-- Course Dropdown -->
        <label for="course">Select Course</label>
        <select name="course" id="course" required>
            <option value="">Select a Course</option>
            <?php
            if ($result_courses->num_rows > 0) {
                while ($row = $result_courses->fetch_assoc()) {
                    echo "<option value='" . htmlspecialchars($row['course_name']) . "'>" . htmlspecialchars($row['course_name']) . "</option>";
                }
            } else {
                echo "<option value=''>No courses available</option>";
            }
            ?>
        </select>

        <!-- State Dropdown -->
        <label for="state">Select State</label>
        <select name="state" id="state" required>
            <option value="">Select a State</option>
            <?php
            if ($result_states->num_rows > 0) {
                while ($row = $result_states->fetch_assoc()) {
                    echo "<option value='" . htmlspecialchars($row['state_name']) . "'>" . htmlspecialchars($row['state_name']) . "</option>";
                }
            } else {
                echo "<option value=''>No states available</option>";
            }
            ?>
        </select>

        <!-- College Name -->
        <label for="college_name">College Name</label>
        <input type="text" name="college_name" id="college_name" required>

        <!-- College Image -->
        <label for="college_image">College Image</label>
        <input type="file" name="college_image" id="college_image" accept="image/*" required>

        <!-- About College -->
        <label for="about_college">One Line About College</label>
        <textarea name="about_college" id="about_college" rows="3" required></textarea>

        <button type="submit" name="submit">Add College</button>
    </form>

    <h2>Existing Colleges</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>College Name</th>
                <th>Image</th>
                <th>About</th>
                <th>State</th>
                <th>Course</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result_colleges->num_rows > 0) {
                while ($row = $result_colleges->fetch_assoc()) {
                    echo "<tr>
                        <td>" . htmlspecialchars($row['id']) . "</td>
                        <td>" . htmlspecialchars($row['college_name']) . "</td>
                        <td><img src='" . htmlspecialchars($row['college_image']) . "' alt='College Image' width='100'></td>
                        <td>" . htmlspecialchars($row['about_college']) . "</td>
                        <td>" . htmlspecialchars($row['state']) . "</td>
                        <td>" . htmlspecialchars($row['course_name']) . "</td>
                    </tr>";
                }
            } else {
                echo "<tr><td colspan='6'>No colleges found.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

</body>
</html>
