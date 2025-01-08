<?php
// Database connection
$host = "localhost";
$username = "root";
$password = "";
$dbname = "reglog";

$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission (Insert Placement Data)
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['submit'])) {
    $college_name = $conn->real_escape_string($_POST['college_name']);
    $course_name = $conn->real_escape_string($_POST['course_name']);
    $average_package = $conn->real_escape_string($_POST['average_package']);
    $minimum_package = $conn->real_escape_string($_POST['minimum_package']);
    $placement_rate = $conn->real_escape_string($_POST['placement_rate']);
    $participated_students = $conn->real_escape_string($_POST['participated_students']);
    $accepted_offers = $conn->real_escape_string($_POST['accepted_offers']);
    $total_recruiters = $conn->real_escape_string($_POST['total_recruiters']);

    // Handle file upload for company images
    $uploaded_images = [];
    if (!empty($_FILES['company_images']['name'][0])) {
        $upload_dir = "uploads/";
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        foreach ($_FILES['company_images']['tmp_name'] as $key => $tmp_name) {
            $file_name = uniqid() . "_" . basename($_FILES['company_images']['name'][$key]);
            $target_file = $upload_dir . $file_name;

            if (move_uploaded_file($tmp_name, $target_file)) {
                $uploaded_images[] = $target_file; // Save file paths
            }
        }
    }

    // Convert uploaded images to JSON
    $company_logos = json_encode($uploaded_images);

    // Insert data into database
    $sql = "INSERT INTO placements 
            (college_name, course, average_package, minimum_package, placement_rate, participated_students, accepted_offers, total_recruiters, company_logos) 
            VALUES ('$college_name', '$course_name', '$average_package', '$minimum_package', '$placement_rate', '$participated_students', '$accepted_offers', '$total_recruiters', '$company_logos')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Placement details added successfully!');</script>";
    } else {
        echo "<script>alert('Error: " . $conn->error . "');</script>";
    }
}

// Get college_id from the URL or set to null
$college_id = isset($_GET['college_id']) ? $conn->real_escape_string($_GET['college_id']) : null;

if ($college_id) {
    // SQL query to fetch both scholarship and placement data for a particular college
    $sql = "
        SELECT 
            scholarships.scholarship,
            scholarships.about_scholarship,
            colleges.college_name
        FROM 
            scholarships
        LEFT JOIN 
            colleges ON scholarships.college = colleges.college_name

        WHERE 
            colleges.id = '$college_id'
    ";
    $result = $conn->query($sql);
} else {
    $result = false; // No college_id provided
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Placement and Scholarship Management</title>
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
            background-color: #f8f9fa;
            /* padding: 20px; */
        }

        .container {
            max-width: 1100px;
            margin: 0 auto;
            background: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.1);
        }

        h1, h2, h3 {
            text-align: center;
            color: #007bff;
        }

        .placement-table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }

        .placement-table th, .placement-table td {
            padding: 15px;
            text-align: left;
            border: 1px solid #ccc;
        }

        .placement-table th {
            background-color: #007bff;
            color: #fff;
        }

        .image-section {
            margin-top: 20px;
        }

        .image-container {
            display: flex;
            gap: 15px;
            justify-content: center;
        }

        .image-container img {
            width: 100%;
            height: 150px;
            object-fit: cover;
            border-radius: 5px;
            border: 1px solid #ddd;
            padding: 5px;
            background: #f8f8f8;
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
						<img src="img/logo.png" alt="">
                        Logo
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

   <div class="scholarship py-5">
   <div class="container">
        <h2>Placement & Scholarship Records</h2>

        <!-- Placement Table -->
        

        <!-- Scholarship Information -->
        <h3>Scholarships Offered</h3>
        <table class="placement-table">
            <thead>
                <tr>
                    <th>Scholarship Name</th>
                    <th>About Scholarship</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Reset the pointer to fetch scholarship details
                $result->data_seek(0);
                while ($row = $result->fetch_assoc()) {
                    echo "
                        <tr><td>" . $row['scholarship'] . "</td><td>" . $row['about_scholarship'] . "</td></tr>
                    ";
                }
                ?>
            </tbody>
        </table>

        <!-- Company Logos
        <div class="image-section">
            <h3>Companies</h3>
            <div class="image-container">
                <?php
                // Reset the pointer to the first record
                $result->data_seek(0);
                while ($row = $result->fetch_assoc()) {
                    $company_logos = json_decode($row['company_logos']);
                    if (!empty($company_logos)) {
                        foreach ($company_logos as $logo) {
                            echo "<img src='" . $logo . "' alt='Company Logo'>";
                        }
                    }
                }
                ?>
            </div>
        </div> -->
    </div>
   
   </div> <?php include('footer.php')?>

</body>
</html>

<?php
$conn->close();
?>
