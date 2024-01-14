<?php 
session_start();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            background-color: #ffb6c1;
        }

        .login-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
            text-align: left;
        }

        .login-container img {
            width: 100px; 
            margin-bottom: 10px;
        }

        .login-container h2 {
            color: #333;
        }

        .login-form {
            margin-top: 20px;
        }
        h2{
            text-align: center;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: #333;
        }

        .form-group input {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .form-group input[type="submit"] {
            background-color: #007bff;
            color: #fff;
            cursor: pointer;
        }

        .form-group input[type="submit"]:hover {
            background-color: #0056b3;
        }
        .login-container .image-container {
    text-align: center;
}

.login-container img {
    width: 150px;
    margin-bottom: 10px;
    display: inline-block;
}

    </style>
</head>
<body>

<div class="login-container">
<div class="image-container">
        <img src="pics/mimis_logo.jpg" alt="Logo">
    </div>
    <h2>Login</h2>
    <form class="login-form" method="post">
        <div class="form-group">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
        </div>
        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
        </div>
        <div class="form-group">
            <input type="submit" value="Login">
        </div>
        <div class="form-link">
                <label for="reg"> Dont have account? <a href ="create_user.php">Click Here</a> 
            </div>
    </form>
</div>

</body>
</html>
