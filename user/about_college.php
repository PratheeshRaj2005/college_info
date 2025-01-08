<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "reglog");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the 'id' parameter is provided in the URL
if (isset($_GET['id'])) {
    $college_id = $_GET['id'];

    // Prepare SQL query to prevent SQL injection
    $stmt = $conn->prepare("SELECT * FROM about_college WHERE id = ?");
    $stmt->bind_param("i", $college_id); // Bind parameter as integer
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the college exists
    if ($row = $result->fetch_assoc()) {
        $college_name = $row['college_name'];
        $college_image = $row['logo'];
        $about_college = $row['about_college']; 
        $contact = $row['contact'];
        $location= $row['location'];
    } else {
        echo "College not found!";
        exit();
    }

    $stmt->close();
} else {
    echo "No college selected!";
    exit();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($college_name); ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
        }

        /* Sidebar Styles */
        .sidebar {
            width: 200px;
            background-color: #333;
            color: white;
            padding: 20px;
            position: fixed;
            top: 0;
            left: 0;
            height: 100%;
            overflow-y: auto;
        }

        .sidebar .btn {
            background-color: #444;
            color: white;
            padding: 10px;
            margin: 10px 0;
            width: 100%;
            border: none;
            text-align: left;
            cursor: pointer;
            border-radius: 5px;
        }

        .sidebar .btn:hover {
            background-color: #555;
        }

        /* Main content area */
        .main-content {
            margin-left: 220px;
            padding: 20px;
            width: calc(100% - 220px);
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        .college-details {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 20px;
        }

        .college-details img {
            max-width: 400px;
            height: auto;
            border-radius: 8px;
        }

        .college-description {
            font-size: 1.2em;
            color: #555;
            line-height: 1.6;
            max-width: 600px;
        }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <button class="btn" >Cutoff</button>
        <button class="btn" >Fees</button>
        <button class="btn" >Reviews</button>
        <button class="btn">Admissions</button>
        <button class="btn" >Placements</button>
        <button class="btn" >Rankings</button>
        <button class="btn" >Scholarships</button>
        <button class="btn">News</button>
        <button class="btn" >Location</button>
        <button class="btn">Ratings</button>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="container">
            <h1><?php echo htmlspecialchars($college_name); ?></h1>

            <div class="college-details">
                <img src="<?php echo htmlspecialchars($college_image); ?>" alt="<?php echo htmlspecialchars($college_name); ?>">
                
                <div class="college-description">
                    <p><?php echo nl2br(htmlspecialchars($about_college)); ?></p>
                    <p><?php echo nl2br(htmlspecialchars($contact)); ?></p>
                    <p><?php echo nl2br(htmlspecialchars($location)); ?></p>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Placeholder function for sidebar button click
        function loadContent(section) {
            alert("Load content for: " + section);
        }
    </script>

</body>
</html>
