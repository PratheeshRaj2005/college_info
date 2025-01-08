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

// Handle form submission for adding scholarship
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $college_name = $_POST['college'];
    $course_name = $_POST['course'];
    $scholarship_title = $_POST['scholarship_title'];
    $about_scholarship = $_POST['about_scholarship'];

    // Sanitize input
    $college_name = $conn->real_escape_string($college_name);
    $course_name = $conn->real_escape_string($course_name);
    $scholarship_title = $conn->real_escape_string($scholarship_title);
    $about_scholarship = $conn->real_escape_string($about_scholarship);

    // Insert scholarship details into the database
    $sql = "INSERT INTO scholarships (college, course, scholarship, about_scholarship) 
            VALUES ('$college_name', '$course_name', '$scholarship_title', '$about_scholarship')";

    if ($conn->query($sql) === TRUE) {
        $feedback = "<div class='alert alert-success'>Scholarship added successfully!</div>";
    } else {
        $feedback = "<div class='alert alert-danger'>Error: " . $conn->error . "</div>";
    }
}

// Handle delete action
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $delete_query = "DELETE FROM scholarships WHERE id = ?";
    $stmt = $conn->prepare($delete_query);
    $stmt->bind_param("i", $delete_id);
    if ($stmt->execute()) {
        $feedback = "<div class='alert alert-success'>Scholarship deleted successfully!</div>";
    } else {
        $feedback = "<div class='alert alert-danger'>Error: " . $stmt->error . "</div>";
    }
    $stmt->close();
}

// Handle edit action (Fetching specific data for editing)
$edit_data = [];
if (isset($_GET['edit_id'])) {
    $edit_id = $_GET['edit_id'];
    $edit_query = "SELECT * FROM scholarships WHERE id = ?";
    $stmt = $conn->prepare($edit_query);
    $stmt->bind_param("i", $edit_id);
    $stmt->execute();
    $edit_result = $stmt->get_result();
    if ($edit_result->num_rows > 0) {
        $edit_data = $edit_result->fetch_assoc();
    } else {
        $feedback = "<div class='alert alert-danger'>No data found for the given ID.</div>";
    }
    $stmt->close();
}

// Handle update action (when editing a scholarship)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    $edit_id = $_POST['id'];
    $college_name = $_POST['college'];
    $course_name = $_POST['course'];
    $scholarship_title = $_POST['scholarship_title'];
    $about_scholarship = $_POST['about_scholarship'];

    // Sanitize input
    $college_name = $conn->real_escape_string($college_name);
    $course_name = $conn->real_escape_string($course_name);
    $scholarship_title = $conn->real_escape_string($scholarship_title);
    $about_scholarship = $conn->real_escape_string($about_scholarship);

    // Update scholarship details in the database
    $update_query = "UPDATE scholarships SET college = ?, course = ?, scholarship = ?, about_scholarship = ? WHERE id = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("ssssi", $college_name, $course_name, $scholarship_title, $about_scholarship, $edit_id);

    if ($stmt->execute()) {
        $feedback = "<div class='alert alert-success'>Scholarship updated successfully!</div>";
    } else {
        $feedback = "<div class='alert alert-danger'>Error: " . $stmt->error . "</div>";
    }
    $stmt->close();
}

// Fetch all scholarships from the database for display
$scholarships_query = "SELECT * FROM scholarships";
$scholarships_result = $conn->query($scholarships_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Scholarship Page</title>
    <link rel="icon" type="image/x-icon" href="../img/college.png">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        /* Basic CSS for layout and design */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f7f6;
            margin: 0;
            padding: 0;
        }

        .header {
            background-color: #333;
            color: white;
            padding: 15px 0;
            text-align: center;
            font-size: 24px;
        }

        .container {
            margin: 30px auto;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 800px;
        }

        form {
            margin-bottom: 30px;
        }

        label {
            font-weight: bold;
        }

        input, select, textarea, button {
            width: 100%;
            padding: 12px;
            margin: 8px 0;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        button {
            background-color: #4CAF50;
            color: white;
            font-size: 16px;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
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
            background-color: #f2f2f2;
        }

        .actions a {
            margin-right: 10px;
            color: #007BFF;
            text-decoration: none;
        }

        .actions a:hover {
            text-decoration: underline;
        }

        .alert {
            padding: 10px;
            margin-top: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
        }

        .alert-success {
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
        }

        .alert-danger {
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
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
    <h2><?= isset($edit_data['id']) ? 'Edit' : 'Add' ?> Scholarship</h2>
    <?= $feedback; ?>

    <form action="" method="POST">
        <?php if (isset($edit_data['id'])): ?>
            <!-- Hidden input for updating the scholarship -->
            <input type="hidden" name="id" value="<?= $edit_data['id']; ?>">
        <?php endif; ?>

        <!-- Select College -->
        <label for="college">Select College</label>
        <select name="college" id="college" required>
            <option value="" disabled <?= isset($edit_data['college']) ? '' : 'selected'; ?>>Select a College</option>
            <?php
            if ($colleges_result->num_rows > 0) {
                while ($row = $colleges_result->fetch_assoc()) {
                    $selected = (isset($edit_data['college']) && $edit_data['college'] == $row['college_name']) ? 'selected' : '';
                    echo "<option value='" . $row['college_name'] . "' $selected>" . $row['college_name'] . "</option>";
                }
            }
            ?>
        </select>

        <!-- Select Course -->
        <label for="course">Select Course</label>
        <select name="course" id="course" required>
            <option value="" disabled <?= isset($edit_data['course']) ? '' : 'selected'; ?>>Select a Course</option>
            <?php
            if (isset($edit_data['college'])) {
                $selected_college = $edit_data['college'];
                $courses_query = "SELECT DISTINCT course_name FROM colleges WHERE college_name = '$selected_college'";
                $courses_result = $conn->query($courses_query);
                while ($row = $courses_result->fetch_assoc()) {
                    $selected = (isset($edit_data['course']) && $edit_data['course'] == $row['course_name']) ? 'selected' : '';
                    echo "<option value='" . $row['course_name'] . "' $selected>" . $row['course_name'] . "</option>";
                }
            }
            ?>
        </select>

        <!-- Scholarship Title -->
        <label for="scholarship_title">Scholarship Title</label>
        <input type="text" name="scholarship_title" id="scholarship_title" value="<?= isset($edit_data['scholarship']) ? $edit_data['scholarship'] : ''; ?>" required>

        <!-- About Scholarship -->
        <label for="about_scholarship">About Scholarship</label>
        <textarea name="about_scholarship" id="about_scholarship" rows="4" required><?= isset($edit_data['about_scholarship']) ? $edit_data['about_scholarship'] : ''; ?></textarea>

        <!-- Submit Button -->
        <button type="submit" name="<?= isset($edit_data['id']) ? 'update' : 'submit'; ?>"><?= isset($edit_data['id']) ? 'Update' : 'Submit'; ?> Scholarship</button>
    </form>

    <h2>Scholarship List</h2>
    <table>
        <thead>
            <tr>
                <th>College</th>
                <th>Course</th>
                <th>Scholarship</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($scholarships_result->num_rows > 0) {
                while ($row = $scholarships_result->fetch_assoc()) {
                    echo "<tr>
                            <td>" . $row['college'] . "</td>
                            <td>" . $row['course'] . "</td>
                            <td>" . $row['scholarship'] . "</td>
                            <td class='actions'>
                                <a href='?edit_id=" . $row['id'] . "'>Edit</a>
                                <a href='?delete_id=" . $row['id'] . "'>Delete</a>
                            </td>
                        </tr>";
                }
            }
            ?>
        </tbody>
    </table>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script>
    // When a college is selected, fetch the courses for that college
    $('#college').on('change', function() {
        var collegeName = $(this).val();
        if (collegeName) {
            $.ajax({
                url: 'fetch_courses.php',  // PHP file to fetch courses
                method: 'POST',
                data: { college_name: collegeName },
                dataType: 'json',
                success: function(response) {
                    // Clear the courses dropdown
                    $('#course').empty().append('<option value="" disabled selected>Select a Course</option>');
                    // Populate the courses dropdown with the fetched courses
                    $.each(response, function(index, course) {
                        $('#course').append('<option value="' + course + '">' + course + '</option>');
                    });
                },
                error: function() {
                    alert('Error fetching courses. Please try again.');
                }
            });
        } else {
            $('#course').empty().append('<option value="" disabled selected>Select a Course</option>');
        }
    });
</script>

</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
