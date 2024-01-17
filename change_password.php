<?php include 'change_password_process.php' ?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>
    <style>
        body {
            background-color: #ffd1dc; /* Pink pastel background color */
            margin: 0;
            padding: 0;
            font-family: 'Arial', sans-serif;
        }

        .change-container {
            background-color: #fff; /* White container background color */
            max-width: 400px;
            margin: 50px auto;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .h1-container {
            color: #ff1493; /* Deep Pink text color */
            text-align: center;
        }

        .change-container-content {
            margin-bottom: 20px;
        }

        label {
            color: #ff1493; /* Deep Pink label text color */
        }

        input {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            margin-bottom: 15px;
            box-sizing: border-box;
        }

        button {
            background-color: #ff1493; /* Deep Pink button color */
            color: #fff;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
        }
    </style>
</head>
<body>
    <div class="change-container">
        <h1 class="h1-container">
            Change Password
        </h1>
        <form action="" method="post">
            <div class="change-container-content">
                <label for="inputEmail" class="col-4 col-form-label">Email</label>
                <div class="col-lg-auto">
                    <input type="email" name="email" id="inputEmail" class="form-control" required>
                </div>
            </div>
            <div class="change-container-content">
                <label for="inputPassword">New Password</label>
                <div class="col-lg-auto">
                    <input type="password" name="new_password" id="inputPassword" required>
                </div>
            </div>
            <div class="change-container-content">
                <div>
                    <button type="submit" name="change">Change</button>
                </div>
            </div>
        </form>
    </div>
</body>
</html>