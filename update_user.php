
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
    

<?php
session_start();
include("components/db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $emp_id = mysqli_real_escape_string($conn, $_POST['emp_id']);

    $query = "SELECT * FROM employee WHERE emp_id = '$emp_id'";
    $result = mysqli_query($conn, $query);

    if ($result) {
        if (mysqli_num_rows($result) == 1) {
            $row = mysqli_fetch_assoc($result);

            echo "<form action='update_user.php' method='post'>
                    <div class='image-container'>
                        <img src='pics/mimis_logo.jpg' alt='Logo'>
                    </div>
                    <h2>Update Employee</h2>
                    <input type='hidden' name='emp_id' value='{$row['emp_id']}'>
                    
                    <label for='emp_name'>Employee Name:</label>
                    <input type='text' name='emp_name' value='{$row['emp_name']}' required>

                    <label for='emp_user'>Employee User:</label>
                    <input type='text' name='emp_user' value='{$row['emp_user']}' required>

                    <label for='emp_status'>Employee Status:</label>
                    <input type='text' name='emp_status' value='{$row['emp_status']}' required>

                    <label for='emp_type'>Employee Type:</label>
                    <select name='emp_type' required>
                        <option value='Admin' " . ($row['emp_type'] == 'Admin' ? 'selected' : '') . ">Admin</option>
                        <option value='Employee' " . ($row['emp_type'] == 'Employee' ? 'selected' : '') . ">Employee</option>
                    </select>

                    <button type='submit' name='update_employee'>Update</button>
                    <a href='manage_emp.php'><button type='button' class='cancel'>Cancel</button></a>
                </form>";
        } else {
            echo "Employee not found.";
        }
    } else {
        echo "Error fetching employee data.";
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_employee'])) {
    $emp_id = mysqli_real_escape_string($conn, $_POST['emp_id']);
    $emp_name = mysqli_real_escape_string($conn, $_POST['emp_name']);
    $emp_user = mysqli_real_escape_string($conn, $_POST['emp_user']);
    $emp_status = mysqli_real_escape_string($conn, $_POST['emp_status']);
    $emp_type = mysqli_real_escape_string($conn, $_POST['emp_type']);

    $update_query = "UPDATE employee SET 
                    emp_name = '$emp_name',
                    emp_user = '$emp_user',
                    emp_status = '$emp_status',
                    emp_type = '$emp_type'
                    WHERE emp_id = '$emp_id'";

    $update_result = mysqli_query($conn, $update_query);

    if ($update_result) {
        header("Location: manage_emp.php");
        exit();
    } else {
        echo "Error updating employee details.";
    }
}
?>

</body>
</html>

