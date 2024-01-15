<?php

function check_login($con)
{
    if (isset($_SESSION['emp_id']))
    {
        $id = $_SESSION['emp_id'];
        $query = "SELECT * FROM employee WHERE emp_id = '$id' LIMIT 1";
        
        $result = mysqli_query($con, $query);
        if ($result && mysqli_num_rows($result) > 0)
        {
            $user_data = mysqli_fetch_assoc($result);
            return $user_data;
        }
    }
    else if (basename($_SERVER['PHP_SELF']) !== 'home.php')
    {
        header("Location: home.php");
        die;
    }
}
?>