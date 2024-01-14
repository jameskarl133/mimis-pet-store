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

// Initialize variables
$errorMessage = "";

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['product_id']) && isset($_POST['quantity'])) {
        $productId = $_POST['product_id'];
        $quantity = $_POST['quantity'];

        // Check if there is an open invoice for the current user
        $openInvoiceSql = "SELECT * FROM invoice WHERE cus_id = 1 AND invoice_status = 'open'"; // Replace 1 with the actual cus_id
        $openInvoiceResult = mysqli_query($conn, $openInvoiceSql);

        if (!$openInvoiceResult) {
            die("Error checking open invoice: " . mysqli_error($conn));
        }

        $invoiceId = null;

        if (mysqli_num_rows($openInvoiceResult) > 0) {
            // If there is an open invoice, use its ID
            $openInvoiceRow = mysqli_fetch_assoc($openInvoiceResult);
            $invoiceId = $openInvoiceRow['invoice_id'];
        } else {
            // If there is no open invoice, create a new one
            $insertInvoiceSql = "INSERT INTO invoice (emp_id, cus_id, invoice_date, invoice_status) VALUES (1, 1, NOW(), 'open')"; // Replace 1 with the actual cus_id
            if (!mysqli_query($conn, $insertInvoiceSql)) {
                die("Error creating new invoice: " . mysqli_error($conn));
            }

            $invoiceId = mysqli_insert_id($conn);
        }

        // Check if pur_qty is greater than inv_qty_item
        $checkInventorySql = "SELECT inv_item_qty FROM inventory WHERE prod_id = '$productId'";
        $checkInventoryResult = mysqli_query($conn, $checkInventorySql);

        if (!$checkInventoryResult) {
            die("Error checking inventory: " . mysqli_error($conn));
        }

        $inventoryRow = mysqli_fetch_assoc($checkInventoryResult);
        $inventoryQty = $inventoryRow['inv_item_qty'];

        if ($quantity > $inventoryQty) {
            $errorMessage = "Error: Purchase quantity exceeds available inventory quantity.";
        } else {
            // Insert data into the purchase table with the generated invoice ID
            $insertPurchaseSql = "INSERT INTO purchase (pur_qty, pur_price, pur_status, prod_id, invoice_id) 
                                  VALUES ('$quantity', (SELECT prod_price FROM product WHERE prod_id = '$productId'), 'Pending', '$productId', '$invoiceId')";
            if (!mysqli_query($conn, $insertPurchaseSql)) {
                $errorMessage = "Error inserting into purchase table: " . mysqli_error($conn);
            } else {
                $errorMessage = "Data inserted successfully.";
            }
        }
    }
}
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
            <input type="text" id="searchInput" onkeyup="filterProducts()" placeholder="Search for products...">

            <?php if (!empty($productData)): ?>
                <table id="productTable">
                    <thead>
                        <tr>
                            <th>Product Name</th>
                            <th>Description</th>
                            <th>Price</th>
                            <th>Inventory Quantity</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($productData as $product): ?>
                            <tr>
                                <td><?= $product['prod_name'] ?></td>
                                <td><?= $product['prod_desc'] ?></td>
                                <td><?= $product['prod_price'] ?></td>
                                <td><?= $product['inv_item_qty'] ?></td>
                                <td>
                                    <div class="add-to-cart-container">
                                        <form method="POST" action="">
                                            <input type="hidden" name="product_id" value="<?= $product['prod_id'] ?>">
                                            <input type="number" name="quantity" value="0">
                                            <button type="submit">Add to Cart</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <script>
                    function filterProducts() {
                        var input, filter, table, tr, td, i, txtValue;
                        input = document.getElementById("searchInput");
                        filter = input.value.toUpperCase();
                        table = document.getElementById("productTable");
                        tr = table.getElementsByTagName("tr");

                        for (i = 0; i < tr.length; i++) {
                            td = tr[i].getElementsByTagName("td")[0]; // Product Name column
                            if (td) {
                                txtValue = td.textContent || td.innerText;
                                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                                    tr[i].style.display = "";
                                } else {
                                    tr[i].style.display = "none";
                                }
                            }
                        }
                    }
                </script>
            <?php else: ?>
                <p>No products are available.</p>
            <?php endif; ?>

            <?php if ($errorMessage): ?>
                <div class="alert">
                    <?php echo $errorMessage; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <?php include "components/scripts.php"; ?>
</body>
</html>
