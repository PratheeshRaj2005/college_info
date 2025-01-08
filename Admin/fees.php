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

// Initialize courses result
$courses_result = [];
if (isset($_POST['college_name'])) {
    // Fetch courses for the selected college
    $college_name = $_POST['college_name'];
    $courses_query = "SELECT DISTINCT course_name FROM colleges WHERE college_name = '$college_name'";
    $courses_result = $conn->query($courses_query);
}

// Handle fee addition (Insert new fee details)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_fee'])) {
    $college_name = $_POST['college_name'];
    $course_name = $_POST['course_name'];
    $particular = $_POST['particular'];
    $fee_amount = $_POST['fee_amount'];

    // Sanitize and validate input
    $college_name = $conn->real_escape_string($college_name);
    $course_name = $conn->real_escape_string($course_name);
    $particular = $conn->real_escape_string($particular);
    $fee_amount = $conn->real_escape_string($fee_amount);

    // Insert into the fees table
    $sql = "INSERT INTO fee_details (college_name, course, particular, fee_amount) 
            VALUES ('$college_name', '$course_name', '$particular', '$fee_amount')";

    if ($conn->query($sql) === TRUE) {
        $feedback = "<div class='success'>Fee details added successfully!</div>";
    } else {
        $feedback = "<div class='error'>Error: " . $conn->error . "</div>";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fees - Select College and Course</title>
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

        select,
        button,
        textarea,
        input[type="text"] {
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
        <h1>Fees Information</h1>

        <!-- Feedback message -->
        <?= $feedback; ?>

        <form action="" method="POST">
            <!-- Select College Name -->
            <label for="college_name">Select College</label>
            <select name="college_name" id="college_name" required onchange="this.form.submit()">
                <option value="">Select a College</option>
                <?php
                if ($colleges_result->num_rows > 0) {
                    while ($row = $colleges_result->fetch_assoc()) {
                        $selected = (isset($_POST['college_name']) && $_POST['college_name'] == $row['college_name']) ? "selected" : "";
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
                if (isset($_POST['college_name'])) {
                    $selected_college = $_POST['college_name'];
                    $courses_query = "SELECT DISTINCT course_name FROM colleges WHERE college_name = '$selected_college'";
                    $courses_result = $conn->query($courses_query);

                    if ($courses_result->num_rows > 0) {
                        while ($row = $courses_result->fetch_assoc()) {
                            echo "<option value='" . $row['course_name'] . "'>" . $row['course_name'] . "</option>";
                        }
                    }
                }
                ?>
            </select>

            <label for="particular">Particular</label>
            <input type="text" name="particular" id="particular" placeholder="Enter Particular (e.g. Tuition Fee)" required>

            <label for="fee_amount">Fee Amount</label>
            <input type="number" name="fee_amount" id="fee_amount" placeholder="Enter Fee Amount" required>

            <button type="submit" name="add_fee">Add Fee Details</button>
        </form>

        <!-- If course is selected, show the fee details -->
        <?php
        if (isset($_POST['course']) && isset($_POST['course'])) {
            $course_name = $_POST['course'];
            $college_name = $_POST['college_name'];

            // Fetch fee details for the selected college and course
            $fees_query = "SELECT * FROM fee_details WHERE college_name = '$college_name' AND course = '$course_name'";
            $fees_result = $conn->query($fees_query);

            if ($fees_result->num_rows > 0) {
                echo "<h2>Fee Details for $college_name - $course_name</h2>";
                echo "<table class='table'>";
                echo "<thead><tr><th>Particular</th><th>Fee Amount</th></tr></thead><tbody>";
                while ($fee = $fees_result->fetch_assoc()) {
                    echo "<tr><td>" . $fee['particular'] . "</td><td>" . $fee['fee_amount'] . "</td></tr>";
                }
                echo "</tbody></table>";
            } else {
                echo "<div class='error'>No fee details found for this course.</div>";
            }
        }
        ?>

    </div>
</body>
</html>

<?php
$conn->close();
?>
