<?php
session_start();
include("components/db.php")
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Employee</title>
    <style>
        body {
            background-color: #ffd1dc;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        h2 {
            color: #333;
            text-align: center;
        }

        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            width: 300px;
            margin: 0 auto;
            margin-top: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #555;
        }

        input, select {
            width: 100%;
            padding: 8px;
            margin-bottom: 12px;
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

        button:hover {
            background-color: #45a049;
        }
        .image-container {
            text-align: center;
        }

        .image-container img {
            width: 100px;
            margin-bottom: 10px;
            display: inline-block;
        }
    </style>
</head>
<body>
    

    <form action="update_user.php" method="post">
    <div class="image-container">
        <img src="pics/mimis_logo.jpg" alt="Logo">
    </div>
        <h2>Update Employee</h2>
        <input type="hidden" name="emp_id" value="<?php echo $emp_id; ?>">

        <label for="emp_name">Employee Name:</label>
        <input type="text" name="emp_name" value="<?php echo $emp_name; ?>" required>

        <label for="emp_user">Employee User:</label>
        <input type="text" name="emp_user" value="<?php echo $emp_user; ?>" required>

        <label for="emp_status">Employee Status:</label>
        <input type="text" name="emp_status" value="<?php echo $emp_status; ?>" required>

        <label for="emp_type">Employee Type:</label>
        <select name="emp_type" required>
            <option value="Admin" <?php echo ($emp_type == 'Admin') ? 'selected' : ''; ?>>Admin</option>
            <option value="Employee" <?php echo ($emp_type == 'Employee') ? 'selected' : ''; ?>>Employee</option>
        </select>

        <button type="submit">Update</button>
        <a href="manage_emp.php"><button type="button" class="cancel">Cancel</button></a>
    </form>
</body>
</html>

