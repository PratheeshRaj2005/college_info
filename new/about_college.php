<?php
// Database connection credentials
define('DB_HOST', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', ''); // Set your database password here
define('DB_NAME', 'reglog');

// Establish the database connection
$conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get college ID from the URL and validate it
$college_id = isset($_GET['college_id']) ? intval($_GET['college_id']) : null;
$college = null; // Initialize $college to avoid undefined variable warnings

if ($college_id) {
    // Fetch the college details based on the college_id
    $sql = "SELECT * FROM about_college WHERE college = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $college_id); // 'i' is for integer binding
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $college = $result->fetch_assoc(); // Set $college only if result is found
    }
    $stmt->close();
}

$conn->close(); // Close the database connection
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About College</title>
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
            background-color: #f8f9fa;
        }

        .college-container {
            padding: 20px;
            max-width: 75%;
            margin: 0 auto;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-top:30px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .logo-name {
            display: flex;
            align-items: flex-start;
        }

        .logo {
            margin-right: 20px;
        }

        .logo img {
            max-width: 100px;
            border-radius: 8px;
            border: 1px solid #ddd;
        }

        .college-name-location {
            display: flex;
            flex-direction: column;
        }

        .college-name {
            font-size: 2em;
            font-weight: bold;
            color: #333;
            margin-bottom: 5px;
        }

        .location {
            font-size: 1.1em;
            color: #555;
        }

        .contact {
            font-size: 1.1em;
            color: #555;
            text-align: right;
        }

        .about-college h2 {
            font-size: 1.3em;
            color: #333;
            margin-bottom: 10px;
        }

        .about-college p {
            font-size: 1em;
            color: #666;
            line-height: 1.6;
        }

        .gallery h3 {
            margin-bottom: 10px;
            color: #333;
        }

        .scroll-images {
            display: flex;
            overflow-x: auto; /* Enable horizontal scrolling */
            gap: 15px; /* Add space between images */
            padding: 10px;
            scroll-behavior: smooth; /* Enable smooth scrolling */
            scrollbar-width: thin; /* Thin scrollbar for modern browsers */
            scrollbar-color: #007bff #f8f9fa; /* Custom scrollbar colors */
        }

        .scroll-images img {
            max-width: 300px; /* Larger image width */
            height: auto; /* Maintain aspect ratio */
            border-radius: 8px;
            border: 1px solid #ddd;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Add shadow for depth */
        }

        .scroll-images::-webkit-scrollbar {
            height: 8px;
        }

        .scroll-images::-webkit-scrollbar-thumb {
            background-color: #007bff;
            border-radius: 4px;
        }

        .scroll-images::-webkit-scrollbar-track {
            background-color: #f8f9fa;
        }

        /* Button Container Styling */
        .button-container {
            margin: 20px 0;
            padding-left:70px;
            display: flex;
            justify-content: flex-start;
            gap: 18px;
            flex-wrap: nowrap;
            overflow-x: auto;
        }

        .button-container button {
            padding: 10px 18px;
            font-size: 1em;
            color: #fff;
            background-color: #007bff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .button-container button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    
	<!-- Header section -->
     <?php include('navbar.php') ?>
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
							<li><a href="../blog.html">News</a></li>
							<li><a href="../contact.html">Contact</a></li>
						</ul>
					</nav>
				</div>
			</div>
		</div>
	</header> -->
	<!-- Header section end -->
<div class="college-container">
    <?php if ($college): ?>
        <div class="header">
            <!-- Logo and Name Section -->
            <div class="logo-name">
                <div class="logo">
                    <img src="<?php echo htmlspecialchars($college['logo'] ?? 'default-logo.png'); ?>" alt="College Logo">
                </div>
                <div class="college-name-location">
                    <div class="college-name">
                        <?php echo htmlspecialchars($college['college_name'] ?? 'N/A'); ?>
                    </div>
                    <div class="location">
                        Location: <?php echo htmlspecialchars($college['location'] ?? 'N/A'); ?>
                    </div>
                </div>
            </div>

            <!-- Contact Information -->
            <div class="contact">
                Contact: <?php echo htmlspecialchars($college['contact'] ?? 'N/A'); ?>
            </div>
        </div>

        <!-- Button Container -->
        <div class="button-container">
        <button onclick="navigateToPage('cutoff.php')">Cutoff</button>
        <button onclick="navigateToPage('rankings.php')">Rankings</button>
            <button onclick="navigateToPage('placements.php')">Placements</button>
            <button onclick="navigateToPage('scholarship.php')">Scholarships</button>
            <button onclick="navigateToPage('fees.php')">Fees</button>
            <button onclick="navigateToPage('news.php')">News</button>
            <button onclick="navigateToPage('infrastructure.php')">Infrastructure</button>
        </div>

        <!-- About College Section -->
        <div class="about-college">
            <h2>About College</h2>
            <p><?php echo nl2br(htmlspecialchars($college['about_college'] ?? 'Information not available.')); ?></p>
        </div>

        <!-- Image Gallery Section -->
        <div class="gallery">
            <h3>Gallery</h3>
            <div class="scroll-images">
                <?php for ($i = 1; $i <= 4; $i++): ?>
                    <?php if (!empty($college["image$i"])): ?>
                        <img src="<?php echo htmlspecialchars($college["image$i"]); ?>" alt="College Image <?php echo $i; ?>">
                    <?php endif; ?>
                <?php endfor; ?>
            </div>
        </div>
    <?php else: ?>
        <p>College information not found or invalid ID.</p>
    <?php endif; ?>
</div>

<script>
    // Function to handle button clicks and navigate to respective pages
    function navigateToPage(page) {
        // Redirect to the page by changing the window's location
        window.location.href = page;
    }
</script>
<script>
    // Function to get query parameters from the URL
    function getQueryParam(param) {
        const urlParams = new URLSearchParams(window.location.search);
        return urlParams.get(param);
    }

    function navigateToPage(url) {
        // Extract college_id from the current URL
        const collegeId = getQueryParam('college_id');
        if (collegeId) {
            // Construct the target URL with the college_id
            const fullUrl = `${url}?college_id=${encodeURIComponent(collegeId)}`;
            // Redirect to the target URL
            window.location.href = fullUrl;
        } else {
            alert('College ID not found in the current URL.');
        }
    }
</script>
<?php include('footer.php')?>
</body>
</html>
