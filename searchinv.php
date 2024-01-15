<html>
    <?php include "components/db.php";?>
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
            <h1>Search Invoice</h1>
            <form method="post" action="">
                <label for="invoice_id">Enter Invoice ID:</label>
                <input type="text" id="invoice_id" name="invoice_id" required>
                <input type="submit" value="Search" name="search">
            </form>

            <?php 
            if(isset($_POST["search"])){
                $invoiceId = mysqli_real_escape_string($conn, $_POST["invoice_id"]);
                $sql = "SELECT * FROM invoice WHERE invoice_id = '$invoiceId'";
                $result = $conn->query($sql);
            
                if($result && $result->num_rows > 0){
                    $invoiceData = $result->fetch_assoc();
                    echo "<div class='container'>  
                            <table class='table table-bordered text-center'>
                                <thead>
                                    <tr class='bg-dark text-white'>
                                        <th>Invoice Number</th>
                                        <th>Date of Invoice</th>
                                        <th>Employee ID</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>{$invoiceData['invoice_id']}</td>
                                        <td>{$invoiceData['invoice_date']}</td>
                                        <td>{$invoiceData['emp_id']}</td>
                                        <td>{$invoiceData['invoice_status']}</td>
                                    </tr>
                                </tbody>
                            </table>
                          </div>";
                } else {
                    echo "<p>No invoice found with the specified ID.</p>";
                }
            }
            ?>

        </div>
    </div>
    <div class="content">
        <div class="container">  
            <?php
            if(isset($_POST["view"])){
                $view_inv_id = mysqli_real_escape_string($conn, $_POST["inv_id"]);
                $sql = "SELECT * FROM invoice WHERE invoice_id = '$view_inv_id'";
                $result = $conn->query($sql);
            
                $view_sql = "SELECT purchase.invoice_id, pur_id, prod_id, pur_qty, pur_price, invoice_date
                FROM purchase
                INNER JOIN invoice
                ON purchase.invoice_id = invoice.invoice_id
                WHERE purchase.invoice_id = '$view_inv_id'";
                $result_view = $conn->query($view_sql);
                
                if($result && $result->num_rows > 0){
                    $invoiceData = $result->fetch_assoc();
                    echo "<div class='container'>  
                            <table class='table table-bordered text-center'>
                                <thead>
                                    <tr class='bg-dark text-white'>
                                        <th>Invoice Number</th>
                                        <th>Date of Invoice</th>
                                        <th>Employee ID</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>{$invoiceData['invoice_id']}</td>
                                        <td>{$invoiceData['invoice_date']}</td>
                                        <td>{$invoiceData['emp_id']}</td>
                                        <td>{$invoiceData['invoice_status']}</td>
                                    </tr>
                                </tbody>
                            </table>
                          </div>";

                    if ($invoiceData['invoice_status'] === 'cancelled') {
                        echo "<p class='text-danger'>Invoice is cancelled.</p>";
                    }

                }

                if ($result_view && $result_view->num_rows > 0) {
                    
                    echo "<div class='container'>  
                            <table class='table table-bordered text-center'>
                                <thead>
                                    <tr class='bg-dark text-white'>
                                        <th>Purchase ID</th>
                                        <th>Order quantity</th>
                                        <th>Order price</th>
                                        <th>Date of Invoice</th>
                                        <th>Total Amount</th>
                                    </tr>
                                </thead>
                                <tbody>";
                                $totalSum = 0;

                                foreach ($result_view as $row) {
                                    $totalPrice = $row['pur_qty'] * $row['pur_price'];
                                    $totalSum += $totalPrice;
                                
                                    echo "<tr>
                                            <td>{$row['pur_id']}</td>
                                            <td>{$row['pur_qty']}</td>
                                            <td>{$row['pur_price']}</td>
                                            <td>{$row['invoice_date']}</td>
                                            <td>{$totalPrice}</td>
                                          </tr>";
                                }
                                
                                echo "<tr>
                                        <td colspan='4'>Total:</td>
                                        <td>{$totalSum}</td>
                                      </tr>";
                                

                    echo "</tbody>
                          </table>
                          </div>";
                } else {
                    echo "<p>No purchases found for the specified invoice ID.</p>";
                }
            }
            ?>
            <form method="post" action="">
                <input type="hidden" name="inv_id" value="<?php echo isset($invoiceData['invoice_id']) ? $invoiceData['invoice_id'] : ''; ?>">
                <input type="submit" value="View Invoice" name="view">
            </form>
        </div>
    </div>

    <?php include "components/scripts.php"; ?>
</body>
</html>
