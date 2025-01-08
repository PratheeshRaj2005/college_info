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
if (isset($_POST['college_name'])) {
    // Fetch courses for the selected college
    $college_name = $_POST['college_name'];
    $courses_query = "SELECT DISTINCT course_name FROM colleges WHERE college_name = '$college_name'";
    $courses_result = $conn->query($courses_query);
}

// Handle form submission for adding or editing admission details
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['submit_admission'])) {
        // Handle adding a new admission record
        $college_name = $_POST['college_name'];
        $course_name = $_POST['course_name'];
        $admission_description = isset($_POST['admission_description']) ? $_POST['admission_description'] : "";
        $particulars = $_POST['particulars'];
        $highlights = $_POST['highlights'];

        // Validate and sanitize input
        $college_name = $conn->real_escape_string($college_name);
        $course_name = $conn->real_escape_string($course_name);
        $admission_description = $conn->real_escape_string($admission_description);
        $particulars = $conn->real_escape_string($particulars);
        $highlights = $conn->real_escape_string($highlights);

        // Insert data into the database
        $sql = "INSERT INTO admission_details (college_name, course, admission_description, particulars, highlights) 
                VALUES ('$college_name', '$course_name', '$admission_description', '$particulars', '$highlights')";

        if ($conn->query($sql) === TRUE) {
            $feedback = "<div class='success'>Admission details added successfully!</div>";
        } else {
            $feedback = "<div class='error'>Error: " . $conn->error . "</div>";
        }
    } elseif (isset($_POST['update_admission'])) {
        // Handle updating an existing admission record
        $id = $_POST['id'];
        $college_name = $_POST['college_name'];
        $course_name = $_POST['course_name'];
        $admission_description = $_POST['admission_description'];
        $particulars = $_POST['particulars'];
        $highlights = $_POST['highlights'];

        // Validate and sanitize input
        $id = $conn->real_escape_string($id);
        $college_name = $conn->real_escape_string($college_name);
        $course_name = $conn->real_escape_string($course_name);
        $admission_description = $conn->real_escape_string($admission_description);
        $particulars = $conn->real_escape_string($particulars);
        $highlights = $conn->real_escape_string($highlights);

        // Update data in the database
        $sql = "UPDATE admission_details SET college_name='$college_name', course='$course_name', admission_description='$admission_description', particulars='$particulars', highlights='$highlights' WHERE id='$id'";

        if ($conn->query($sql) === TRUE) {
            $feedback = "<div class='success'>Admission details updated successfully!</div>";
        } else {
            $feedback = "<div class='error'>Error: " . $conn->error . "</div>";
        }
    } elseif (isset($_POST['delete_admission'])) {
        // Handle deleting an admission record
        $id = $_POST['id'];

        // Validate and sanitize input
        $id = $conn->real_escape_string($id);

        // Delete the record from the database
        $sql = "DELETE FROM admission_details WHERE id='$id'";

        if ($conn->query($sql) === TRUE) {
            $feedback = "<div class='success'>Admission details deleted successfully!</div>";
        } else {
            $feedback = "<div class='error'>Error: " . $conn->error . "</div>";
        }
    }
}

// Fetch all admission details
$admission_query = "SELECT * FROM admission_details";
$admission_result = $conn->query($admission_query);

// Fetch details for editing
$edit_admission = null;
if (isset($_POST['edit_admission'])) {
    $id = $_POST['id'];
    $edit_query = "SELECT * FROM admission_details WHERE id = '$id'";
    $edit_result = $conn->query($edit_query);
    if ($edit_result->num_rows > 0) {
        $edit_admission = $edit_result->fetch_assoc();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Add Admission Details</title>
    <link rel="icon" type="image/x-icon" href="../img/college.png">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            /* padding: 20px; */
        }

        .container {
            max-width: 800px;
            margin: auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        textarea,
        select,
        input[type="text"],
        button {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button {
            background-color: #007BFF;
            color: white;
            border: none;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }

        table {
            margin-top: 30px;
        }

        .success {
            color: #4CAF50;
            background-color: #e8f5e9;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #4CAF50;
            border-radius: 4px;
        }

        .error {
            color: #f44336;
            background-color: #fdecea;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #f44336;
            border-radius: 4px;
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
        <h1><?php echo $edit_admission ? "Edit Admission Details" : "Add Admission Details"; ?></h1>
        <?= $feedback; ?>
        <form action="" method="POST">
            <!-- Select College Name -->
            <label for="college_name">Select College</label>
            <select name="college_name" id="college_name" required onchange="this.form.submit()">
                <option value="">Select a College</option>
                <?php
                if ($colleges_result->num_rows > 0) {
                    while ($row = $colleges_result->fetch_assoc()) {
                        $selected = (isset($edit_admission) && $edit_admission['college_name'] == $row['college_name']) ? "selected" : "";
                        echo "<option value='" . $row['college_name'] . "' $selected>" . $row['college_name'] . "</option>";
                    }
                }
                ?>
            </select>

            <!-- Select Course -->
            <label for="course_name">Select Course</label>
            <select name="course_name" id="course_name" required>
                <option value="">Select a Course</option>
                <?php
                // If college is selected, show the corresponding courses
                if (isset($_POST['college_name']) || isset($edit_admission)) {
                    $selected_college = isset($edit_admission) ? $edit_admission['college_name'] : $_POST['college_name'];
                    $courses_query = "SELECT DISTINCT course_name FROM colleges WHERE college_name = '$selected_college'";
                    $courses_result = $conn->query($courses_query);
                    
                    if ($courses_result->num_rows > 0) {
                        while ($row = $courses_result->fetch_assoc()) {
                            $selected = (isset($edit_admission) && $edit_admission['course'] == $row['course_name']) ? "selected" : "";
                            echo "<option value='" . $row['course_name'] . "' $selected>" . $row['course_name'] . "</option>";
                        }
                    }
                }
                ?>
            </select>
            
            <!-- Admission Description -->
            <label for="admission_description">Admission Description</label>
            <textarea name="admission_description" id="admission_description" rows="3" placeholder="Add 2 lines about the admission process"><?php echo $edit_admission['admission_description'] ?? ''; ?></textarea>

            <!-- Particulars -->
            <label for="particulars">Particulars</label>
            <textarea name="particulars" id="particulars" rows="4" required><?php echo $edit_admission['particulars'] ?? ''; ?></textarea>

            <!-- Highlights -->
            <label for="highlights">Highlights</label>
            <textarea name="highlights" id="highlights" rows="4"><?php echo $edit_admission['highlights'] ?? ''; ?></textarea>

            <?php if ($edit_admission): ?>
                <input type="hidden" name="id" value="<?php echo $edit_admission['id']; ?>">
                <button type="submit" name="update_admission">Update Admission</button>
            <?php else: ?>
                <button type="submit" name="submit_admission">Add Admission</button>
            <?php endif; ?>
        </form>

        <!-- Display Admission Details -->
        <h2>Admission Details</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>College Name</th>
                    <th>Course Name</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($admission = $admission_result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $admission['college_name']; ?></td>
                        <td><?php echo $admission['course']; ?></td>
                        <td><?php echo $admission['admission_description']; ?></td>
                        <td>
                            <form action="" method="POST" style="display:inline;">
                                <input type="hidden" name="id" value="<?php echo $admission['id']; ?>">
                                <button type="submit" name="edit_admission">Edit</button>
                            </form>
                            <form action="" method="POST" style="display:inline;">
                                <input type="hidden" name="id" value="<?php echo $admission['id']; ?>">
                                <button type="submit" name="delete_admission" onclick="return confirm('Are you sure you want to delete this record?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>

<?php
$conn->close();
?>
