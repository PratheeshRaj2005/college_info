<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "reglog");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch colleges for the selected state
$colleges = [];
$state_name = ''; // Default to empty in case 'state' is not provided
if (isset($_GET['state'])) {
    $state_name = $_GET['state'];

    // Prepare SQL query to prevent SQL injection
    $stmt = $conn->prepare("SELECT id, college_name, college_image, about_college FROM colleges WHERE state = ?");
    $stmt->bind_param("s", $state_name); // Bind parameter
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $colleges[] = $row; // Store college details in array
    }
    $stmt->close();
} else {
    echo "State parameter is missing!";
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Colleges in <?php echo htmlspecialchars($state_name); ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 80%;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        .college-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .college-item {
            background-color: #fff;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .college-item:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
        }

        .college-item img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
        }

        .college-name {
            font-size: 1.5em;
            font-weight: bold;
            margin-top: 10px;
        }

        .college-description {
            font-size: 1em;
            margin-top: 10px;
            color: #555;
        }
    </style>
</head>
<body>
    
    <div class="container">
        <h1>Colleges in <?php echo htmlspecialchars($state_name); ?></h1>
        
        <div class="college-list">
            <?php
            // Loop through the colleges array to display college details dynamically
            if (!empty($colleges)) {
                foreach ($colleges as $college) {
                    // Create a URL to open the specific college details
                    $college_url = "about_college.php?id=" . $college['id'];
                    echo '<div class="college-item">';
                    echo '<a href="' . htmlspecialchars($college_url) . '">'; // Properly escape the URL
                    echo '<img src="' . htmlspecialchars($college['college_image']) . '" alt="' . htmlspecialchars($college['college_name']) . '">';
                    echo '<div class="college-name">' . htmlspecialchars($college['college_name']) . '</div>';
                    echo '<div class="college-description">' . htmlspecialchars($college['about_college']) . '</div>';
                    echo '</a>';
                    echo '</div>';
                }
            } else {
                echo '<p>No colleges available for the selected state.</p>';
            }
            ?>
        </div>
    </div>

</body>
</html>
