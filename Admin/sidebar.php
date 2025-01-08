<head>
    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
     /* Sidebar Styles */
     .sidebar {
            width: 250px;
            background-color: #007bff;
            color: white;
            padding-top: 20px;
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            overflow-y: auto;
            box-shadow: 4px 0 8px rgba(0, 0, 0, 0.1);
        }

        .sidebar a {
            display: flex;
            gap: 20px;
            padding: 13px;
            color: white;
            text-decoration: none;
            font-size: 1.1rem;
            transition: background-color 0.3s;
            text-align: justify;
        }

        .sidebar a:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<div class="sidebar">
    <a href="faculty.php" class="btn"><i class="fas fa-chalkboard-teacher"></i> Add Faculty</a>
    <a href="courses.php" class="btn"><i class="fas fa-book-open"></i> Add Courses</a>
    <a href="states.php" class="btn"><i class="fas fa-map-marker-alt"></i> Add States</a>
    <a href="add_college.php" class="btn"><i class="fas fa-university"></i> Add College</a>
    <a href="about_college.php" class="btn"><i class="fas fa-info-circle"></i> Add About College</a>
    <a href="cutoff.php" class="btn"><i class="fas fa-percent"></i> Add Cutoff</a>
    <a href="rankings.php" class="btn"><i class="fas fa-trophy"></i> Add Rankings</a>
    <a href="admission.php" class="btn"><i class="fas fa-calendar-check"></i> Add Admission</a>
    <a href="placement.php" class="btn"><i class="fas fa-briefcase"></i> Add Placement</a>
    <a href="scholarship.php" class="btn"><i class="fas fa-money-bill-alt"></i> Add Scholarship</a>
    <a href="fees.php" class="btn"><i class="fas fa-dollar-sign"></i> Add Fees</a>
    <a href="infrastructure.php" class="btn"><i class="fas fa-building"></i> Add Infrastructure</a>
    <a href="news.php" class="btn"><i class="fas fa-newspaper"></i> Add News</a>
</div>
