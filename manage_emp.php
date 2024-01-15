<?php
session_start();
include "components/db.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
<?php include "addprodStyle.php"; ?>
</head>
<body>
<?php include "addprodHeader.php"; ?>
<div class="content">
        <h2>Employee Data</h2>
        <table border="1">
            <tr>
                <th>Employee Name</th>
                <th>Employee User</th>
                <th>Employee Status</th>
                <th>Employee Type</th>
                <th>Action</th>
            </tr>

            <?php

            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            $sql = "SELECT emp_id, emp_name, emp_user, emp_status, emp_type FROM employee";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['emp_name'] . "</td>";
                    echo "<td>" . $row['emp_user'] . "</td>";
                    echo "<td>" . $row['emp_status'] . "</td>";
                    echo "<td>" . $row['emp_type'] . "</td>";
                    echo "<td>
                    <form action='update_emp.php' method='post'>
                    <input type='hidden' name='emp_id' value='{$row['emp_id']}'>
                    <button class='update' type='submit'>Update</button>
                    </form>
                    <form action='delete_emp.php' method='post' class='delete-form'>
                    <input type='hidden' name='emp_id' value='{$row['emp_id']}'>
                    <input type='hidden' name='page' value='manage_employee'>
                    <button class='delete' type='submit'>Delete</button>
                    </form>
                          </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='4'>No data found</td></tr>";
            }

            // Close connection
            $conn->close();
            ?>
        </table>
    </div>
</body>
</html>