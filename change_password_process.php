<?php
if (isset($_GET['verify_token'])) {
    $code = $_GET['verify_token'];

    $conn = new mysqli('localhost', 'root', '', 'mimi pet store');
    if ($conn->connect_error) {
        die('Could not connect to the database');
    }

    $verifyQuery = $conn->query("SELECT * FROM employee WHERE verify_token = '$code'");

    if ($verifyQuery->num_rows == 0) {
        header("Location: change_password.php");
        exit();
    }

    if (isset($_POST['change'])) {
        $email = $_POST['email'];
        $new_password = $_POST['new_password'];

        $changeQuery = $conn->query("UPDATE employee SET emp_pass = '$new_password' WHERE emp_email = '$email' AND verify_token = '$code'");

        if ($changeQuery) {
            header("Location: success_reset_page.php");
            exit();
        } else {
            echo 'Password change failed';
        }
    }

    $conn->close();
} else {
    header("Location: reset_pass.php");
    exit();
}
?>