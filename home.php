<?php
session_start();

if (!isset($_SESSION["username"])) {
    header("Location: login_page.php");
    exit();
}

$user_type = isset($_SESSION["emp_type"]) ? $_SESSION["emp_type"] : "";
$user_name = isset($_SESSION["username"]) ? $_SESSION["username"] : "";

?>
<html>
<head>
<?php include "addprodStyle.php"; ?>
</head>
<body>
<?php include "addprodHeader.php"; ?>

<div class="content">
        <div class="container">
        </div>
    </div>
    <div class="content">
        <div class="container">
            <?php
            echo "<h1>Welcome: $user_type, $user_name!</h1>";
            ?>
        </div>
    </div>

    <form action="" method="post">
        <div class="content">
            <div class="container">
            </div>
        </div>
    </form>
</body>
</html>
