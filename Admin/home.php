<?php
    // PHP backend to fetch total colleges, courses, and states from the database
    $servername = "localhost"; // Database host
    $username = "root";        // Database username
    $password = "";            // Database password
    $dbname = "reglog";        // Database name

    // Establish a database connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check for a connection error
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Query to fetch the total number of colleges
    $sql_colleges = "SELECT COUNT(*) AS total_colleges FROM colleges";
    $result_colleges = $conn->query($sql_colleges);
    $total_colleges = 0; // Default value
    if ($result_colleges->num_rows > 0) {
        $row = $result_colleges->fetch_assoc();
        $total_colleges = $row['total_colleges']; // Fetch the count
    }

    // Query to fetch the total number of courses
    $sql_courses = "SELECT COUNT(*) AS total_courses FROM courses_name";
    $result_courses = $conn->query($sql_courses);
    $total_courses = 0; // Default value
    if ($result_courses->num_rows > 0) {
        $row = $result_courses->fetch_assoc();
        $total_courses = $row['total_courses']; // Fetch the count
    }

    // Query to fetch the total number of states
    $sql_states = "SELECT COUNT(*) AS total_states FROM states";
    $result_states = $conn->query($sql_states);
    $total_states = 0; // Default value
    if ($result_states->num_rows > 0) {
        $row = $result_states->fetch_assoc();
        $total_states = $row['total_states']; // Fetch the count
    }

    $conn->close(); // Close the connection
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="icon" type="image/x-icon" href="../img/college.png">


    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            height: 100vh;
            background-color: #f8f9fc;
            overflow:hidden;
        }

        /* Sidebar Styles */
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

        h2 {
            text-align: center;
            font-size: 2rem;
            color: #495057;
            margin-bottom: 40px;
        }

        .content-container {
            display: none;
        }

        /* Button Styles */
        .btn {
            background-color: #007bff;
            color: #fff;
            padding: 15px;
            font-size: 1rem;
            text-align: center;
            border-radius: 8px;
            text-decoration: none;
            transition: background-color 0.3s, transform 0.2s;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .btn:hover {
            background-color: #0056b3;
            transform: translateY(-3px);
        }

        .btn:active {
            transform: translateY(0);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        /* Footer Styles */
        footer {
            -align: center;
    /* padding: 20px; */
    /* background-color: #007bff; */
    /* color: white; */
    font-size: 1rem;
    /* margin-top: 40px; */
    position: relative;
    /* width: 100%; */
    bottom: 0;
    position: fixed;
    width: 100%;
    text-align: center;
        }

        /* Dashboard Stats Container */
        .dashboard-stats {
            display: flex;
            justify-content: space-around;
            margin-top: 30px;
            margin-bottom: 40px;
        }

        .stat-box {
            background-color: #ffffff;
            border: 1px solid #ddd;
            padding: 20px;
            width: 200px;
            text-align: center;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        }

        .stat-box h3 {
            font-size: 1.5rem;
            color: #495057;
        }

        .stat-box p {
            font-size: 1.2rem;
            color: #007bff;
        }
        
    </style>
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
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
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Header Section -->
        <div class="header">
        <!-- <a class="navbar-brand" href=""></a> -->
        <img src="../img/college logo.png" alt="logo" style="    width: 13%;">
        <h1>Admin Dashboard</h1>
            <nav>
                <a href="../index.html">Home</a>
        
                <a href="../login.php">Logout</a>
            </nav>
        </div>

        <!-- Dashboard Stats Section -->
        <div class="dashboard-stats">
            <div class="stat-box">
                <h3>Total Colleges</h3>
                <p><?php echo $total_colleges; ?></p>
            </div>
            <div class="stat-box">
                <h3>Total Courses</h3>
                <p><?php echo $total_courses; ?></p>
            </div>
            <div class="stat-box">
                <h3>Total States</h3>
                <p><?php echo $total_states; ?></p>
            </div>
        </div>

        <!-- Dashboard Content -->
        <div class="content-container">
            <h2>Manage College Information</h2>
            <p>Here you can manage the college, courses, states, and other data.</p>
        </div>
    </div>

    <!-- Footer Section -->
    <footer>
        © 2024 College Info Admin. All Rights Reserved.
    </footer>

</body>
</html>
