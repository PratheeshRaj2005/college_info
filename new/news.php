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

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $college_name = $conn->real_escape_string($_POST['college_name']);
    $course = $conn->real_escape_string($_POST['course']);
    $news_title = $conn->real_escape_string($_POST['news_title']);
    $about_news = $conn->real_escape_string($_POST['about_news']);
    $news_date = $conn->real_escape_string($_POST['news_date']);
    $news_time = $conn->real_escape_string($_POST['news_time']);

    $stmt = $conn->prepare("
        INSERT INTO news_updates (college_name, course, news_title, about_news, news_date, news_time) 
        VALUES (?, ?, ?, ?, ?, ?)
    ");
    $stmt->bind_param("ssssss", $college_name, $course, $news_title, $about_news, $news_date, $news_time);

    if ($stmt->execute()) {
        echo "<div class='alert alert-success'>News update added successfully!</div>";
    } else {
        echo "<div class='alert alert-danger'>Error: " . $stmt->error . "</div>";
    }
    $stmt->close();
}

// Fetch news updates
$news_updates = [];
$college_id = isset($_GET['college_id']) ? intval($_GET['college_id']) : null;

if ($college_id) {
    $stmt = $conn->prepare("
        SELECT 
            news_updates.news_title, 
            news_updates.about_news, 
            news_updates.news_date, 
            news_updates.news_time, 
            colleges.college_name
        FROM 
            news_updates
        LEFT JOIN 
            colleges 
        ON 
            news_updates.college_name = colleges.college_name
        WHERE 
            colleges.id = ?
    ");
    $stmt->bind_param("i", $college_id);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $conn->query("
        SELECT 
            news_title, 
            about_news, 
            news_date, 
            news_time 
        FROM 
            news_updates 
        ORDER BY news_date DESC, news_time DESC
    ");
}

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $news_updates[] = $row;
    }
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>News Page</title>
    <link rel="icon" type="image/x-icon" href="../img/college.png">

      <!-- Google Fonts -->
      <link href="https://fonts.googleapis.com/css?family=Raleway:400,400i,500,500i,600,600i,700,700i,800,800i" rel="stylesheet">
    <!-- Stylesheets -->
    <link rel="stylesheet" href="../css/bootstrap.min.css"/>
    <link rel="stylesheet" href="../css/font-awesome.min.css"/>
    <link rel="stylesheet" href="../css/style.css"/>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            /* padding: 20px; */
        }
        .container {
            max-width: 1200px;
            margin: auto;
        }
        .news-container {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .news-item {
            border: 1px solid #ddd;
            border-radius: 6px;
            padding: 20px;
            margin-bottom: 20px;
            background-color: #fdfdfd;
        }
        .news-title {
            font-size: 1.5em;
            font-weight: bold;
        }
        .news-meta {
            font-size: 0.9em;
            color: #666;
        }
        .news-content {
            margin-top: 10px;
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
   <div class="py-5">
   <div class="container">
        <h1 class="text-center">News Management</h1>

        <div class="news-container">
            <h2>Latest News Updates</h2>
            <?php if (!empty($news_updates)): ?>
                <?php foreach ($news_updates as $news): ?>
                    <div class="news-item">
                        <div class="news-title"><?= htmlspecialchars($news['news_title']); ?></div>
                        <p class="news-meta">
                            Date: <?= htmlspecialchars($news['news_date']); ?> | Time: <?= htmlspecialchars($news['news_time']); ?>
                        </p>
                        <div class="news-content">
                            <?= nl2br(htmlspecialchars($news['about_news'])); ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No news updates available.</p>
            <?php endif; ?>
        </div>
    </div>
   </div>
   <?php include('footer.php')?>
</body>
</html>
