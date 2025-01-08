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

// Fetch all colleges for the dropdown
$colleges_query = "SELECT id, name FROM colleges";
$colleges_result = $conn->query($colleges_query);

// Handle review submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $college_id = $_POST['college_id'];
    $reviewer_name = $_POST['reviewer_name'];
    $review = $_POST['review'];
    $rating = $_POST['rating'];

    $insert_review_query = "INSERT INTO reviews (college_id, reviewer_name, review, rating, created_at) 
                            VALUES ('$college_id', '$reviewer_name', '$review', '$rating', NOW())";
    if ($conn->query($insert_review_query) === TRUE) {
        $success_message = "Review submitted successfully!";
    } else {
        $error_message = "Error submitting review: " . $conn->error;
    }
}

// Fetch all reviews for display
$reviews_query = "SELECT reviews.*, colleges.name AS college_name 
                  FROM reviews 
                  JOIN colleges ON reviews.college_id = colleges.id 
                  ORDER BY reviews.created_at DESC";
$reviews_result = $conn->query($reviews_query);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>College Reviews</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
        }
        .container {
            max-width: 800px;
            margin: 50px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h1, h2 {
            text-align: center;
        }
        label {
            font-weight: bold;
        }
        textarea {
            resize: none;
        }
        .review-card {
            background-color: #f1f1f1;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 15px;
        }
        .review-header {
            font-weight: bold;
            color: #007bff;
        }
        .rating {
            color: #ffc107;
        }
        .review-text {
            margin-top: 10px;
        }
        .review-date {
            margin-top: 5px;
            font-size: 0.9rem;
            color: #6c757d;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>College Reviews</h1>

        <!-- Success or Error Message -->
        <?php if (isset($success_message)): ?>
            <div class="alert alert-success"><?= $success_message; ?></div>
        <?php elseif (isset($error_message)): ?>
            <div class="alert alert-danger"><?= $error_message; ?></div>
        <?php endif; ?>

        <!-- Review Form -->
        <form action="" method="POST">
            <label for="college_name">Select College</label>
            <select name="college_id" id="college_name" class="form-control mb-3" required>
                <option value="">Select a College</option>
                <?php if ($colleges_result->num_rows > 0): ?>
                    <?php while ($row = $colleges_result->fetch_assoc()): ?>
                        <option value="<?= $row['id']; ?>"><?= $row['name']; ?></option>
                    <?php endwhile; ?>
                <?php else: ?>
                    <option value="">No colleges available</option>
                <?php endif; ?>
            </select>

            <label for="reviewer_name">Your Name</label>
            <input type="text" name="reviewer_name" id="reviewer_name" class="form-control mb-3" placeholder="Enter your name" required>

            <label for="review">Your Review</label>
            <textarea name="review" id="review" rows="5" class="form-control mb-3" placeholder="Write your review here" required></textarea>

            <label for="rating">Rating</label>
            <select name="rating" id="rating" class="form-control mb-3" required>
                <option value="1">1 - Poor</option>
                <option value="2">2 - Fair</option>
                <option value="3">3 - Good</option>
                <option value="4">4 - Very Good</option>
                <option value="5">5 - Excellent</option>
            </select>

            <button type="submit" class="btn btn-primary btn-block">Submit Review</button>
        </form>

        <!-- Display Reviews -->
        <h2 class="mt-5">Reviews</h2>
        <?php if ($reviews_result->num_rows > 0): ?>
            <?php while ($row = $reviews_result->fetch_assoc()): ?>
                <div class="review-card">
                    <div class="review-header"><?= $row['reviewer_name']; ?> - <span class="rating"><?= str_repeat('★', $row['rating']); ?><?= str_repeat('☆', 5 - $row['rating']); ?></span></div>
                    <div class="review-text"><?= $row['review']; ?></div>
                    <div class="review-date"><?= date("F j, Y, g:i A", strtotime($row['created_at'])); ?></div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No reviews available.</p>
        <?php endif; ?>
    </div>
</body>
</html>
