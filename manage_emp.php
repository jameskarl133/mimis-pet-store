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
        <div class="search-container">
            <form action="" method="get">
                <label for="search">Search Employee:</label>
                <input type="text" id="search" name="query" placeholder="Enter employee name or ID">
                <button type="submit" name="search_button">Search</button>
                <button type="submit" name="show_all_button">Show All</button>
            </form>
        </div>
        </div>
        <div class ="content">
            <h2>Employee Data</h2>
        </form>
    </div>
        <table border="1">
            <tr>
                <th>Employee ID</th>
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

            // Check if the form is submitted and a search query is provided
            if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['search_button'])) {
                $search_query = isset($_GET['query']) ? $_GET['query'] : '';

                // Check if the search query is empty
                if (empty($search_query)) {
                    echo "<tr><td colspan='6'>Please enter name or ID</td></tr>";
                } else {
                    $sql = "SELECT emp_id, emp_name, emp_user, emp_status, emp_type FROM employee";

                    // Modify the SQL query if a search query is provided
                    $sql .= " WHERE emp_name LIKE '%$search_query%' OR emp_id LIKE '%$search_query%'";

                    $result = $conn->query($sql);

                    if ($result) {
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . $row['emp_id'] . "</td>";
                                echo "<td>" . $row['emp_name'] . "</td>";
                                echo "<td>" . $row['emp_user'] . "</td>";
                                echo "<td>" . $row['emp_status'] . "</td>";
                                echo "<td>" . $row['emp_type'] . "</td>";
                                echo "<td>
                                    <form action='update_user.php' method='post'>
                                    <input type='hidden' name='emp_id' value='{$row['emp_id']}'>
                                    <button class='update' type='submit'>Update</button>
                                    </form>
                                    <form action='delete_user.php' method='post' class='delete-form'>
                                    <input type='hidden' name='emp_id' value='{$row['emp_id']}'>
                                    <input type='hidden' name='page' value='manage_employee'>
                                    <button class='delete' type='submit'>Delete</button>
                                    </form>
                                </td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='6'>No matching records found</td></tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6'>Error executing query: " . $conn->error . "</td></tr>";
                    }
                }
            } 
            elseif ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['show_all_button'])) {
                $sql = "SELECT emp_id, emp_name, emp_user, emp_status, emp_type FROM employee";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['emp_id'] . "</td>";
                        echo "<td>" . $row['emp_name'] . "</td>";
                        echo "<td>" . $row['emp_user'] . "</td>";
                        echo "<td>" . $row['emp_status'] . "</td>";
                        echo "<td>" . $row['emp_type'] . "</td>";
                        echo "<td>
                            <form action='update_user.php' method='post'>
                            <input type='hidden' name='emp_id' value='{$row['emp_id']}'>
                            <button class='update' type='submit'>Update</button>
                            </form>
                            <form action='delete_user.php' method='post' class='delete-form'>
                            <input type='hidden' name='emp_id' value='{$row['emp_id']}'>
                            <input type='hidden' name='page' value='manage_employee'>
                            <button class='delete' type='submit'>Delete</button>
                            </form>
                        </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>No data found</td></tr>";
                }
            }else {
                $sql = "SELECT emp_id, emp_name, emp_user, emp_status, emp_type FROM employee";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['emp_id'] . "</td>";
                        echo "<td>" . $row['emp_name'] . "</td>";
                        echo "<td>" . $row['emp_user'] . "</td>";
                        echo "<td>" . $row['emp_status'] . "</td>";
                        echo "<td>" . $row['emp_type'] . "</td>";
                        echo "<td>
                            <form action='update_user.php' method='post'>
                            <input type='hidden' name='emp_id' value='{$row['emp_id']}'>
                            <button class='update' type='submit'>Update</button>
                            </form>
                            <form action='delete_user.php' method='post' class='delete-form'>
                            <input type='hidden' name='emp_id' value='{$row['emp_id']}'>
                            <input type='hidden' name='page' value='manage_employee'>
                            <button class='delete' type='submit'>Delete</button>
                            </form>
                        </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>No data found</td></tr>";
                }
            }

            $conn->close();
            ?>
        </table>
    </div>
</body>
</html>