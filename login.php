<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url("img/bg.jpg");
            background-size: cover;
            background-position: center;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .login-container {
            background-color: #da163d;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            text-align: center;
            opacity: 0.9;
        }

        h1 {
            margin-bottom: 20px;
            color: white;
        }

        input[type="text"],
        input[type="password"],
        select {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        button {
            background-color: black;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
        }

        button:hover {
            background-color: #555;
        }

        .signup-link, .forgot-password {
            margin-top: 10px;
            display: block;
            color: white;
        }

        .forgot-password:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="login-container" data-setbg="img/bg.jpg">
    <h1>Login</h1>
    <form action="backend/login.php" method="post">
        <input type="text" name="email" placeholder="Enter email" required>
        <input type="password" name="password" placeholder="Enter Password" required>
        
        
        <button type="submit" value="submit">Login</button>
    </form>
    <p class="forgot-password"><a href="forgot-password.html" style="color: white;">Forgot Password?</a></p>
    <p class="signup-link"><a href="signup.html" style="color: white;">Don't have an account? Sign up here</a></p>
</div>

</body>
</html>
