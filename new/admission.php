<?php
// Database connection
$host = "localhost"; // Replace with your host
$username = "root";  // Replace with your MySQL username
$password = "";      // Replace with your MySQL password
$dbname = "reglog";  // Replace with your database name

$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize an empty array for admission details
$admissions = [];

// Get the college_id from the request
$college_id = isset($_GET['college_id']) ? $_GET['college_id'] : null;

if ($college_id) {
    // Fetch admission data based on the college_id
    $query = "
        SELECT 
            admission_details.admission_description, 
            admission_details.particulars, 
            admission_details.highlights,
            colleges.college_name
        FROM 
            admission_details 
        INNER JOIN 
            colleges 
        ON 
            admission_details.college_name = colleges.college_name
        WHERE 
            colleges.id = '$college_id'
    ";
    $result = $conn->query($query);

    // Check if the query returned any rows
    if ($result && $result->num_rows > 0) {
        // Populate the admissions array with the results
        while ($row = $result->fetch_assoc()) {
            $admissions[] = $row;
        }
    }
} else {
    echo "No college ID provided.";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admission Page</title>
    <link rel="icon" type="image/x-icon" href="../img/college.png">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Raleway:400,400i,500,500i,600,600i,700,700i,800,800i" rel="stylesheet">
    <!-- Stylesheets -->
    <link rel="stylesheet" href="../css/bootstrap.min.css"/>
    <link rel="stylesheet" href="../css/font-awesome.min.css"/>
    <link rel="stylesheet" href="../css/owl.carousel.css"/>
    <link rel="stylesheet" href="../css/style.css"/>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            /* padding: 20px; */
        }

        .container {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            margin-bottom: 30px;
        }

        .section-title {
            margin-bottom: 20px;
            font-size: 1.5em;
            color: #333;
        }

        .table {
            margin-top: 20px;
        }

        th, td {
            padding: 15px;
            text-align: left;
        }

        th {
            background-color: #f4f4f4;
        }

        td {
            background-color: #fafafa;
        }

        .table-container {
            margin-top: 30px;
        }

        .description-section {
            background-color: #f8f8f8;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
<?php include('navbar.php') ?>

    <!-- Header section -->
    <!-- <header class="header-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-3">
                    <div class="site-logo">
                        <img src="img/logo.png" alt="Logo">
                    </div>
                    <div class="nav-switch">
                        <i class="fa fa-bars"></i>
                    </div>
                </div>
                <div class="col-lg-9 col-md-9">
                    <nav class="main-menu">
                        <ul>
                            <li><a href="../index.html">Home</a></li>
                            <li><a href="../aboutus.html">About us</a></li>
                            <li><a href="../courses.html">Courses</a></li>
                            <li><a href="../blog.html">News</a></li>
                            <li><a href="../contact.html">Contact</a></li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </header> -->
    <!-- Header section end -->

    <div class="py-5">
    <div class="container">
        <h1>Admission Details</h1>

        <!-- Admission Description Section -->
        <div class="description-section">
            <?php
            // Display admission description if available
            if (!empty($admissions)) {
                echo "<strong>Admission Description:</strong> " . htmlspecialchars($admissions[0]['admission_description']);
            } else {
                echo "<strong>Admission Description:</strong> No description available.";
            }
            ?>
        </div>

        <!-- Table with Admission Details -->
        <div class="table-container">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Particular</th>
                        <th>Highlight</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Loop through the admission data and display particulars and highlights
                    foreach ($admissions as $admission) {
                        $particulars = explode("\n", $admission['particulars']); // Split by newline
                        $highlights = explode("\n", $admission['highlights']);  // Split by newline
                        $num_items = max(count($particulars), count($highlights)); // Determine the largest list

                        // Display particulars and highlights
                        for ($i = 0; $i < $num_items; $i++) {
                            $particular = isset($particulars[$i]) ? htmlspecialchars($particulars[$i]) : "";
                            $highlight = isset($highlights[$i]) ? htmlspecialchars($highlights[$i]) : "";

                            echo "<tr>
                                <td>{$particular}</td>
                                <td>{$highlight}</td>
                              </tr>";
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    </div>
<?php include('footer.php')?>

</body>
</html>
