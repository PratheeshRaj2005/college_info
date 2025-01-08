<?php
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "reglog";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch faculty data from the database
$facultyData = [];
$sql_fetch = "SELECT * FROM faculty";
$result = $conn->query($sql_fetch);

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $facultyData[] = $row;
    }
}

// Fetch data for editing if `edit_id` is provided
$editData = null;
if (isset($_GET['edit_id'])) {
    $edit_id = $_GET['edit_id'];
    $sql_edit_fetch = "SELECT * FROM faculty WHERE id = ?";
    $stmt = $conn->prepare($sql_edit_fetch);
    $stmt->bind_param("i", $edit_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $editData = $result->fetch_assoc();
    $stmt->close();
}

// Handle form submission (add/update faculty)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'] ?? null;
    $faculty_name = $_POST['faculty_name'];
    $qualification = $_POST['qualification'];
    $designation = $_POST['designation'];
    $work_location = $_POST['work_location'];
    $personal_number = $_POST['personal_number'];
    $image = $_FILES['image'];

    if ($id) {
        // Update faculty
        $sql_update = "UPDATE faculty SET faculty_name = ?, qualification = ?, designation = ?, work_location = ?, personal_number = ?, image = ? WHERE id = ?";
        $stmt = $conn->prepare($sql_update);
        $stmt->bind_param("ssssssi", $faculty_name, $qualification, $designation, $work_location, $personal_number, $image['name'], $id);
        $stmt->execute();
        $stmt->close();
        $_SESSION['message'] = 'Faculty updated successfully!';
    } else {
        // Add new faculty
        $sql_insert = "INSERT INTO faculty (faculty_name, qualification, designation, work_location, personal_number, image) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql_insert);
        $stmt->bind_param("ssssss", $faculty_name, $qualification, $designation, $work_location, $personal_number, $image['name']);
        $stmt->execute();
        $stmt->close();
        $_SESSION['message'] = 'Faculty added successfully!';
    }

    // Redirect back to the same page
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Handle faculty deletion
if (isset($_POST['delete_id'])) {
    $delete_id = $_POST['delete_id'];
    $sql_delete = "DELETE FROM faculty WHERE id = ?";
    $stmt = $conn->prepare($sql_delete);
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
    $stmt->close();
    $_SESSION['message'] = 'Faculty deleted successfully!';

    // Redirect back to the same page
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add/Edit Faculty</title>
    <link rel="icon" type="image/x-icon" href="../img/college.png">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script>
        // Confirmation dialog for delete
        function confirmDelete(form) {
            if (confirm("Are you sure you want to delete this faculty member?")) {
                form.submit();
            }
        }
    </script>
    <style>
        .container {
            max-width: 600px;
            margin-top: 30px;
        }
        .form-group label {
            font-weight: bold;
        }
        .form-control {
            margin-bottom: 15px;
        }
        .main-content {
            margin-left: 250px;
            width: calc(100% - 250px);
            overflow-y: auto;
            flex-grow: 1;
        }
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
        .table {
            margin-top: 30px;
        }
        .action-buttons {
            display: flex;
            gap: 10px;
        }
        .alert {
            margin-top: 20px;
            text-align: center;
        }
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

        <!-- Show success/failure message -->
        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-success">
                <?= $_SESSION['message'] ?>
            </div>
            <?php unset($_SESSION['message']); ?>
        <?php endif; ?>

        <div class="container">
            <h2 class="text-center"><?= $editData ? "Edit Faculty Member" : "Add Faculty Member" ?></h2>
            <form action="../backend/faculty.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?= $editData['id'] ?? '' ?>">
                <div class="form-group">
                    <label for="faculty_name">Faculty Name</label>
                    <input type="text" class="form-control" id="faculty_name" name="faculty_name" required value="<?= htmlspecialchars($editData['faculty_name'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label for="qualification">Qualification</label>
                    <input type="text" class="form-control" id="qualification" name="qualification" required value="<?= htmlspecialchars($editData['qualification'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label for="designation">Designation</label>
                    <input type="text" class="form-control" id="designation" name="designation" required value="<?= htmlspecialchars($editData['designation'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label for="work_location">Work Location</label>
                    <input type="text" class="form-control" id="work_location" name="work_location" required value="<?= htmlspecialchars($editData['work_location'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label for="personal_number">Personal Number</label>
                    <input type="text" class="form-control" id="personal_number" name="personal_number" required value="<?= htmlspecialchars($editData['personal_number'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label for="image">Faculty Image</label>
                    <input type="file" class="form-control" id="image" name="image" <?= $editData ? '' : 'required' ?>>
                </div>
                <button type="submit" class="btn btn-primary btn-block"><?= $editData ? "Update Faculty" : "Add Faculty" ?></button>
            </form>

            <!-- Faculty Table -->
            <h3 class="text-center mt-4">Faculty List</h3>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Qualification</th>
                        <th>Designation</th>
                        <th>Work Location</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($facultyData)): ?>
                        <?php foreach ($facultyData as $index => $faculty): ?>
                            <tr>
                                <td><?= $index + 1 ?></td>
                                <td><?= htmlspecialchars($faculty['faculty_name']) ?></td>
                                <td><?= htmlspecialchars($faculty['qualification']) ?></td>
                                <td><?= htmlspecialchars($faculty['designation']) ?></td>
                                <td><?= htmlspecialchars($faculty['work_location']) ?></td>
                                <td>
                                    <div class="action-buttons">
                                        <!-- Edit button -->
                                        <a href="?edit_id=<?= $faculty['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                                        <!-- Delete form with confirmation -->
                                        <form action="" method="POST" style="display:inline;" onsubmit="event.preventDefault(); confirmDelete(this);">
                                            <input type="hidden" name="delete_id" value="<?= $faculty['id'] ?>">
                                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center">No faculty data available.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
