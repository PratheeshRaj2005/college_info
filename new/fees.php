<?php
// Database connection
$host = "localhost";  // Replace with your host
$username = "root";   // Replace with your MySQL username
$password = "";       // Replace with your MySQL password
$dbname = "reglog";   // Replace with your database name

$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission for adding fee details
if (isset($_POST['submit'])) {
    // Sanitize form inputs
    $college_name = $conn->real_escape_string($_POST['college_name']);
    $course = $conn->real_escape_string($_POST['course']);
    $about_fees = $conn->real_escape_string($_POST['about_fees']);
    $particular = $conn->real_escape_string($_POST['particular']);
    $fee_amount = $conn->real_escape_string($_POST['fee_amount']);

    // Use prepared statements to insert data into the database
    $stmt = $conn->prepare("
        INSERT INTO fee_details (college_name, course, about_fees, particular, fee_amount) 
        VALUES (?, ?, ?, ?, ?)
    ");
    $stmt->bind_param("sssss", $college_name, $course, $about_fees, $particular, $fee_amount);

    // Execute the query
    if ($stmt->execute()) {
        echo "Fee details added successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();
}

// Get the college_id from the request (if available)
$college_id = isset($_GET['college_id']) ? $conn->real_escape_string($_GET['college_id']) : null;

if ($college_id) {
    // Fetch fee details based on the college_id
    $query = "
        SELECT 
            fee_details.particular,
            fee_details.fee_amount
        FROM 
            fee_details 
        INNER JOIN 
            colleges 
        ON 
            fee_details.college_name = colleges.college_name
        WHERE 
            colleges.id = '$college_id'
    ";
    $result = $conn->query($query);
} else {
    $result = false; // No college_id provided
}

// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fee Details</title>
    <link rel="icon" type="image/x-icon" href="../img/college.png">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Raleway:400,400i,500,500i,600,600i,700,700i,800,800i" rel="stylesheet">
    <!-- Stylesheets -->
    <link rel="stylesheet" href="../css/bootstrap.min.css"/>
    <link rel="stylesheet" href="../css/font-awesome.min.css"/>
    <link rel="stylesheet" href="../css/owl.carousel.css"/>
    <link rel="stylesheet" href="../css/style.css"/>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
        }

        .container {
            max-width: 900px;  /* Reduced max-width to make the container smaller */
            margin: 20px auto;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 30px;  /* Reduced padding */
        }

        h1, h2 {
            text-align: center;
            color: #333;
        }

        .about-admission {
            margin-bottom: 20px;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #f5f5f5;
            line-height: 1.6;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #007bff;
            color: #fff;
        }

        td {
            background-color: #f9f9f9;
        }

        .right-column {
            text-align: right;
        }

        @media (max-width: 600px) {
            .container {
                padding: 15px;
            }

            h1, h2 {
                font-size: 1.5rem;
            }

            th, td {
                font-size: 0.9rem;
            }
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
                            <li><a href="../college.php">Courses</a></li>
                            <li><a href="../blog.html">News</a></li>
                            <li><a href="../contact.html">Contact</a></li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </header> -->
    <!-- Header section end -->

    <div class="container">
        <h1>Fee Details</h1>

        <!-- About Admission Section -->
        <div class="about-admission">
            <h2>About Admission</h2>
            <p>
                Admission to this program is based on merit and involves the submission of all required documents. 
                Applicants are encouraged to review the admission guidelines carefully. The process includes an online 
                application, followed by verification and payment of fees as detailed below.
            </p>
        </div>

        <h2>Fee Details</h2>
        <table>
            <thead>
                <tr>
                    <th>Particular</th>
                    <th class="right-column">Amount (₹)</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result && $result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['particular']) . "</td>";
                        echo "<td class='right-column'>" . htmlspecialchars($row['fee_amount']) . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='2'>No fee details available.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
    <?php include('footer.php') ?>

</body>
</html>
                