<?php
session_start();
include "components/db.php";

if (!isset($_SESSION['emp_id'])) {
    header("Location: home.php");
    exit();
}

$emp_id = $_SESSION['emp_id'];

$sql = "SELECT * FROM employee WHERE emp_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $emp_id);
$stmt->execute();
$result = $stmt->get_result();
$emp = $result->fetch_assoc();

if (!$emp) {
    echo "User not found";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $emp_name = filter_input(INPUT_POST, 'emp_name', FILTER_SANITIZE_STRING);
    $emp_user = filter_input(INPUT_POST, 'emp_user', FILTER_SANITIZE_STRING);
    $emp_phone = filter_input(INPUT_POST, 'emp_phone', FILTER_SANITIZE_STRING);
    $emp_email = filter_input(INPUT_POST, 'emp_email', FILTER_VALIDATE_EMAIL);

    $update_sql = "UPDATE employee SET emp_name = ?, emp_user = ?, emp_phone = ?, emp_email = ? WHERE emp_id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param('ssssi', $emp_name, $emp_user, $emp_phone, $emp_email, $emp_id);

    if ($update_stmt->execute()) {
        header("Location: home.php"); 
        exit();
    } else {
        echo "Update failed";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <style>
    body {
        font-family: Arial, sans-serif;
        background-color: #ffb6c1;
        margin: 0;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
    }

    .edit-profile-container {
        background-color: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    label {
        display: block;
        margin-bottom: 8px;
    }

    input {
        width: 100%;
        padding: 8px;
        margin-bottom: 16px;
        box-sizing: border-box;
    }

    input[type="submit"] {
        background-color: #4caf50;
        color: #fff;
        border: none;
        padding: 10px;
        border-radius: 4px;
        cursor: pointer;
    }

    input[type="submit"]:hover {
        background-color: #45a049;
    }
    </style>
</head>
<body>
    <div class="edit-profile-container">
        <h1>Edit Profile</h1>
        <form action="" method="post">
            <label for="emp_name">Employee Name:</label>
            <input type="text" id="emp_name" name="emp_name" value="<?php echo htmlspecialchars($emp['emp_name']); ?>" required>

            <label for="emp_user">Employee Username:</label>
            <input type="text" id="emp_user" name="emp_user" value="<?php echo htmlspecialchars($emp['emp_user']); ?>" required>

            <label for="emp_phone">Employee Phone:</label>
            <input type="text" id="emp_phone" name="emp_phone" value="<?php echo htmlspecialchars($emp['emp_phone']); ?>" required>

            <label for="emp_email">Employee Email:</label>
            <input type="email" id="emp_email" name="emp_email" value="<?php echo htmlspecialchars($emp['emp_email']); ?>" required>

            <input type="submit" value="Save Changes">
        </form>
    </div>
</body>
</html>
