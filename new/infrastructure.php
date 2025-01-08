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

// Initialize infrastructures array
$infrastructures = [];

// Get the college_id from the request
$college_id = isset($_GET['college_id']) ? intval($_GET['college_id']) : null;

if ($college_id) {
    // Fetch infrastructure details
    $stmt = $conn->prepare("
        SELECT 
            infrastructure.particular,
            infrastructure.particular_text,
            infrastructure.highlights_text,
            infrastructure.infrastructure_images,
            colleges.college_name
        FROM 
            infrastructure
        INNER JOIN 
            colleges 
        ON 
            infrastructure.college_name = colleges.college_name
        WHERE 
            colleges.id = ?
    ");
    $stmt->bind_param("i", $college_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $infrastructures[] = $row;
        }
    }

    $stmt->close();
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Infrastructure Details</title>
    <link rel="icon" type="image/x-icon" href="../img/college.png">

      <!-- Google Fonts -->
      <link href="https://fonts.googleapis.com/css?family=Raleway:400,400i,500,500i,600,600i,700,700i,800,800i" rel="stylesheet">
    <!-- Stylesheets -->
    <link rel="stylesheet" href="../css/bootstrap.min.css"/>
    <link rel="stylesheet" href="../css/font-awesome.min.css"/>
    <link rel="stylesheet" href="../css/style.css"/>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            /* padding: 20px; */
        }
        .container {
            max-width: 900px;
            margin: 0 auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        h1, h3 {
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table th, table td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: left;
        }
        table th {
            background-color: #007bff;
            color: #fff;
        }
        .image-section {
            margin-top: 20px;
            text-align: center;
        }
        .image-container img {
            width: 120px;
            height: 120px;
            object-fit: cover;
            margin: 5px;
            border-radius: 5px;
            border: 1px solid #ddd;
        }
        .no-image {
            font-size: 0.9em;
            color: #777;
        }
        footer{
            position: fixed;
    bottom: 0;
    width: 100%;
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
    <div class="py-5">
    <div class="container">
        <h1>Infrastructure Details</h1>

        <?php if (!empty($infrastructures)): ?>

            <!-- Highlights Section -->
            <div class="highlights">
                <h3>Highlights</h3>
                <p><?= htmlspecialchars($infrastructures[0]['highlights_text']); ?></p>
            </div>

            <!-- Infrastructure Table -->
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Particular</th>
                        <th>About</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($infrastructures as $index => $infrastructure): ?>
                        <tr>
                            <td><?= $index + 1; ?></td>
                            <td><?= htmlspecialchars($infrastructure['particular']); ?></td>
                            <td><?= htmlspecialchars($infrastructure['particular_text']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <!-- Image Section -->
            <div class="image-section">
                <h3>Images</h3>
                <div class="image-container">
                    <?php
                    $has_images = false;
                    foreach ($infrastructures as $infrastructure) {
                        $images = array_filter(explode(',', $infrastructure['infrastructure_images']));
                        foreach ($images as $image) {
                            echo '<img src="' . htmlspecialchars($image) . '" alt="Infrastructure Image">';
                            $has_images = true;
                        }
                    }
                    if (!$has_images) {
                        echo '<p class="no-image">No images available.</p>';
                    }
                    ?>
                </div>
            </div>

        <?php else: ?>
            <p>No infrastructure details available for the selected college.</p>
        <?php endif; ?>
    </div>
    </div>
    <?php include('footer.php')?>
</body>
</html>
