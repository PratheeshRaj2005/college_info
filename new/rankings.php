<?php
// Database connection
$host = "localhost";
$username = "root";
$password = "";
$dbname = "reglog"; // Change this to your actual database name

$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get college_id from the URL or set to null
$college_id = isset($_GET['college_id']) ? $conn->real_escape_string($_GET['college_id']) : null;

// Fetch only rankings based on college_id
if ($college_id) {
    $sql = "
        SELECT 
            rankings.ranking_logo, 
            rankings.ranking_title, 
            rankings.about_rankings, 
            colleges.college_name
        FROM 
            rankings
        LEFT JOIN 
            colleges 
        ON 
            rankings.college_name = colleges.college_name
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
    <title>Rankings</title>
    <link rel="icon" type="image/x-icon" href="../img/college.png">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Raleway:400,400i,500,500i,600,600i,700,700i,800,800i" rel="stylesheet">
    <!-- Stylesheets -->
    <link rel="stylesheet" href="../css/bootstrap.min.css"/>
    <link rel="stylesheet" href="../css/font-awesome.min.css"/>
    <link rel="stylesheet" href="../css/style.css"/>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; background-color: #f4f4f4; }
        .container { padding: 40px; max-width: 1200px; margin: 0 auto; }
        .ranking-card { background-color: white; border-radius: 8px; box-shadow: 0 0 15px rgba(0, 0, 0, 0.15); margin-bottom: 30px; padding: 30px; }
        .ranking-card img { width: 200px; height: auto; margin-right: 30px; border-radius: 8px; }
        .ranking-card h3 { margin: 0; font-size: 2em; color: #333; }
        .ranking-card p { font-size: 1.2em; color: #555; line-height: 1.6; }
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
	</header>
	Header section end -->
    <header>
        <h1 class="text-center py-3" >Rankings</h1>
    </header>

    <div class="container">
    <?php
    if ($result && $result->num_rows > 0) {
        echo '<div class="ranking-grid">'; // Start grid container
        while ($row = $result->fetch_assoc()) {
            echo '<div class="ranking-card">';
            echo '<img src="' . htmlspecialchars($row['ranking_logo']) . '" alt="Ranking Logo">';
            echo '<div>';
            echo '<h3>' . htmlspecialchars($row['ranking_title']) . '</h3>';
            echo '<p>' . htmlspecialchars($row['about_rankings']) . '</p>';
            echo '<p><strong>College:</strong> ' . htmlspecialchars($row['college_name']) . '</p>';
            echo '</div>';
            echo '</div>';
        }
        echo '</div>'; // End grid container
    } else {
        echo "<p>No rankings available for this college.</p>";
    }
    ?>
</div>

<style>
.container {
    width: 100%;
    padding: 20px;
    box-sizing: border-box;
}

.ranking-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr); /* Four columns */
    gap: 20px; /* Space between cards */
}

.ranking-card {
    border: 1px solid #ccc;
    border-radius: 8px;
    padding: 15px;
    text-align: center;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s;
}

.ranking-card img {
    width: 100%;
    max-height: 150px;
    object-fit: contain;
    margin-bottom: 10px;
}

.ranking-card h3 {
    font-size: 18px;
    margin: 10px 0;
}

.ranking-card p {
    font-size: 14px;
    color: #555;
}

.ranking-card:hover {
    transform: scale(1.05);
}
footer{
    position: fixed;
    bottom:0; 
    width:100%;
}
</style>
<?php include('footer.php')?>

</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
