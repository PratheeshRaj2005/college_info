<?php
// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "reglog";

// Create a connection to the database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle course and state filters
$filters = [];
if (isset($_GET['course_name']) && !empty($_GET['course_name'])) {
    $course_name = $conn->real_escape_string($_GET['course_name']);
    $filters[] = "c.course_name = '$course_name'";  // Adding course filter
}

if (isset($_GET['state_name']) && !empty($_GET['state_name'])) {
    $state_name = $conn->real_escape_string($_GET['state_name']);
    $filters[] = "c.state = '$state_name'";  // Adding state filter
}

// Combine the filters into the query if any filters are set
$course_filter = '';
if (!empty($filters)) {
    $course_filter = " WHERE " . implode(" AND ", $filters);  // Combining filters with AND
}

// SQL to fetch colleges based on selected course and state
$sql_fetch = "
    SELECT DISTINCT c.id, c.college_name, c.college_image, c.about_college, c.state
    FROM colleges AS c
    JOIN courses_name AS cn ON c.course_name = cn.course_name" . $course_filter;

$result_colleges = $conn->query($sql_fetch);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Colleges</title>
    <link rel="icon" type="image/x-icon" href="../img/college.png">

	<!-- Google Fonts -->
	<link href="https://fonts.googleapis.com/css?family=Raleway:400,400i,500,500i,600,600i,700,700i,800,800i" rel="stylesheet">

	<!-- Stylesheets -->
	<link rel="stylesheet" href="../css/bootstrap.min.css"/>
	<link rel="stylesheet" href="../css/font-awesome.min.css"/>
	<link rel="stylesheet" href="../css/owl.carousel.css"/>
	<link rel="stylesheet" href="../css/style.css"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <style>
        body {
            font-family: Arial, sans-serif;
            /* background-color: #f9f9f9; */
            background:url('../images/hallway-with-bag-floor.jpg');
            background-size:cover;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            height:100%;
        }

        /* Container for the form and college cards */
        .container {
            width: 90%;
            margin: 20px auto;
            /* display: flex; */
            flex-wrap: wrap;
            justify-content: space-between;
        }

        /* Styling for the form */
        form {
            width: 100%;
            margin-bottom: 30px;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        form label {
            font-size: 1.1em;
            margin-right: 10px;
        }

        form select, form button {
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1em;
            outline: none;
        }

        form button {
            background-color: #d82a4e;
            color: white;
            cursor: pointer;
            transition: background-color 0.3s ease;
            padding: 8px 10px;
        }

        form button:hover {
            background-color: #0056b3;
        }

        /* Styling for the college cards */
        .college-card {
            background: rgba(255, 255, 255, 0.2);
    border-radius: 15px;
    box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    /* border: 1px solid rgba(255, 255, 255, 0.3); */
    padding: 20px;
    color: #fff;
    /* text-align: center;
    */        margin-bottom: 20px;

        }   
        .college-card img {
            width: 100%;
            height: auto;
            object-fit: cover;
            border-bottom: 1px solid #ddd;
        }

        .college-card .content {
            padding: 15px;
        }

        .college-card .content h3 {
            font-size: 1.3em;
            margin: 10px 0;
            color: #fff;
        }

        .college-card .content p {
            font-size: 0.9em;
            color: #fff;
            margin: 10px 0;
        }

        /* Hover effect for college cards */
        .college-card:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }
        .select-course{
            display: flex;
            width: 100%;
            /* margin-top: 100px; */
        }
        .coll-card{
            display: flex;
            width: 100%;
        }
        .coll-card .college-card{
            flex: 1;
        }
        /* Responsive design */
        @media (max-width: 1024px) {
            .college-card {
                width: 45%;
            }
        }

        @media (max-width: 768px) {
            .college-card {
                width: 100%;
            }

            .container {
                flex-direction: column;
                align-items: center;
            }
        }

    </style>
    <style>
   

    .college-card img {
        max-height: 150px;
        object-fit: cover;
        border-radius: 5px;
        margin-bottom: 10px;
    }

    .college-card .content {
        padding: 10px;
    }

    .college-card h3 {
        font-size: 1.25rem;
        margin-bottom: 10px;
    }

    .view-more-btn {
        color: #007bff;
        text-decoration: none;
        font-weight: bold;
    }

    .view-more-btn:hover {
        text-decoration: underline;
    }
    .navbar-expand-lg .navbar-collapse{
            flex-grow: 0;
        }
        .container-fluid{
            width: 1200px;
        }
        .navbar-expand-lg .navbar-nav{
            gap: 60px;
        }
        a img{
            width: 30% !important;
        }
</style>

</head>
<body>


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
							<li><a href="index.html">Home</a></li>
							<li><a href="../aboutus.html">About us</a></li>
							<li><a href="courses.html">Courses</a></li>
							<li><a href="blog.html">News</a></li>
							<li><a href="contact.html">Contact</a></li>
						</ul>
					</nav>
				</div>
			</div>
		</div>
	</header> -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
        <a class="navbar-brand" href=""><img src="../img/college logo.png" style="width= 30% !important;" alt=""></a>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                 <!-- Guidance Dropdown -->
                 <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="guidanceDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Guidance
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="guidanceDropdown">
                            <li><a class="dropdown-item" href="../career.php">College Selection</a></li>
                            <li><a class="dropdown-item" href="../guidence.php">Importance of College</a></li>
                        </ul>
                    </li>
              <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="#">Home</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="../aboutus.html">About</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="../login.php">Course</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="../contact.html">Contact</a>
              </li>
              <!-- <li class="nav-item">
                <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">Disabled</a>
              </li> -->
            </ul>
            
          </div>
        </div>
      </nav>

      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

	<!-- Header section end -->
<div class="select-course">
<div class="container">
<form method="GET" action="">
    <label for="course_name">Select Course:</label>
    <select name="course_name" id="course_name">
        <option value="">--Select a Course--</option>
        <!-- Populate dynamically from the courses_name table -->
        <?php
        $sql_courses = "SELECT DISTINCT course_name FROM courses_name";
        $result_courses = $conn->query($sql_courses);
        while ($row_course = $result_courses->fetch_assoc()) {
            $selected = (isset($_GET['course_name']) && $_GET['course_name'] == $row_course['course_name']) ? 'selected' : '';
            echo "<option value='" . htmlspecialchars($row_course['course_name']) . "' $selected>" . htmlspecialchars($row_course['course_name']) . "</option>";
        }
        ?>
    </select>

    <label for="state_name">Select State:</label>
    <select name="state_name" id="state_name">
        <option value="">--Select a State--</option>
        <!-- Populate dynamically from the states table -->
        <?php
        $sql_states = "SELECT DISTINCT state_name FROM states"; // Fetch states from the states table
        $result_states = $conn->query($sql_states);
        while ($row_state = $result_states->fetch_assoc()) {
            $selected_state = (isset($_GET['state_name']) && $_GET['state_name'] == $row_state['state_name']) ? 'selected' : '';
            echo "<option value='" . htmlspecialchars($row_state['state_name']) . "' $selected_state>" . htmlspecialchars($row_state['state_name']) . "</option>";
        }
        ?>
    </select>

    <button type="submit" style="margin-left:340px;width:14%">Search</button>
</form>
<div class="row">
    <?php
    if ($result_colleges->num_rows > 0) {
        while ($row = $result_colleges->fetch_assoc()) {
            echo "
            <div class='col-md-4'> <!-- Use col-md-4 to fit 3 cards in one row -->
                <div class='college-card'>
                    <img src='" . htmlspecialchars($row['college_image']) . "' alt='" . htmlspecialchars($row['college_name']) . "' class='img-fluid'>
                    <div class='content'>
                        <h3>" . htmlspecialchars($row['college_name']) . "</h3>
                        <p>" . htmlspecialchars($row['about_college']) . "</p>
                        <p><strong>State:</strong> " . htmlspecialchars($row['state']) . "</p>
                        <a href='about_college.php?college_id=" . urlencode($row['id']) . "' class='view-more-btn'>View More</a>
                    </div>
                </div>
            </div>";
        }
    } else {
        echo "<p>No colleges found for the selected course and state.</p>";
    }
    ?>
</div>

</div>
</div>
</body>
</html>

<?php
$conn->close();
?>
