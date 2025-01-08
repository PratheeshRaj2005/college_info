<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "reglog");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle Edit functionality
$edit_data = [];
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $edit_query = "SELECT * FROM about_college WHERE id = $id";
    $edit_result = $conn->query($edit_query);
    if ($edit_result->num_rows > 0) {
        $edit_data = $edit_result->fetch_assoc();
    }
}

// Handle Delete functionality
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    if ($conn->query("DELETE FROM about_college WHERE id = $id") === TRUE) {
        echo "<script>alert('Record deleted successfully!');window.location.href='';</script>";
    } else {
        echo "<script>alert('Error deleting record: " . $conn->error . "');</script>";
    }
}

// Check if the form is submitted for updating
// Check if the form is submitted for updating
if (isset($_POST['update'])) {
    // Get the posted data
    $id = $_GET['edit'];
    $college_name = $_POST['college_name'];
    // $college = $_POST['college'];
    $course = $_POST['course_name'];
    $contact = $_POST['contact'];
    $location = $_POST['location'];
    $about_college = $_POST['about_college'];

    // Escape the input data to prevent SQL injection and handle special characters
    $college_name = $conn->real_escape_string($college_name);
    $college = $conn->real_escape_string($college);
    $course = $conn->real_escape_string($course);
    $contact = $conn->real_escape_string($contact);
    $location = $conn->real_escape_string($location);
    $about_college = $conn->real_escape_string($about_college);

    // Handle file uploads (Logo and Images)
    $logo = $_FILES['logo']['name'] ? $_FILES['logo']['name'] : $edit_data['logo'];
    $image1 = $_FILES['image1']['name'] ? $_FILES['image1']['name'] : $edit_data['image1'];
    $image2 = $_FILES['image2']['name'] ? $_FILES['image2']['name'] : $edit_data['image2'];
    $image3 = $_FILES['image3']['name'] ? $_FILES['image3']['name'] : $edit_data['image3'];
    $image4 = $_FILES['image4']['name'] ? $_FILES['image4']['name'] : $edit_data['image4'];

    // Move uploaded files to the uploads directory
    if ($_FILES['logo']['name']) {
        move_uploaded_file($_FILES['logo']['tmp_name'], "../uploads/$logo");
    }
    if ($_FILES['image1']['name']) {
        move_uploaded_file($_FILES['image1']['tmp_name'], "../uploads/$image1");
    }
    if ($_FILES['image2']['name']) {
        move_uploaded_file($_FILES['image2']['tmp_name'], "../uploads/$image2");
    }
    if ($_FILES['image3']['name']) {
        move_uploaded_file($_FILES['image3']['tmp_name'], "../uploads/$image3");
    }
    if ($_FILES['image4']['name']) {
        move_uploaded_file($_FILES['image4']['tmp_name'], "../uploads/$image4");
    }

    // Update query with escaped data
    $update_query = "UPDATE about_college SET 
                        college_name = '$college_name',
                        -- college = '$college',
                        course = '$course',
                        contact = '$contact',
                        location = '$location',
                        about_college = '$about_college',
                        logo = '$logo',
                        image1 = '$image1',
                        image2 = '$image2',
                        image3 = '$image3',
                        image4 = '$image4'
                    WHERE id = $id";

    if ($conn->query($update_query) === TRUE) {
        echo "<script>alert('Record updated successfully!');window.location.href='';</script>";
    } else {
        echo "<script>alert('Error updating record: " . $conn->error . "');</script>";
    }
}


// Check if the request is for fetching courses
// Fetch courses for the selected college
if (isset($_GET['fetch_courses']) && $_GET['fetch_courses'] == '1') {
    if (isset($_GET['college_name'])) {
        $college_name = $_GET['college_name'];
        $courses_query = "SELECT DISTINCT course_name FROM colleges WHERE college_name = '$college_name'";
        $courses_result = $conn->query($courses_query);

        $courses = [];
        if ($courses_result->num_rows > 0) {
            while ($row = $courses_result->fetch_assoc()) {
                $courses[] = $row['course_name'];
            }
        }

        // Return courses as JSON and exit
        header('Content-Type: application/json');
        echo json_encode($courses);
        exit;
    }
}


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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add/Update College</title>
    <link rel="icon" type="image/x-icon" href="../img/college.png">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            /* padding: 20px; */
        }

        .container {
            max-width: 700px;
            margin: auto;
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            margin-bottom: 30px;
        }

        label {
            font-weight: bold;
            margin-top: 10px;
        }

        input[type="file"],
        input[type="text"],
        textarea,
        button,
        select {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 1rem;
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

        .form-control:focus {
            border-color: #007BFF;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
        }

        .image-preview {
            display: flex;
            gap: 10px;
        }

        .image-preview img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 8px;
        }

        .table-container {
            margin-top: 40px;
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
            background-color: #007BFF;
            color: white;
        }

        .actions a {
            padding: 6px 12px;
            margin: 0 5px;
            border-radius: 6px;
            text-decoration: none;
            color: white;
        }

        .edit {
            background-color: #28a745;
        }

        .delete {
            background-color: #dc3545;
        }

        .actions a:hover {
            opacity: 0.8;
        }

        /* Image styling for the table */
        .table-container img {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 6px;
            margin-right: 5px;
        }
          /* Main Content Area */
          .main-content {
            /* margin-left: 250px; */
            /* padding: 20px; */
            /* width: calc(100% - 250px); */
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
    <h1><?= isset($_GET['edit']) ? 'Edit College' : 'Add College' ?></h1>
    <form action="" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?= isset($_GET['edit']) ? $edit_data['id'] : '' ?>">
            
            <!-- Select College Name -->
            <label for="college_name">College</label>
            <select name="college_name" id="college_name" required onchange="fetchCourses()">
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

            <!-- College Logo -->
            <label for="logo">College Logo</label>
            <input type="file" name="logo" id="logo">
            <?php if (isset($edit_data['logo']) && $edit_data['logo'] != ''): ?>
                <img src="../uploads/<?= $edit_data['logo'] ?>" width="100">
            <?php endif; ?>

            <!-- Images -->
            <label for="image1">Image 1</label>
            <input type="file" name="image1" id="image1">
            <?php if (isset($edit_data['image1']) && $edit_data['image1'] != ''): ?>
                <img src="../uploads/<?= $edit_data['image1'] ?>" width="100">
            <?php endif; ?>

            <label for="image2">Image 2</label>
            <input type="file" name="image2" id="image2">
            <?php if (isset($edit_data['image2']) && $edit_data['image2'] != ''): ?>
                <img src="../uploads/<?= $edit_data['image2'] ?>" width="100">
            <?php endif; ?>

            <label for="image3">Image 3</label>
            <input type="file" name="image3" id="image3">
            <?php if (isset($edit_data['image3']) && $edit_data['image3'] != ''): ?>
                <img src="../uploads/<?= $edit_data['image3'] ?>" width="100">
            <?php endif; ?>

            <label for="image4">Image 4</label>
            <input type="file" name="image4" id="image4">
            <?php if (isset($edit_data['image4']) && $edit_data['image4'] != ''): ?>
                <img src="../uploads/<?= $edit_data['image4'] ?>" width="100">
            <?php endif; ?>

            <!-- Other Information -->
            <label for="contact">Contact</label>
            <input type="text" name="contact" value="<?= isset($edit_data['contact']) ? $edit_data['contact'] : '' ?>" required>

            <label for="location">Location</label>
            <input type="text" name="location" value="<?= isset($edit_data['location']) ? $edit_data['location'] : '' ?>" required>

            <label for="about_college">About College</label>
            <textarea name="about_college" required><?= isset($edit_data['about_college']) ? $edit_data['about_college'] : '' ?></textarea>

            <!-- Submit Button -->
            <button type="submit" name="update"><?= isset($_GET['edit']) ? 'Update College' : 'Add College' ?></button>
        </form>
    </div>

    <div class="table-container">
        <h2>Colleges List</h2>
        <table>
            <thead>
                <tr>
                    <th>College Name</th>
                    <th>Course</th>
                    <th>Contact</th>
                    <th>Location</th>
                    <th>Logo</th>
                    <th>Images</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $result = $conn->query("SELECT * FROM about_college");
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['college_name'] . "</td>";
                    echo "<td>" . $row['course'] . "</td>";
                    echo "<td>" . $row['contact'] . "</td>";
                    echo "<td>" . $row['location'] . "</td>";
                    echo "<td><img src='../uploads/" . $row['logo'] . "' width='50'></td>";
                    echo "<td>";
                    for ($i = 1; $i <= 4; $i++) {
                        if ($row["image$i"]) {
                            echo "<img src='../uploads/" . $row["image$i"] . "' width='50'>";
                        }
                    }
                    echo "</td>";
                    echo "<td class='actions'>
                            <a href='?edit=" . $row['id'] . "' class='edit'>Edit</a>
                            <a href='?delete=" . $row['id'] . "' class='delete'>Delete</a>
                          </td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <script>
        function fetchCourses() {
    const collegeName = document.getElementById('college_name').value;

    if (collegeName) {
        $.ajax({
            url: '', // You can leave the URL empty to use the same page for processing the request
            method: 'GET',
            data: {
                fetch_courses: 1,
                college_name: collegeName
            },
            success: function(response) {
                // Log the response to debug the format
                console.log('Courses Response:', response);

                // Check if the response is an array
                if (Array.isArray(response)) {
                    const courseSelect = document.getElementById('course_name');
                    courseSelect.innerHTML = "<option value=''>Select a Course</option>";

                    // Populate the course dropdown
                    response.forEach(function(course) {
                        const option = document.createElement('option');
                        option.value = course;
                        option.textContent = course;
                        courseSelect.appendChild(option);
                    });
                } else {
                    console.error('Expected an array, but received:', response);
                    alert('Error: Invalid data received.');
                }
            },
            error: function() {
                alert('Error fetching courses.');
            }
        });
    } else {
        // Reset course dropdown if no college is selected
        const courseSelect = document.getElementById('course_name');
        courseSelect.innerHTML = "<option value=''>Select a Course</option>";
    }
}

    </script>

</body>
</html>
