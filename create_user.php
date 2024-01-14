<?php
session_start();
include "components/db.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #ffb6c1;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
        }

        label {
            display: block;
            margin-bottom: 8px;
        }

        input, select {
            width: 100%;
            padding: 8px;
            margin-bottom: 16px;
            box-sizing: border-box;
        }

        button {
            background-color: #4caf50;
            color: #fff;
            padding: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
    </style>
    <title>Create Account</title>
</head>
<body>
    <form action="" method="post">
        <h2> Create Account </h2>
        <label for="fullname">Full Name:</label>
        <input type="fullname" id="fullname" name="fullname" required>

        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>

        <label for="employee_type">Type:</label>
        <select id="employee_type" name="employee_type">
            <option value="employee">Employee</option>
            <option value="admin">Admin</option>
        </select>

        <button type="submit">Create Account</button>
        <div class="form-link">
                <label for="reg"><a href ="login_page.php">Go back</a>
            </div>
    </form>
</body>
</html>
