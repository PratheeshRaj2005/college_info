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

// Fetch colleges from the database
$colleges_query = "SELECT DISTINCT college_name FROM colleges";
$colleges_result = $conn->query($colleges_query);

// Fetch infrastructure data for display
$infrastructure_query = "SELECT * FROM infrastructure";
$infrastructure_result = $conn->query($infrastructure_query);

// Handle form submission for adding new infrastructure data
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    // Sanitize form inputs
    $college_name = $conn->real_escape_string($_POST['college_name']);
    $course = $conn->real_escape_string($_POST['course']);
    $particular = $conn->real_escape_string($_POST['particular']);
    $particular_text = $conn->real_escape_string($_POST['particular_text']);
    $highlights = isset($_POST['highlights']) ? $conn->real_escape_string($_POST['highlights']) : null;

    // Handle file upload
    $uploaded_images = [];
    if (isset($_FILES['infrastructure_images']) && count($_FILES['infrastructure_images']['tmp_name']) > 0) {
        $target_dir = "../uploads/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        foreach ($_FILES['infrastructure_images']['tmp_name'] as $key => $tmp_name) {
            $file_name = basename($_FILES['infrastructure_images']['name'][$key]);
            $target_file = $target_dir . uniqid() . "_" . $file_name;

            if (move_uploaded_file($tmp_name, $target_file)) {
                $uploaded_images[] = $target_file;
            } else {
                echo "Error uploading file: " . htmlspecialchars($file_name);
                exit;
            }
        }
    }

    $infrastructure_images_string = implode(',', $uploaded_images);

    // Insert data into the database
    $stmt = $conn->prepare("
        INSERT INTO infrastructure 
        (college_name, course, particular, particular_text, highlights_text, infrastructure_images) 
        VALUES (?, ?, ?, ?, ?, ?)
    ");
    $stmt->bind_param("ssssss", $college_name, $course, $particular, $particular_text, $highlights, $infrastructure_images_string);

    if ($stmt->execute()) {
        $feedback = "<div class='success'>Infrastructure details added successfully!</div>";
    } else {
        $feedback = "<div class='error'>Error: " . $stmt->error . "</div>";
    }

    $stmt->close();
}

// Handle delete action
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $delete_query = "DELETE FROM infrastructure WHERE id = ?";
    $stmt = $conn->prepare($delete_query);
    $stmt->bind_param("i", $delete_id);
    if ($stmt->execute()) {
        $feedback = "<div class='success'>Record deleted successfully!</div>";
    } else {
        $feedback = "<div class='error'>Error: " . $stmt->error . "</div>";
    }
    $stmt->close();
}

// Handle edit action (Fetching specific data for editing)
if (isset($_GET['edit_id'])) {
    $edit_id = $_GET['edit_id'];
    $edit_query = "SELECT * FROM infrastructure WHERE id = ?";
    $stmt = $conn->prepare($edit_query);
    $stmt->bind_param("i", $edit_id);
    $stmt->execute();
    $edit_result = $stmt->get_result();
    if ($edit_result->num_rows > 0) {
        $edit_data = $edit_result->fetch_assoc();
    } else {
        $feedback = "<div class='error'>No data found for the given ID.</div>";
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Infrastructure</title>
    <link rel="icon" type="image/x-icon" href="../img/college.png">

    <style>
        /* General Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f9;
            color: #333;
            line-height: 1.6;
        }

        .container {
            width: 80%;
            max-width: 900px;
            margin: 50px auto;
            background: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #444;
            font-size: 28px;
            margin-bottom: 30px;
        }

        form {
            margin-bottom: 40px;
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 8px;
            color: #555;
        }

        select, input[type="text"], textarea, input[type="file"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
            box-sizing: border-box;
        }

        textarea {
            resize: vertical;
        }

        select:focus, input[type="text"]:focus, textarea:focus, input[type="file"]:focus {
            border-color: #007bff;
            outline: none;
        }

        button {
            width: 100%;
            padding: 12px;
            font-size: 16px;
            color: #fff;
            background-color: #007bff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #0056b3;
        }

        .success, .error {
            padding: 12px;
            margin-bottom: 20px;
            text-align: center;
            border-radius: 5px;
        }

        .success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        table {
            width: 100%;
            margin-top: 40px;
            border-collapse: collapse;
            background-color: #fff;
            border-radius: 8px;
            overflow: hidden;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #007bff;
            color: white;
        }

        tr:hover {
            background-color: #f9f9f9;
        }

        .actions a {
            color: #007bff;
            text-decoration: none;
            margin-right: 10px;
        }

        .actions a:hover {
            text-decoration: underline;
        }

        @media (max-width: 768px) {
            .container {
                padding: 15px;
            }
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
    <h1>Manage Infrastructure</h1>
    <?= isset($feedback) ? $feedback : ''; ?>

    <form action="" method="POST" enctype="multipart/form-data">
        <label for="college_name">Select College:</label>
        <select name="college_name" id="college_name" required>
            <option value="">Select a College</option>
            <?php
            if ($colleges_result->num_rows > 0) {
                while ($row = $colleges_result->fetch_assoc()) {
                    $selected = isset($edit_data) && $edit_data['college_name'] == $row['college_name'] ? "selected" : "";
                    echo "<option value='" . $row['college_name'] . "' $selected>" . $row['college_name'] . "</option>";
                }
            }
            ?>
        </select>

        <label for="course">Select Course:</label>
        <select name="course" id="course" required>
            <option value="">Select a Course</option>
        </select>

        <label for="particular">Add Particular:</label>
        <input type="text" name="particular" id="particular" value="<?= isset($edit_data) ? $edit_data['particular'] : ''; ?>" placeholder="Enter Particular" required>

        <label for="particular_text">Particular Details:</label>
        <textarea name="particular_text" id="particular_text" rows="4" placeholder="Enter details"><?= isset($edit_data) ? $edit_data['particular_text'] : ''; ?></textarea>

        <label for="highlights">Highlights (Optional):</label>
        <textarea name="highlights" id="highlights" rows="4" placeholder="Enter highlights (if any)"><?= isset($edit_data) ? $edit_data['highlights_text'] : ''; ?></textarea>

        <label for="infrastructure_images">Upload Images:</label>
        <input type="file" name="infrastructure_images[]" id="infrastructure_images" accept="image/*" multiple>

        <button type="submit" name="submit">Submit</button>
    </form>

    <table>
        <thead>
            <tr>
                <th>College Name</th>
                <th>Course</th>
                <th>Particular</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($infrastructure_result->num_rows > 0) {
                while ($row = $infrastructure_result->fetch_assoc()) {
                    echo "<tr>
                            <td>{$row['college_name']}</td>
                            <td>{$row['course']}</td>
                            <td>{$row['particular']}</td>
                            <td class='actions'>
                                <a href='?edit_id={$row['id']}'>Edit</a>
                                <a href='?delete_id={$row['id']}'>Delete</a>
                            </td>
                        </tr>";
                }
            }
            ?>
        </tbody>
    </table>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    // Fetch courses dynamically based on selected college
    $('#college_name').on('change', function () {
        var collegeName = $(this).val();
        if (collegeName) {
            $.ajax({
                url: 'fetch_courses.php',
                method: 'POST',
                data: {college_name: collegeName},
                dataType: 'json',
                success: function (response) {
                    $('#course').empty().append('<option value="">Select a Course</option>');
                    $.each(response, function (index, course) {
                        $('#course').append('<option value="' + course + '">' + course + '</option>');
                    });
                },
                error: function () {
                    alert('Error fetching courses. Please try again.');
                }
            });
        } else {
            $('#course').empty().append('<option value="">Select a Course</option>');
        }
    });
</script>

</body>
</html>
