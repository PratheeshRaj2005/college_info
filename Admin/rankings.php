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

// Fetch colleges from the database
$colleges_query = "SELECT DISTINCT college_name FROM colleges";
$colleges_result = $conn->query($colleges_query);

// Fetch rankings from the database
$rankings_query = "SELECT * FROM rankings";
$rankings_result = $conn->query($rankings_query);

// Handle Delete request
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    
    // Delete the image from the server (optional)
    $result = $conn->query("SELECT ranking_logo FROM rankings WHERE id = $id");
    $row = $result->fetch_assoc();
    $image_path = 'uploads/' . $row['ranking_logo'];
    
    if (file_exists($image_path)) {
        unlink($image_path);
    }

    // Delete the record from the database
    $conn->query("DELETE FROM rankings WHERE id = $id");

    // Redirect to the same page to reflect the changes
    header("Location: admin_rankings.php");
    exit();
}

// Handle Edit request
$ranking = null;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $ranking = $conn->query("SELECT * FROM rankings WHERE id = $id")->fetch_assoc();
}

// Handle the form submission for both Add and Edit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $college_name = $_POST['college_name'];
    $course_name = $_POST['course_name'];
    $ranking_title = $_POST['ranking_title'];
    $about_rankings = $_POST['about_rankings'];
    
    // Handle file upload
    if (isset($_FILES['ranking_logo']) && $_FILES['ranking_logo']['error'] == 0) {
        $logo_name = $_FILES['ranking_logo']['name'];
        $logo_tmp = $_FILES['ranking_logo']['tmp_name'];
        $logo_path = 'uploads/' . $logo_name;
        move_uploaded_file($logo_tmp, $logo_path);
    } else {
        // If no new logo is uploaded, use the previous one (if editing)
        $logo_name = isset($ranking) ? $ranking['ranking_logo'] : '';
    }

    if (isset($ranking)) {
        // Update the existing ranking
        $conn->query("UPDATE rankings SET 
            college_name = '$college_name',
            course = '$course_name',
            ranking_title = '$ranking_title',
            about_rankings = '$about_rankings',
            ranking_logo = '$logo_name' 
            WHERE id = $id");
    } else {
        // Insert a new ranking
        $conn->query("INSERT INTO rankings (college_name, course, ranking_title, about_rankings, ranking_logo) 
        VALUES ('$college_name', '$course_name', '$ranking_title', '$about_rankings', '$logo_name')");
    }

    // Redirect to the same page after submission
    // header("Location:Admin/admin_rankings.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Rankings</title>
    <link rel="icon" type="image/x-icon" href="../img/college.png">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
    /* Basic Reset */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f9;
        /* padding: 20px; */
    }

    .container {
        max-width: 1200px;
        margin: 0 auto;
        background-color: white;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        margin-left: 20%;
    }

    h1, h2 {
        color: #333;
    }

    /* Form styles */
    form {
        display: flex;
        flex-direction: column;
        gap: 15px;
        margin-bottom: 30px;
    }

    label {
        font-weight: bold;
        color: #555;
    }

    input, select, textarea {
        padding: 10px;
        font-size: 16px;
        border: 1px solid #ddd;
        border-radius: 5px;
        width: 100%;
    }

    textarea {
        resize: vertical;
        min-height: 150px;
    }

    button[type="submit"] {
        background-color: #007bff;
        color: white;
        padding: 12px 20px;
        border: none;
        border-radius: 5px;
        font-size: 16px;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    button[type="submit"]:hover {
        background-color: #0056b3;
    }

    .ranking-logo {
        max-width: 100px;
        margin-top: 10px;
        border-radius: 5px;
    }

    /* Table styles */
    table {
        width: 100%;
        margin-top: 30px;
        border-collapse: collapse;
        text-align: left;
    }

    th, td {
        padding: 12px;
        border: 1px solid #ddd;
    }

    th {
        background-color: #f8f8f8;
        color: #333;
    }

    td {
        background-color: #fff;
    }

    tr:nth-child(even) td {
        background-color: #f9f9f9;
    }

    .actions a {
        padding: 8px 15px;
        text-decoration: none;
        margin: 0 5px;
        border-radius: 5px;
        color: white;
    }

    .btn-edit {
        background-color: #28a745;
    }

    .btn-edit:hover {
        background-color: #218838;
    }

    .btn-delete {
        background-color: #dc3545;
    }

    .btn-delete:hover {
        background-color: #c82333;
    }

    /* Mobile responsiveness */
    @media (max-width: 768px) {
        .container {
            padding: 15px;
        }

        form {
            gap: 10px;
        }

        button[type="submit"] {
            padding: 10px 15px;
        }

        table {
            font-size: 14px;
        }

        .ranking-logo {
            max-width: 80px;
        }
    }
        /* Main Content Area */
        .main-content {
            /* margin-left: 250px; */
            /* padding: 20px; */
            width: calc(100% - 250px);
            overflow-y: auto;
            flex-grow: 1;
            width: 100%;
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
        /* .sidebar a{
            padding:16px !important;
        } */

</style>

</head>
<body>
      
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
        <h1><?= isset($ranking) ? 'Edit Ranking' : 'Add Rankings' ?></h1>
        <form action="admin_rankings.php" method="POST" enctype="multipart/form-data">
            <label for="college_name">Select College</label>
            <select name="college_name" id="college_name" >
                <option value="">Select a College</option>
                <?php
                // Dynamically populate the college options
                if ($colleges_result->num_rows > 0) {
                    while ($row = $colleges_result->fetch_assoc()) {
                        $selected = isset($ranking) && $ranking['college_name'] == $row['college_name'] ? 'selected' : '';
                        echo "<option value='" . $row['college_name'] . "' $selected>" . $row['college_name'] . "</option>";
                    }
                }
                ?>
            </select>

            <label for="course_name">Select Course</label>
            <select name="course_name" id="course_name" >
                <option value="">Select a Course</option>
                <!-- Courses will be dynamically populated using AJAX -->
            </select>

            <label for="ranking_logo">Upload Ranking Logo</label>
            <input type="file" name="ranking_logo" id="ranking_logo" accept="image/*">
            <?php if (isset($ranking) && $ranking['ranking_logo']): ?>
                <img src="uploads/<?= htmlspecialchars($ranking['ranking_logo']) ?>" class="ranking-logo" alt="Existing Ranking Logo">
            <?php endif; ?>

            <label for="ranking_title">Ranking Title</label>
            <input type="text" name="ranking_title" id="ranking_title" placeholder="Enter the ranking title" value="<?= isset($ranking) ? htmlspecialchars($ranking['ranking_title']) : '' ?>" required>

            <label for="about_rankings">About Rankings</label>
            <textarea name="about_rankings" id="about_rankings" rows="4" placeholder="Provide details about the ranking" required><?= isset($ranking) ? htmlspecialchars($ranking['about_rankings']) : '' ?></textarea>

            <button type="submit" name="submit"><?= isset($ranking) ? 'Update Ranking' : 'Add Ranking' ?></button>
        </form>

        <!-- Display Existing Rankings -->
        <h2 class="mt-5">Existing Rankings</h2>
        <?php if ($rankings_result->num_rows > 0): ?>
            <table class="ranking-table table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>College Name</th>
                        <th>Course</th>
                        <th>Ranking Title</th>
                        <th>Ranking Logo</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $rankings_result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['college_name']) ?></td>
                            <td><?= htmlspecialchars($row['course']) ?></td>
                            <td><?= htmlspecialchars($row['ranking_title']) ?></td>
                            <td><img src="../uploads/<?= htmlspecialchars($row['ranking_logo']) ?>" class="ranking-logo" alt="Ranking Logo"></td>
                            <td class="actions">
                                <a href="?edit=<?= $row['id'] ?>" class="btn-edit">Edit</a>
                                <a href="admin_rankings.php?delete=<?= $row['id'] ?>" class="btn-delete" onclick="return confirm('Are you sure you want to delete this ranking?')">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No rankings found.</p>
        <?php endif; ?>
    </div>

    <script>
        $(document).ready(function() {
            // Fetch courses dynamically based on selected college
            $('#college_name').change(function() {
                const collegeName = $(this).val();

                // Clear previous course options
                $('#course_name').empty().append('<option value="">Select a Course</option>');

                if (collegeName) {
                    $.ajax({
                        url: 'fetch_courses.php', // Path to the PHP file for fetching courses
                        type: 'POST',
                        data: { college_name: collegeName },
                        success: function(response) {
                            const courses = JSON.parse(response);
                            courses.forEach(course => {
                                $('#course_name').append(`<option value="${course}">${course}</option>`);
                            });
                        },
                        error: function() {
                            alert('Failed to fetch courses. Please try again.');
                        }
                    });
                }
            });
        });
    </script>
</body>
</html>
