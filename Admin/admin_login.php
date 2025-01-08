<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login & Registration</title>
    <link rel="icon" type="image/x-icon" href="../img/college.png">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f9f9f9;
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            max-width: 400px;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        form {
            margin-bottom: 15px;
        }

        label {
            font-weight: bold;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"],
        button {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button {
            background-color: #007BFF;
            color: white;
            border: none;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }

        .toggle-link {
            text-align: center;
            margin-top: -10px;
        }

        .toggle-link a {
            color: #007BFF;
            text-decoration: none;
        }

        .toggle-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Login Form -->
        <div id="login-form">
            <h1>Admin Login</h1>
            <form action="backend/admin_login.php" method="POST">
                <label for="login-email">Email</label>
                <input type="email" name="email" id="login-email" placeholder="Enter your email" required>

                <label for="login-password">Password</label>
                <input type="password" name="password" id="login-password" placeholder="Enter your password" required>

                <button type="submit" name="login">Login</button>
            </form>
            <div class="toggle-link">
                <p>Don't have an account? <a href="#" onclick="toggleForms()">Register here</a></p>
            </div>
        </div>

        <!-- Registration Form -->
        <div id="register-form" style="display: none;">
            <h1>Admin Registration</h1>
            <form action="backend/admin_register.php" method="POST">
                <label for="reg-name">Name</label>
                <input type="text" name="name" id="reg-name" placeholder="Enter your name" required>

                <label for="reg-email">Email</label>
                <input type="email" name="email" id="reg-email" placeholder="Enter your email" required>

                <label for="reg-password">Password</label>
                <input type="password" name="password" id="reg-password" placeholder="Enter your password" required>

                <button type="submit" name="register">Register</button>
            </form>
            <div class="toggle-link">
                <p>Already have an account? <a href="#" onclick="toggleForms()">Login here</a></p>
            </div>
        </div>
    </div>

    <script>
        function toggleForms() {
            const loginForm = document.getElementById('login-form');
            const registerForm = document.getElementById('register-form');
            loginForm.style.display = loginForm.style.display === 'none' ? 'block' : 'none';
            registerForm.style.display = registerForm.style.display === 'none' ? 'block' : 'none';
        }
    </script>
</body>
</html>
