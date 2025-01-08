<?php
// Database connection details
$host = "localhost";
$username = "root";
$password = "";
$dbname = "reglog";

// Connect to the database
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the college_id from the request
$college_id = isset($_GET['college_id']) ? $conn->real_escape_string($_GET['college_id']) : null;

if ($college_id) {
    // Fetch cutoff data by joining colleges and cutoffs tables
    $query = "
        SELECT 
            cutoffs.department_name, 
            cutoffs.prev_cutoff, 
            cutoffs.current_cutoff 
        FROM 
            cutoffs 
        INNER JOIN 
            colleges 
        ON 
            cutoffs.college_name = colleges.college_name
        WHERE 
            colleges.id = '$college_id'
    ";
    $result = $conn->query($query);
} else {
    $result = false; // No college_id provided
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Department Cutoffs</title>
    <link rel="icon" type="image/x-icon" href="../img/college.png">

    <link href="https://fonts.googleapis.com/css?family=Raleway:400,400i,500,500i,600,600i,700,700i,800,800i" rel="stylesheet">
    <link rel="stylesheet" href="../css/bootstrap.min.css"/>
    <link rel="stylesheet" href="../css/font-awesome.min.css"/>
    <link rel="stylesheet" href="../css/style.css"/>
    <style>
        /* Custom styles for table */
        .container { padding: 20px; background-color: #fff; border-radius: 8px; }
        h1 { text-align: center; color: #333; }
        .cutoff-table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .cutoff-table th, .cutoff-table td { padding: 15px; border: 1px solid #ddd; }
        .cutoff-table th { background-color: #f2f2f2; }
        body{
            background-color: #f6f6f6 !important;
        }
footer{
    background:#ccc;
    color:#000;
    height:70px;

}
footer p{
    padding:20px;
    text-align:center;
    color:#000;
}
    </style>
</head>
<body>
    <?php include('navbar.php') ?>
    <div class="cuttoff-section py-5">
    <div class="container">
        <h1>Department Cutoffs</h1>
        <table class="cutoff-table">
            <thead>
                <tr>
                    <th>Department Name</th>
                    <th>Previous Year Cutoff</th>
                    <th>Current Year Cutoff</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result && $result->num_rows > 0) {
                    // Fetch and display each row
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>" . htmlspecialchars($row['department_name']) . "</td>
                                <td>" . htmlspecialchars($row['prev_cutoff']) . "</td>
                                <td>" . htmlspecialchars($row['current_cutoff']) . "</td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='3'>No cutoff data available.</td></tr>";
                }

                // Close the database connection
                $conn->close();
                ?>
            </tbody>
        </table>
    </div>
    
    </div><footer>
        <p>© 2024 All Rights Reserved.

</p>
    </footer>
</body>
</html>
