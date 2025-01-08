<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Find the right college and plan your future with confidence. Our guidance platform offers tools, insights, and resources to help you make informed decisions.">
    <meta name="keywords" content="college, education, guidance, career, future, decisions">
    <meta name="author" content="College Guidance Platform">
    <title>Choose Your College Wisely</title>
    
    <!-- Favicon -->
    <link rel="icon" href="img/favicon.png" type="image/png">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Custom CSS -->
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            line-height: 1.6;
            background-color: #f9f9f9;
        }

        header {
            background: linear-gradient(to right, #4e54c8, #8f94fb);
            color: white;
            padding: 30px 0;
        }

        header h1 {
            font-size: 2.5rem;
            font-weight: 700;
        }

        header p {
            font-size: 1.2rem;
        }

        .section-title {
            margin-bottom: 40px;
            text-align: center;
        }

        .section-title h2 {
            font-weight: 600;
            color: #333;
        }

        .card {
            border: none;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .faq-section {
            background-color: #eef1f7;
            padding: 60px 20px;
        }

        .faq-item {
            border-bottom: 1px solid #ddd;
            padding: 15px 0;
            cursor: pointer;
        }

        .faq-item.active .faq-answer {
            max-height: 200px;
            opacity: 1;
            margin-top: 10px;
        }

        .faq-answer {
            max-height: 0;
            overflow: hidden;
            opacity: 0;
            transition: max-height 0.4s ease, opacity 0.4s ease;
        }

        footer {
            background: #4e54c8;
            color: white;
            padding: 20px 0;
        }

        footer p {
            margin: 0;
        }

        /* Gap between Home and other items in navbar */
        .navbar-nav .nav-item {
            margin-right: 15%; /* Apply a 15% gap between each item */
        }

        img {
            max-width: 40%;
        }
    </style>
</head>

<body>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href=""><img src="img/college logo.png" alt=""></a>
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

<!-- Header Section -->
<header class="text-center">
    <div class="container">
        <h1>Choose Your College Wisely</h1>
        <p>Empowering you to make informed decisions for a brighter future.</p>
    </div>
</header>

<!-- Decision Guide Section -->
<section class="container my-5">
    <div class="section-title">
        <h2>Steps to Choose the Right College</h2>
        <p>Follow these steps to ensure you select the best college for your goals.</p>
    </div>

    <div class="row g-4">
        <div class="col-md-4">
            <div class="card p-4 text-center">
                <i class="fas fa-search fa-3x mb-3 text-primary"></i>
                <h5>Research</h5>
                <p>Explore colleges, courses, and reviews to understand your options thoroughly.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card p-4 text-center">
                <i class="fas fa-balance-scale fa-3x mb-3 text-primary"></i>
                <h5>Compare</h5>
                <p>Weigh factors like location, fees, facilities, and alumni success rates.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card p-4 text-center">
                <i class="fas fa-lightbulb fa-3x mb-3 text-primary"></i>
                <h5>Decide</h5>
                <p>Make an informed choice based on your priorities and long-term goals.</p>
            </div>
        </div>
    </div>
</section>

<!-- Testimonials Section -->
<section class="bg-light py-5">
    <div class="container">
        <div class="section-title">
            <h2>What Our Users Say</h2>
        </div>

        <div class="row">
            <div class="col-md-6">
                <blockquote class="blockquote">
                    <p>"This platform guided me through every step of selecting the right college. Highly recommend it!"</p>
                    <footer class="blockquote-footer">John Doe, Engineering Student</footer>
                </blockquote>
            </div>

            <div class="col-md-6">
                <blockquote class="blockquote">
                    <p>"I found my dream college with ease. The resources and insights were invaluable."</p>
                    <footer class="blockquote-footer">Jane Smith, Business Student</footer>
                </blockquote>
            </div>
        </div>
    </div>
</section>

<!-- FAQ Section -->
<section class="faq-section">
    <div class="container">
        <div class="section-title">
            <h2>Frequently Asked Questions</h2>
        </div>

        <div class="faq-item">
            <h5>How do I start researching colleges?</h5>
            <div class="faq-answer">
                <p>Start by identifying your interests and career goals. Use our platform to explore college reviews, courses, and facilities.</p>
            </div>
        </div>

        <div class="faq-item">
            <h5>What factors should I consider when comparing colleges?</h5>
            <div class="faq-answer">
                <p>Key factors include location, fees, faculty, campus facilities, placements, and student reviews.</p>
            </div>
        </div>
    </div>
</section>

<!-- Footer Section -->
<footer class="text-center">
    <div class="container">
        <p>&copy; 2025 College Guidance Platform. All rights reserved.</p>
    </div>
</footer>

<!-- JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // FAQ Toggle Functionality
    document.querySelectorAll('.faq-item').forEach(item => {
        item.addEventListener('click', () => {
            item.classList.toggle('active');
        });
    });
</script>
</body>

</html>
