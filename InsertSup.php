<?php
include "components/db.php";

// Add Supplier
if (isset($_POST["submit"])) {
    $name = $_POST["sup_name"];
    $email = $_POST["sup_email"];
    $phone = $_POST["sup_phone"];

    $query = "INSERT INTO supplier (sup_name, sup_email, sup_phone) VALUES('$name', '$email', '$phone')";
    mysqli_query($conn, $query);

    echo "<script>alert('Supplier is added');</script>";
}

?>

<html>
<head>
    <?php include "Style.php"; ?>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        td.editable {
            cursor: pointer;
        }

        td.editable:hover {
            background-color: #f0f0f0;
        }
    </style>
</head>
<body>
    <?php include "header.php"; ?>

    <div class="content">
        <div class="container">
            <h1> Mimi's Pet Shop </h1>
        </div>
    </div>

    <!-- Add Supplier Form -->
    <form action="" method="post">
        <div class="content">
            <div class="container">
                <h2>Add Supplier</h2>
                <label for="sup_name">Supplier Name:</label>
                <input type="text" id="sup_name" name="sup_name" required><br><br>
                <label for="sup_email">Supplier Email:</label>
                <input type="text" id="sup_email" name="sup_email" required><br><br>
                <label for="sup_phone">Supplier Phone:</label>
                <input type="text" id="sup_phone" name="sup_phone" required><br><br>
                <input type="submit" name="submit" value="Add">
            </div>
        </div>
    </form>



    <?php include "scripts.php"; ?>
</body>
</html>