<?php

include "components/db.php";

// Function to get all products
function getAllProducts($conn) {
    $query = "SELECT * FROM product";
    $result = mysqli_query($conn, $query);

    if (!$result) {
        // Handle the error, for example, by printing the error message
        die("Error: " . mysqli_error($conn));
    }

    return $result;
}

// Function to get brand data for a product
function getBrandData($conn, $productId) {
    $brand_query = "SELECT prod_brand FROM product WHERE prod_id = $productId";
    $brand_result = mysqli_query($conn, $brand_query);

    if (!$brand_result) {
        // Handle the error, for example, by printing the error message
        die("Error: " . mysqli_error($conn));
    }

    return mysqli_fetch_assoc($brand_result);
}

// Get all products
$productResult = getAllProducts($conn);

// Handle form submission after the HTML content
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["quantity"]) && isset($_POST["productId"]) && isset($_POST["productName"])) {
        // Retrieve form data
        $quantity = $_POST["quantity"];
        $productId = $_POST["productId"];
        $productName = $_POST["productName"];

        //Perform your database insertion here
        $insertQuery = "INSERT INTO requested (request_qty, req_id, prod_id) VALUES ($quantity, $reqId, $productId)";
        mysqli_query($conn, $insertQuery);

        // Display a confirmation message
        echo "<script>alert('Product Requested\\nProduct ID: $productId\\nProduct Name: $productName\\nQuantity: $quantity');</script>";
    }
}

?>

<html>
<head>
    <?php include "Style.php"; ?>
    <style>
        .modal {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            border: 1px solid #ccc;
            padding: 20px;
            background-color: #fff;
            z-index: 1000;
        }
        </style>
</head>
<body>
    <?php include "header.php"; ?>

    <!-- Display Product Details -->
    <div class="content">
        <div class="container">
            <h2>Product Details</h2>

            <!-- Search Box -->
            <form action="" method="post">
                <label for="search">Search Product:</label>
                <input type="text" id="search" name="search" placeholder="Type to search">
                <input type="submit" value="Search">
            </form>
            <br>

            <form action="" method="post">
                <table>
                    <tr>
                        <th>Product ID</th>
                        <th>Product Name</th>
                        <th>Product Description</th>
                        <th>Product Price</th>
                        <th>Brand</th>
                        <th>Action</th>
                    </tr>
                    <?php
                    while ($row = mysqli_fetch_assoc($productResult)) {
                        $brand_data = getBrandData($conn, $row['prod_id']);
                        ?>
                        <tr>
                            <td><?php echo $row['prod_id']; ?></td>
                            <td class="editable" onclick="editCell('<?php echo $row['prod_id']; ?>', 'Prod_Name', '<?php echo $row['prod_name']; ?>')"><?php echo $row['prod_name']; ?></td>
                            <td class="editable" onclick="editCell('<?php echo $row['prod_id']; ?>', 'Prod_Desc', '<?php echo $row['prod_desc']; ?>')"><?php echo $row['prod_desc']; ?></td>
                            <td class="editable" onclick="editNumberCell('<?php echo $row['prod_id']; ?>', 'Prod_Price', '<?php echo $row['prod_price']; ?>')"><?php echo $row['prod_price']; ?></td>
                            <td><?php echo $brand_data['prod_brand']; ?></td>
                            <td><button type="button" onclick="openRequestPopup('<?php echo $row['prod_id']; ?>', '<?php echo $row['prod_name']; ?>')">Request</button></td>
                        </tr>
                        <?php
                    }
                    ?>
                </table>
            </form>
        </div>
    </div>
    
    <!-- Modal popup for requesting a product -->
    <div id="requestModal" class="modal">
        <h3>Request Product</h3>
        <form id="requestForm" method="post">
            <label for="quantity">Quantity:</label>
            <input type="number" id="quantity" name="quantity" required>
            <input type="hidden" id="productId" name="productId" value="">
            <input type="hidden" id="productName" name="productName" value="">
            <button type="submit">Submit Request</button>
        </form>
        <button type="button" onclick="closeRequestPopup()">Cancel</button>
    </div>

    <script>
        // JavaScript function to open the request popup
        function openRequestPopup(productId, productName) {
            document.getElementById('productId').value = productId;
            document.getElementById('productName').value = productName;
            document.getElementById('requestModal').style.display = 'block';
        }

        // JavaScript function to close the request popup
        function closeRequestPopup() {
            document.getElementById('requestModal').style.display = 'none';
        }
    </script>
</body>
</html>
