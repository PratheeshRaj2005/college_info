<?php
// Database configuration
$host = "localhost"; // Change if using a different host
$username = "root"; // Your database username
$password = ""; // Your database password
$dbname = "reglog"; // The database name

// Create a connection to the database
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize feedback message
$feedback = "";

// Fetch colleges from the database (used for the initial selection)
$colleges_query = "SELECT DISTINCT college_name FROM colleges";
$colleges_result = $conn->query($colleges_query);

// Initialize courses array
$courses_result = [];
$selected_college = '';
if (isset($_POST['college_name'])) {
    $selected_college = $_POST['college_name'];
    // Fetch courses for the selected college
    $courses_query = "SELECT DISTINCT course_name FROM colleges WHERE college_name = '$selected_college'";
    $courses_result = $conn->query($courses_query);
}

// Handle form submission for adding and updating
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_cutoff'])) {
    $college_name = $_POST['college_name'];
    $course_name = $_POST['course_name'];
    $department_name = $_POST['department_name'];
    $prev_cutoff = $_POST['prev_cutoff'];
    $current_cutoff = $_POST['current_cutoff'];

    // Validate and sanitize input
    $college_name = $conn->real_escape_string($college_name);
    $course_name = $conn->real_escape_string($course_name);
    $department_name = $conn->real_escape_string($department_name);
    $prev_cutoff = floatval($prev_cutoff);
    $current_cutoff = floatval($current_cutoff);

    // Insert or Update data into the database
    if (isset($_POST['id']) && !empty($_POST['id'])) {
        // Update existing record
        $id = $_POST['id'];
        $sql = "UPDATE cutoffs SET college_name = '$college_name', course_name = '$course_name', 
                department_name = '$department_name', prev_cutoff = $prev_cutoff, current_cutoff = $current_cutoff 
                WHERE id = $id";
    } else {
        // Insert new record
        $sql = "INSERT INTO cutoffs (college_name, course_name, department_name, prev_cutoff, current_cutoff) 
                VALUES ('$college_name', '$course_name', '$department_name', $prev_cutoff, $current_cutoff)";
    }

    if ($conn->query($sql) === TRUE) {
        $feedback = "<div class='success'>Cutoff details added/updated successfully!</div>";
    } else {
        $feedback = "<div class='error'>Error: " . $conn->error . "</div>";
    }
}

// Handle Delete request
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    if ($conn->query("DELETE FROM cutoffs WHERE id = $id") === TRUE) {
        $feedback = "<div class='success'>Record deleted successfully!</div>";
    } else {
        $feedback = "<div class='error'>Error deleting record: " . $conn->error . "</div>";
    }
}

// Fetch all cutoffs for display
$cutoffs_query = "SELECT * FROM cutoffs";
$cutoffs_result = $conn->query($cutoffs_query);

// Fetch the data for editing if an edit request is made
$edit_data = [];
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $edit_query = "SELECT * FROM cutoffs WHERE id = $id";
    $edit_result = $conn->query($edit_query);
    if ($edit_result->num_rows > 0) {
        $edit_data = $edit_result->fetch_assoc();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add/Update Cutoff</title>
    <link rel="icon" type="image/x-icon" href="../img/college.png">

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f7f6;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 60%;
            margin: 50px auto;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }
        h1 {
            font-size: 2rem;
            color: #333;
            text-align: center;
            margin-bottom: 30px;
        }
        label {
            font-weight: bold;
            margin-bottom: 5px;
            color: #555;
        }
        select, input {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 1rem;
        }
        select:focus, input:focus {
            border-color: #4CAF50;
            outline: none;
            box-shadow: 0 0 5px rgba(76, 175, 80, 0.5);
        }
        button {
            width: 100%;
            padding: 12px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1rem;
        }
        button:hover {
            background-color: #45a049;
        }
        .success {
            color: #4CAF50;
            background-color: #e8f5e9;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #4CAF50;
            border-radius: 5px;
        }
        .error {
            color: #f44336;
            background-color: #fdecea;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #f44336;
            border-radius: 5px;
        }
        .table-container {
            margin-top: 30px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #4CAF50;
            color: white;
        }
        .actions {
            display: flex;
            gap: 10px;
        }
        .actions a {
            color: #fff;
            text-decoration: none;
            padding: 8px 12px;
            border-radius: 5px;
        }
        .edit {
            background-color: #007BFF;
        }
        .edit:hover {
            background-color: #0056b3;
        }
        .delete {
            background-color: #dc3545;
        }
        .delete:hover {
            background-color: #c82333;
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
            position: fixed;
            width: 100%;
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

        /* Sidebar Styles
        .sidebar {
            width: 250px;
            background-color: #007bff;
            color: white;
            padding-top: 20px;
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            overflow-y: auto;
            box-shadow: 4px 0 8px rgba(0, 0, 0, 0.1);
        }

        .sidebar a {
            display: block;
            padding: 15px;
            color: white;
            text-decoration: none;
            font-size: 1.1rem;
            transition: background-color 0.3s;
        }

        .sidebar a:hover {
            background-color: #0056b3;
        } */
.sidebar a{
    padding: 16px !important;
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
        <h1><?= isset($_GET['edit']) ? 'Edit Cutoff' : 'Add Cutoff' ?></h1>

        <?= $feedback; ?>

        <form action="" method="POST">
            <input type="hidden" name="id" value="<?= isset($_GET['edit']) ? $edit_data['id'] : '' ?>">
            
            <!-- Select College Name -->
            <label for="college_name">College</label>
            <select name="college_name" id="college_name" required onchange="this.form.submit()">
                <option value="">Select a College</option>
                <?php
                if ($colleges_result->num_rows > 0) {
                    while($row = $colleges_result->fetch_assoc()) {
                        $selected = (isset($_POST['college_name']) && $_POST['college_name'] == $row['college_name']) || 
                                    (isset($edit_data['college_name']) && $edit_data['college_name'] == $row['college_name']) ? "selected" : "";
                        echo "<option value='" . $row['college_name'] . "' $selected>" . $row['college_name'] . "</option>";
                    }
                }
                ?>
            </select>

            <!-- Select Course -->
            <label for="course_name">Course</label>
            <select name="course_name" id="course_name" required>
                <option value="">Select a Course</option>
                <?php
                if ($selected_college || isset($edit_data['college_name'])) {
                    $college = isset($edit_data['college_name']) ? $edit_data['college_name'] : $selected_college;
                    $courses_result = $conn->query("SELECT DISTINCT course_name FROM colleges WHERE college_name = '$college'");

                    while ($row = $courses_result->fetch_assoc()) {
                        $selected = (isset($edit_data['course_name']) && $edit_data['course_name'] == $row['course_name']) ? "selected" : "";
                        echo "<option value='" . $row['course_name'] . "' $selected>" . $row['course_name'] . "</option>";
                    }
                }
                ?>
            </select>

            <!-- Department Name -->
            <label for="department_name">Department</label>
            <input type="text" name="department_name" id="department_name" placeholder="Enter department name" required value="<?= isset($edit_data['department_name']) ? $edit_data['department_name'] : '' ?>">

            <!-- Previous Year Cutoff -->
            <label for="prev_cutoff">Previous Cutoff (%)</label>
            <input type="number" name="prev_cutoff" id="prev_cutoff" placeholder="Enter previous cutoff" required value="<?= isset($edit_data['prev_cutoff']) ? $edit_data['prev_cutoff'] : '' ?>">

            <!-- Current Cutoff -->
            <label for="current_cutoff">Current Cutoff (%)</label>
            <input type="number" name="current_cutoff" id="current_cutoff" placeholder="Enter current cutoff" required value="<?= isset($edit_data['current_cutoff']) ? $edit_data['current_cutoff'] : '' ?>">

            <!-- Submit Button -->
            <button type="submit" name="submit_cutoff">Submit</button>
        </form>
    </div>

    <div class="container table-container">
        <h2>Current Cutoffs</h2>
        <table>
            <thead>
                <tr>
                    <th>College</th>
                    <th>Course</th>
                    <th>Department</th>
                    <th>Previous Cutoff (%)</th>
                    <th>Current Cutoff (%)</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $cutoffs_result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['college_name'] ?></td>
                        <td><?= $row['course_name'] ?></td>
                        <td><?= $row['department_name'] ?></td>
                        <td><?= $row['prev_cutoff'] ?></td>
                        <td><?= $row['current_cutoff'] ?></td>
                        <td class="actions">
                            <a href="?edit=<?= $row['id'] ?>" class="edit">Edit</a>
                            <a href="?delete=<?= $row['id'] ?>" class="delete" onclick="return confirm('Are you sure you want to delete this record?')">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

</body>
</html>
