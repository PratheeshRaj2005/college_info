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

// Handle Edit Operation
if (isset($_GET['edit_id'])) {
    $edit_id = $_GET['edit_id'];
    $edit_query = "SELECT * FROM placements WHERE id = ?";
    $stmt = $conn->prepare($edit_query);
    $stmt->bind_param("i", $edit_id);
    $stmt->execute();
    $edit_result = $stmt->get_result();
    $edit_data = $edit_result->fetch_assoc();
}

// Handle Delete Operation
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $delete_query = "DELETE FROM placements WHERE id = ?";
    $stmt = $conn->prepare($delete_query);
    $stmt->bind_param("i", $delete_id);
    if ($stmt->execute()) {
        echo "<script>alert('Placement record deleted successfully.'); window.location.href='';</script>";
    } else {
        echo "<script>alert('Error deleting record.');</script>";
    }
}

// Fetch distinct colleges for the dropdown
$colleges_query = "SELECT DISTINCT college_name FROM colleges";
$colleges_result = $conn->query($colleges_query);
if (!$colleges_result) {
    die("Error fetching colleges: " . $conn->error);
}

// Fetch all placements for displaying the data
$placements_query = "SELECT * FROM placements";
$placements_result = $conn->query($placements_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Placement Page</title>
    <link rel="icon" type="image/x-icon" href="../img/college.png">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f7f6;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #007BFF;
            color: white;
            padding: 15px;
            text-align: center;
            font-size: 2rem;
        }

        footer {
            background-color: #007BFF;
            color: white;
            padding: 10px;
            text-align: center;
            position: fixed;
            bottom: 0;
            width: 100%;
        }

        .container {
            max-width: 900px;
            margin: 50px auto;
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            margin-bottom: 30px;
            color: #007BFF;
        }

        label {
            font-weight: bold;
            margin-top: 10px;
        }

        .form-group {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 15px;
        }

        .form-group label {
            flex: 1;
            margin-right: 15px;
        }

        .form-group input, .form-group select {
            flex: 2;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #007BFF;
            color: white;
        }

        .actions a {
            margin-right: 10px;
            color: #007BFF;
            text-decoration: none;
        }

        .actions a:hover {
            text-decoration: underline;
        }

        .form-group input[type="file"] {
            padding: 5px;
        }

        .form-group input[type="text"] {
            text-transform: capitalize;
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
        /* .sidebar a{
            padding:16px !important;
        } */

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

<header>
    Placement Details Management
</header>

<div class="container">
    <h1><?= isset($edit_data) ? 'Edit Placement Details' : 'Add Placement Details' ?></h1>

    <form action="<?= isset($edit_data) ? 'update_placements.php' : 'add_placements.php' ?>" method="POST" enctype="multipart/form-data">
        <!-- Select College Name -->
        <div class="form-group">
            <label for="college_name">College</label>
            <select name="college_name" id="college_name" required>
                <option value="">Select a College</option>
                <?php
                if ($colleges_result->num_rows > 0) {
                    while ($row = $colleges_result->fetch_assoc()) {
                        $selected = (isset($edit_data) && $edit_data['college_name'] == $row['college_name']) ? 'selected' : '';
                        echo "<option value='" . htmlspecialchars($row['college_name'], ENT_QUOTES) . "' $selected>" . htmlspecialchars($row['college_name'], ENT_QUOTES) . "</option>";
                    }
                }
                ?>
            </select>
        </div>

        <!-- Select Course -->
        <div class="form-group">
            <label for="course_name">Course</label>
            <select name="course_name" id="course_name" required>
                <option value="">Select a Course</option>
                <?php
                if (isset($edit_data)) {
                    // Assuming courses are part of the placement data
                    echo "<option value='" . htmlspecialchars($edit_data['course'], ENT_QUOTES) . "' selected>" . htmlspecialchars($edit_data['course'], ENT_QUOTES) . "</option>";
                }
                ?>
            </select>
        </div>

        <!-- Average Package -->
        <div class="form-group">
            <label for="average_package">Average Package (in LPA)</label>
            <input type="text" name="average_package" id="average_package" value="<?= isset($edit_data) ? htmlspecialchars($edit_data['average_package'], ENT_QUOTES) : '' ?>" placeholder="e.g., 6.5" required>
        </div>

        <!-- Minimum Package -->
        <div class="form-group">
            <label for="minimum_package">Minimum Package (in LPA)</label>
            <input type="text" name="minimum_package" id="minimum_package" value="<?= isset($edit_data) ? htmlspecialchars($edit_data['minimum_package'], ENT_QUOTES) : '' ?>" placeholder="e.g., 3.5" required>
        </div>

        <!-- Placement Rate -->
        <div class="form-group">
            <label for="placement_rate">Placement Rate (%)</label>
            <input type="text" name="placement_rate" id="placement_rate" value="<?= isset($edit_data) ? htmlspecialchars($edit_data['placement_rate'], ENT_QUOTES) : '' ?>" placeholder="e.g., 85.5" required>
        </div>

        <!-- Participated Students -->
        <div class="form-group">
            <label for="participated_students">Participated Students</label>
            <input type="text" name="participated_students" id="participated_students" value="<?= isset($edit_data) ? htmlspecialchars($edit_data['participated_students'], ENT_QUOTES) : '' ?>" placeholder="e.g., 150" required>
        </div>

        <!-- Accepted Offers -->
        <div class="form-group">
            <label for="accepted_offers">Accepted Offers</label>
            <input type="text" name="accepted_offers" id="accepted_offers" value="<?= isset($edit_data) ? htmlspecialchars($edit_data['accepted_offers'], ENT_QUOTES) : '' ?>" placeholder="e.g., 120" required>
        </div>

        <!-- Total Recruiters -->
        <div class="form-group">
            <label for="total_recruiters">Total Recruiters</label>
            <input type="text" name="total_recruiters" id="total_recruiters" value="<?= isset($edit_data) ? htmlspecialchars($edit_data['total_recruiters'], ENT_QUOTES) : '' ?>" placeholder="e.g., 50" required>
        </div>

        <!-- Upload Company Logos -->
        <div class="form-group">
            <label for="company_images">Upload Company Logos</label>
            <input type="file" name="company_images[]" id="company_images" multiple accept="image/*" <?= isset($edit_data) ? '' : 'required' ?>>
        </div>

        <button type="submit" name="submit"><?= isset($edit_data) ? 'Update Placement Details' : 'Add Placement Details' ?></button>
    </form>
</div>

<div class="container">
    <h2>Existing Placement Records</h2>
    <table>
        <thead>
            <tr>
                <th>College</th>
                <th>Course</th>
                <th>Average Package</th>
                <th>Minimum Package</th>
                <th>Placement Rate</th>
                <th>Participated Students</th>
                <th>Accepted Offers</th>
                <th>Total Recruiters</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            while ($row = $placements_result->fetch_assoc()) {
                echo "<tr>
                    <td>" . htmlspecialchars($row['college_name'], ENT_QUOTES) . "</td>
                    <td>" . htmlspecialchars($row['course'], ENT_QUOTES) . "</td>
                    <td>" . htmlspecialchars($row['average_package'], ENT_QUOTES) . "</td>
                    <td>" . htmlspecialchars($row['minimum_package'], ENT_QUOTES) . "</td>
                    <td>" . htmlspecialchars($row['placement_rate'], ENT_QUOTES) . "</td>
                    <td>" . htmlspecialchars($row['participated_students'], ENT_QUOTES) . "</td>
                    <td>" . htmlspecialchars($row['accepted_offers'], ENT_QUOTES) . "</td>
                    <td>" . htmlspecialchars($row['total_recruiters'], ENT_QUOTES) . "</td>
                    <td class='actions'>
                        <a href='?edit_id=" . $row['id'] . "'>Edit</a>
                        <a href='?delete_id=" . $row['id'] . "' onclick='return confirm(\"Are you sure you want to delete this record?\")'>Delete</a>
                    </td>
                </tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<footer>
    Placement Details Management - Admin Panel
</footer>

</body>
</html>
