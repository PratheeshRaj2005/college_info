<?php
// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "reglog";

// Establish a database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check for a connection error
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to fetch faculty data
$sql = "SELECT * FROM faculty";
$result = $conn->query($sql);

// Check if data exists
if ($result->num_rows > 0) {
    $faculty_data = [];
    while ($row = $result->fetch_assoc()) {
        $faculty_data[] = $row;
    }
} else {
    $faculty_data = [];
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faculty Page</title>
    <!-- Stylesheets -->
    <link rel="stylesheet" href="css/bootstrap.min.css"/>
    <link rel="stylesheet" href="css/font-awesome.min.css"/>
    <link rel="stylesheet" href="css/owl.carousel.css"/>
    <link rel="stylesheet" href="css/style.css"/>
    <style>
        body {
            background-image: linear-gradient(to top, #cfd9df 0%, #e2ebf0 100%);
            color: #333;
            font-family: 'Arial', sans-serif;
        }

        .header-section {
            margin-bottom: 20px;
        }

        .navbar-nav {
            gap: 15%;
        }

        h1 {
            font-size: 2rem;
            color: #2c3e50;
            text-align: center;
            margin: 20px 0;
        }

        .subheading {
            text-align: center;
            font-size: 1.2rem;
            color: #555;
            margin-bottom: 40px;
        }

        .faculty-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
        }

        .faculty-card {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            width: 300px;
            text-align: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .faculty-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }

        .faculty-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .faculty-card .info {
            padding: 15px;
        }

        .faculty-card h3 {
            margin-bottom: 10px;
            color: #34495e;
        }

        .faculty-card p {
            margin-bottom: 5px;
            font-size: 0.9rem;
        }

        .faculty-card strong {
            color: #7f8c8d;
        }

        footer {
            text-align: center;
            padding: 20px;
            background-color: #2c3e50;
            color: white;
            margin-top: 30px;
        }

        footer a {
            color: #1abc9c;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <!-- Header section -->
    <header class="header-section">
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container-fluid">
                <a class="navbar-brand" href="#"><img src="img/college logo.png" alt="College Logo" style="width: 30%; margin-right: 80%;"></a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="index.html">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="aboutus.html">About</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="login.php">Course</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="contact.html">Contact</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="calculator.php">Calculator</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="faculty.php">Faculty</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <!-- Main content -->
    <main>
        <h1>Faculties Are Here to Guide You</h1>
        <p class="subheading">You are free to contact them. Contact them and get guidance from them to achieve your goals.</p>

        <div class="faculty-container">
            <?php if (empty($faculty_data)): ?>
                <p>No faculty records available.</p>
            <?php else: ?>
                <?php foreach ($faculty_data as $faculty): ?>
                    <div class="faculty-card">
                        <img src="<?php echo $faculty['image']; ?>" alt="<?php echo $faculty['faculty_name']; ?>">
                        <div class="info">
                            <h3><?php echo $faculty['faculty_name']; ?></h3>
                            <p><?php echo $faculty['qualification']; ?></p>
                            <p><strong>Designation:</strong> <?php echo $faculty['designation']; ?></p>
                            <p><strong>Work Location:</strong> <?php echo $faculty['work_location']; ?></p>
                            <p><strong>Personal Number:</strong> <?php echo $faculty['personal_number']; ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </main>

    <!-- Footer -->
    <footer>
        <p>&copy; 2024 Your College Name. All Rights Reserved. <a href="#">Privacy Policy</a></p>
    </footer>
</body>
</html>
