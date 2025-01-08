<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "reglog";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Predefined list of Indian states
$indianStates = [
    "Andhra Pradesh", "Arunachal Pradesh", "Assam", "Bihar", "Chhattisgarh",
    "Goa", "Gujarat", "Haryana", "Himachal Pradesh", "Jharkhand", "Karnataka",
    "Kerala", "Madhya Pradesh", "Maharashtra", "Manipur", "Meghalaya", 
    "Mizoram", "Nagaland", "Odisha", "Punjab", "Rajasthan", "Sikkim",
    "Tamil Nadu", "Telangana", "Tripura", "Uttar Pradesh", "Uttarakhand",
    "West Bengal", "Andaman and Nicobar Islands", "Chandigarh", "Dadra and Nagar Haveli and Daman and Diu", 
    "Lakshadweep", "Delhi", "Puducherry", "Ladakh", "Jammu and Kashmir"
];

// Fetch states from the database
$statesInDb = [];
$sql_states = "SELECT state_name FROM states";
$result_states = $conn->query($sql_states);

if ($result_states && $result_states->num_rows > 0) {
    while ($row = $result_states->fetch_assoc()) {
        $statesInDb[] = $row['state_name'];
    }
}

// Handle Add state
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $state_name = $_POST['state_name'];
    if (!in_array($state_name, $statesInDb)) {
        $sql_add = "INSERT INTO states (state_name) VALUES (?)";
        $stmt = $conn->prepare($sql_add);
        $stmt->bind_param("s", $state_name);
        $stmt->execute();
        $stmt->close();
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } else {
        $error = "State already exists in the database.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Indian States</title>
    <link rel="icon" type="image/x-icon" href="../img/college.png">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 50%;
            margin: 50px auto;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        h1 {
            text-align: center;
        }
        label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
        }
        select, button {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            background-color: #4CAF50;
            color: white;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
        .state-list {
            margin-top: 30px;
            background-color: #f9f9f9;
            padding: 15px;
            border-radius: 8px;
        }
        .state-item {
            padding: 10px;
            background-color: #e9e9e9;
            margin-bottom: 5px;
            border-radius: 4px;
        }
        .error {
            color: red;
            text-align: center;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Add Indian State</h1>
    <?php if (!empty($error)): ?>
        <p class="error"><?= htmlspecialchars($error); ?></p>
    <?php endif; ?>
    <form action="<?= $_SERVER['PHP_SELF'] ?>" method="POST">
        <label for="state_name">Select a State</label>
        <select name="state_name" id="state_name" required>
            <option value="">Choose a state</option>
            <?php foreach ($indianStates as $state): ?>
                <option value="<?= htmlspecialchars($state); ?>" <?= in_array($state, $statesInDb) ? 'disabled' : ''; ?>>
                    <?= htmlspecialchars($state); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <button type="submit">Add State</button>
    </form>

    <div class="state-list">
        <h2>Available States</h2>
        <?php if (!empty($statesInDb)): ?>
            <?php foreach ($statesInDb as $state): ?>
                <div class="state-item"><?= htmlspecialchars($state); ?></div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No states available.</p>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
