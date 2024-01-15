<?php
session_start();
include "components/db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['emp_id'])) {
        $emp_id = $_POST['emp_id'];

        if (isset($_SESSION['emp_id']) && $_SESSION['emp_id'] == $emp_id) {
            echo "You cannot delete your own account.";
            exit();
        }

        $sql = "DELETE FROM employee WHERE emp_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $emp_id);

        if ($stmt->execute()) {
            header("Location: manage_emp.php");
            exit();
        } else {
            echo "Error deleting record: " . $conn->error;
        }

        $stmt->close();
    } else {
        echo "Invalid request";
    }
} else {
    echo "Invalid request method";
}

$conn->close();
?>
