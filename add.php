<?php
session_start();
include "components/db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $productName = $_POST['productName'];
    $productDescription = $_POST['productDescription'];
    $productPrice = $_POST['productPrice'];
    $productBrand = $_POST['productBrand'];

    $sql = "INSERT INTO product (prod_name, prod_desc, prod_price, prod_brand) VALUES ('$productName', '$productDescription', '$productPrice', '$productBrand')";

    if ($con->query($sql) === TRUE) {
        
        header("Location: ".$_SERVER['PHP_SELF']);
        exit(); 
    } else {
        
        echo "Error: " . $sql . "<br>" . $con->error;
    }

    $con->close();
}
?>

<html>
<head>
<?php include "addprodStyle.php"; ?>
</head>
<body>
<?php include "header.php"; ?>

    <div class="content">
        <div class="container">
            <h1>Mimi's Pet Shop</h1>
        </div>
    </div>

    <form action="" method="post">
        <div class="content">
            <div class="container">
                <h2>Add Product</h2><br>

                <label for="productName">Product Name:</label>
                <input type="text" id="productName" name="productName" required>

                <label for="productDescription">Product Description:</label>
                <textarea id="productDescription" name="productDescription" required></textarea>

                <label for="productPrice">Product Price:</label>
                <input type="text" id="productPrice" name="productPrice" required>

                <label for="productBrand">Product Brand:</label>
                <input type="text" id="productBrand" name="productBrand" required>

                <button type="submit">Add Product</button>
            </div>
        </div>
    </form>
</body>
</html>
