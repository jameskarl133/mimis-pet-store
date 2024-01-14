<?php
include "components/db.php";

// Update inventory status to 'unavailable' when inv_item_qty is 0
$updateSql = "UPDATE inventory SET inv_item_status = 'unavailable' WHERE inv_item_qty = 0";
mysqli_query($conn, $updateSql);

// Fetch products with inventory quantity > 0
$sql = "SELECT * FROM product
        INNER JOIN inventory ON product.prod_id = inventory.prod_id
        WHERE inventory.inv_item_status = 'available'";
$result = mysqli_query($conn, $sql);

// Initialize an array to store product data
$productData = array();

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        // Store each row in the array
        $productData[] = $row;
    }
}

// Close the database connection
mysqli_close($conn);
?>

<html>
<head>
    <?php include "components/head.php"; ?>
</head>
<body>
    <?php include "components/nav.php"; ?>

    <div class="content">
        <div class="container">
            <h1> Mimi's Pet Shop </h1>
            <p>Mahayahay, Gabi, Cordova</p>
            <p>mimispetcorner@gmail.com</p>
        </div>
    </div>
    <div class="content">
        <div class="container">
            <?php if (!empty($productData)): ?>
                <div class="product-container">
                    <?php foreach ($productData as $product): ?>
                        <div class="collapsible" onclick="toggleContent(this)">
                            <?= $product['prod_name'] ?>
                        </div>
                        <div class="content">
                            <p>Description: <?= $product['prod_desc'] ?></p>
                            <p>Price: <?= $product['prod_price'] ?></p>
                            <p>Inventory Quantity: <?= $product['inv_item_qty'] ?></p>
                            <div class="add-to-cart-container">
                                <input type="number" id="quantity<?= $product['prod_id'] ?>" value="0">
                                <button onclick="addToCart('<?= $product['prod_name'] ?>', document.getElementById('quantity<?= $product['prod_id'] ?>').value)">Add to Cart</button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p>No products are available.</p>
            <?php endif; ?>
        </div>
    </div>

    <?php include "components/scripts.php"; ?>

    <script>
        function toggleContent(element) {
            var content = element.nextElementSibling;
            content.style.display = (content.style.display === "none" || content.style.display === "") ? "block" : "none";
        }

        function addToCart(productName, quantity) {
            // You can add logic here to handle adding the product to the cart
            alert("Added " + quantity + " units of " + productName + " to the cart!");
        }
    </script>
</body>
</html>
