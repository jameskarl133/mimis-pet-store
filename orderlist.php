<?php
include "components/db.php";

// Fetch order details from the purchase table using the generated invoice_id
$sql = "SELECT p.*, pr.prod_name FROM purchase p
        INNER JOIN product pr ON p.prod_id = pr.prod_id
        WHERE p.pur_status = 'Pending'";
$result = mysqli_query($conn, $sql);

// Initialize an array to store order details
$orderDetails = array();

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        // Store each row in the array
        $orderDetails[] = $row;
    }
}

// Check if the checkout button is pressed
if (isset($_POST['checkout'])) {
    // Start a transaction to ensure atomic operations
    mysqli_begin_transaction($conn);

    try {
        // Update the purchase table and set status to 'done'
        $updatePurchaseSql = "UPDATE purchase SET pur_status = 'done' WHERE pur_status = 'Pending'";
        mysqli_query($conn, $updatePurchaseSql);

        // Update invoice status to 'closed'
        $updateInvoiceSql = "UPDATE invoice SET invoice_status = 'closed' WHERE invoice_status = 'open'";
        mysqli_query($conn, $updateInvoiceSql);

        // Update inventory quantities
        foreach ($orderDetails as $order) {
            $productId = $order['prod_id'];
            $purQty = $order['pur_qty'];

            // Subtract pur_qty from inv_item_qty
            $updateInventorySql = "UPDATE inventory SET inv_item_qty = inv_item_qty - $purQty WHERE prod_id = $productId";
            mysqli_query($conn, $updateInventorySql);
        }

        // Commit the transaction
        mysqli_commit($conn);
    } catch (Exception $e) {
        // Rollback the transaction on error
        mysqli_rollback($conn);
        echo "Transaction failed: " . $e->getMessage();
    }
}
if (isset($_POST['cancel'])) {
    // Start a transaction to ensure atomic operations
        // Update the purchase table and set status to 'done'
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
            <?php if (!empty($orderDetails)): ?>
                <h1>Order Details</h1>
                <table>
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Product Price</th>
                            <th>Quantity</th>
                            <th>Total Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orderDetails as $order): ?>
                            <tr>
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
