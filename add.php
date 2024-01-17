<?php
session_start();
include "components/db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $productName = $_POST['productName'];
    $productDescription = $_POST['productDescription'];
    $productPrice = floatval($_POST['productPrice']);
    if ($productPrice < 0) {
        echo '<script>alert("Error: Product price cannot be a negative number.");window.location.href = "'.$_SERVER['PHP_SELF'].'";</script>';
        exit();
    }
    $productBrand = $_POST['productBrand'];

    $sql = "INSERT INTO product (prod_name, prod_desc, prod_price, prod_brand) VALUES ('$productName', '$productDescription', '$productPrice', '$productBrand')";

    if ($conn->query($sql) === TRUE) {
        
        header("Location: ".$_SERVER['PHP_SELF']);
        exit(); 
    } else {
        
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
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
                <h1>Add Product</h1><br>

                <label for="productName">Product Name:</label>
                <input type="text" id="productName" name="productName" value="<?php echo isset($productName) ? $productName : ''; ?>" required>

                <label for="productDescription">Product Description:</label>
                <textarea id="productDescription" name="productDescription" required><?php echo isset($productDescription) ? $productDescription : ''; ?></textarea>

                <label for="productPrice">Product Price:</label>
                <input type="text" id="productPrice" name="productPrice" value="<?php echo isset($productPrice) ? $productPrice : ''; ?>" required>

                <label for="productBrand">Product Brand:</label>
                <input type="text" id="productBrand" name="productBrand" value="<?php echo isset($productBrand) ? $productBrand : ''; ?>" required>


                <button type="submit">Add Product</button>
            </div>
        </div>
    </form>
</body>
</html>
