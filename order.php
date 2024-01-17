<?php
session_start();
include "components/db.php";

$updateSql = "UPDATE inventory SET inv_item_status = 'unavailable' WHERE inv_item_qty = 0";
mysqli_query($conn, $updateSql);

$sql = "SELECT * FROM product
        INNER JOIN inventory ON product.prod_id = inventory.prod_id
        WHERE inventory.inv_item_status = 'available'";
$result = mysqli_query($conn, $sql);

$productData = array();

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $productData[] = $row;
    }
}

$errorMessage = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['product_id']) && isset($_POST['quantity'])) {
        $productId = $_POST['product_id'];
        $quantity = $_POST['quantity'];

        // Check if the entered quantity is valid
        $checkInventorySql = "SELECT inv_item_qty FROM inventory WHERE prod_id = '$productId'";
        $checkInventoryResult = mysqli_query($conn, $checkInventorySql);

        if (!$checkInventoryResult) {
            die("Error checking inventory: " . mysqli_error($conn));
        }

        $inventoryRow = mysqli_fetch_assoc($checkInventoryResult);
        $inventoryQty = $inventoryRow['inv_item_qty'];

        // Check if the total quantity in the cart exceeds available inventory quantity
        $totalQuantitySql = "SELECT COALESCE(SUM(pur_qty), 0) as total FROM purchase WHERE prod_id = '$productId' AND pur_status = 'Pending'";
        $totalQuantityResult = mysqli_query($conn, $totalQuantitySql);

        if (!$totalQuantityResult) {
            die("Error checking total quantity: " . mysqli_error($conn));
        }

        $totalQuantityRow = mysqli_fetch_assoc($totalQuantityResult);
        $totalQuantityInCart = $totalQuantityRow['total'];
        
        if (($totalQuantityInCart + $quantity) > $inventoryQty) {
            $errorMessage = "Error: Total quantity in the cart exceeds available inventory quantity.";
        } else {
            // Proceed with your existing code for handling purchase

            $openInvoiceSql = "SELECT * FROM invoice WHERE invoice_status = 'open'";
            $openInvoiceResult = mysqli_query($conn, $openInvoiceSql);

            if (!$openInvoiceResult) {
                die("Error checking open invoice: " . mysqli_error($conn));
            }

            $invoiceId = null;

            if (mysqli_num_rows($openInvoiceResult) > 0) {
                $openInvoiceRow = mysqli_fetch_assoc($openInvoiceResult);
                $invoiceId = $openInvoiceRow['invoice_id'];

                $employeeIdSql = "SELECT emp_id FROM invoice WHERE invoice_id = '$invoiceId'";
                $employeeIdResult = mysqli_query($conn, $employeeIdSql);
                if (!$employeeIdResult) {
                    die("Error retrieving employee ID: " . mysqli_error($conn));
                }

                $employeeIdRow = mysqli_fetch_assoc($employeeIdResult);
                $_SESSION["emp_id"] = $employeeIdRow['emp_id'];

            } else {
                // Fetch the employee ID before using it in the INSERT query
                $employeeIdSql = "SELECT emp_id FROM employee WHERE emp_id = '$_SESSION[emp_id]'";
                $employeeIdResult = mysqli_query($conn, $employeeIdSql);

                if (!$employeeIdResult) {
                    die("Error retrieving employee ID: " . mysqli_error($conn));
                }

                $employeeIdRow = mysqli_fetch_assoc($employeeIdResult);
                $_SESSION["emp_id"] = $employeeIdRow['emp_id'];

                // Now you can use $employeeIdResult in the INSERT query
                $insertInvoiceSql = "INSERT INTO invoice (emp_id, cus_id, invoice_date, invoice_status) VALUES ('$employeeIdRow[emp_id]', (SELECT MAX(cus_id) FROM customer), NOW(), 'open')";
                if (!mysqli_query($conn, $insertInvoiceSql)) {
                    die("Error creating new invoice: " . mysqli_error($conn));
                }

                $invoiceId = mysqli_insert_id($conn);
            }

            // Check if the same product already exists in the open invoice
            $checkDuplicateSql = "SELECT * FROM purchase WHERE prod_id = '$productId' AND invoice_id = '$invoiceId'";
            $checkDuplicateResult = mysqli_query($conn, $checkDuplicateSql);

            if (!$checkDuplicateResult) {
                die("Error checking duplicate: " . mysqli_error($conn));
            }

            if (mysqli_num_rows($checkDuplicateResult) > 0) {
                // If the product already exists, update the quantity instead of creating a new record
                $updateQuantitySql = "UPDATE purchase SET pur_qty = pur_qty + $quantity WHERE prod_id = '$productId' AND invoice_id = '$invoiceId'";
                if (!mysqli_query($conn, $updateQuantitySql)) {
                    $errorMessage = "Error updating quantity: " . mysqli_error($conn);
                } else {
                    $errorMessage = "order quantity updated successfully.";
                }
            } else {
                // If the product doesn't exist, create a new record
                $insertPurchaseSql = "INSERT INTO purchase (pur_qty, pur_price, pur_status, prod_id, invoice_id) 
                                      VALUES ('$quantity', (SELECT prod_price FROM product WHERE prod_id = '$productId'), 'Pending', '$productId', '$invoiceId')";
                if (!mysqli_query($conn, $insertPurchaseSql)) {
                    $errorMessage = "Error inserting into purchase table: " . mysqli_error($conn);
                } else {
                    $errorMessage = "order inserted successfully.";
                }
            }
        }
    }
}
?>
<?php
include "components/db.php";

$sql = "SELECT p.*, pr.prod_name FROM purchase p
        INNER JOIN product pr ON p.prod_id = pr.prod_id
        WHERE p.pur_status = 'Pending'";
$result = mysqli_query($conn, $sql);


$orderDetails = array();

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $orderDetails[] = $row;
    }
}


if (isset($_POST['checkout'])) {

    mysqli_begin_transaction($conn);

    try {

        $updatePurchaseSql = "UPDATE purchase SET pur_status = 'done' WHERE pur_status = 'Pending'";
        mysqli_query($conn, $updatePurchaseSql);

        $updateInvoiceSql = "UPDATE invoice SET invoice_status = 'closed' WHERE invoice_status = 'open'";
        mysqli_query($conn, $updateInvoiceSql);

        $newcustomersql = "INSERT INTO CUSTOMER VALUES ('','','')";
        mysqli_query($conn, $newcustomersql);

        foreach ($orderDetails as $order) {
            $productId = $order['prod_id'];
            $purQty = $order['pur_qty'];

            $updateInventorySql = "UPDATE inventory SET inv_item_qty = inv_item_qty - $purQty WHERE prod_id = $productId";
            mysqli_query($conn, $updateInventorySql);
        }
        $errorMessage = "checked out successfully.";
        mysqli_commit($conn);
    } catch (Exception $e) {
        mysqli_rollback($conn);
        echo "Transaction failed: " . $e->getMessage();
    }
}
if (isset($_POST['cancel'])) {
        $cancelPurchaseSql = "UPDATE purchase SET pur_status = 'cancelled' WHERE pur_status = 'Pending'";
        mysqli_query($conn, $cancelPurchaseSql);
        $cancelInvoiceSql = "UPDATE invoice SET invoice_status = 'cancelled' WHERE invoice_status = 'open'";
        mysqli_query($conn, $cancelInvoiceSql);
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
                            <th>Input Quantity</th>
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
                            td = tr[i].getElementsByTagName("td")[0];
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
    <div class="content">
        <div class="container">
            <?php if (!empty($orderDetails)): ?>
                <p>orderlist</p>
                <table>
                    <thead>
                        <tr><th>Invoice Id :</th>
                            <th>Product</th>
                            <th>Product Price</th>
                            <th>Quantity</th>
                            <th>Total Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orderDetails as $order): ?>
                            <tr>
                                <td><?= $order['invoice_id']; ?></td>
                                <td><?= $order['prod_name']; ?></td>
                                <td><?= $order['pur_price']; ?></td>
                                <td><?= $order['pur_qty']; ?></td>
                                <td><?= $order['pur_qty'] * $order['pur_price']; ?></td>
                            </tr>
                        <?php endforeach; ?>
                        <tr>
                            <td>Total</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td>
                                <?php 
                                    $totalPrices = array_map(function ($order) {
                                        return $order['pur_qty'] * $order['pur_price'];
                                    }, $orderDetails);
                                    echo array_sum($totalPrices);
                                ?>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <!-- Checkout Button -->
            <?php else: ?>
                <p>No orders available.</p>
            <?php endif; ?>
        </div>
        <form method="post">
                    <button type="submit" name="checkout">Checkout</button>
                    <button type="submit" name="cancel">Cancel Invoice</button>
                </form>
    </div>

    <?php include "components/scripts.php"; ?>
</body>
</html>
