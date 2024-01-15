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

// Function to get all requested products
function getAllRequestedProducts($conn) {
    $query = "SELECT requested.*, product.prod_name FROM requested
              JOIN product ON requested.prod_id = product.prod_id
              WHERE requested.req_id IS NULL";
    $result = mysqli_query($conn, $query);

    if (!$result) {
        // Handle the error, for example, by printing the error message
        die("Error: " . mysqli_error($conn));
    }

    return $result;
}

// Handle form submission for product request
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["requestQuantity"])) {
    // Retrieve form data
    $quantity = $_POST["requestQuantity"];
    $productId = $_POST["requestProductId"];
    $productName = $_POST["requestProductName"];

    // Fetch the actual product price from the result set
    $selectProdprice = "SELECT prod_price FROM product WHERE prod_id = $productId";
    $prod_price_result = mysqli_query($conn, $selectProdprice);

    // Check if the query was successful
    if (!$prod_price_result) {
        die("Error: " . mysqli_error($conn));
    }

    $prod_price_row = mysqli_fetch_assoc($prod_price_result);
    $defaultPrice = $prod_price_row['prod_price'];

    // Check if the price textbox is null
    $finalPrice = isset($_POST["requestPrice"]) && $_POST["requestPrice"] !== '' ? $_POST["requestPrice"] : $defaultPrice;

    // Check if there is an existing request for the same product
    $existingRequestQuery = "SELECT * FROM requested WHERE prod_id = $productId AND req_id IS NULL";
    $existingRequestResult = mysqli_query($conn, $existingRequestQuery);

    if (!$existingRequestResult) {
        die("Error: " . mysqli_error($conn));
    }

    if (mysqli_num_rows($existingRequestResult) > 0) {
        // If there is an existing request, update the quantity
        $updateQuery = "UPDATE requested SET request_qty = request_qty + $quantity, request_price = $finalPrice WHERE prod_id = $productId AND req_id IS NULL";
        mysqli_query($conn, $updateQuery);
    } else {
        // If there is no existing request, insert a new request
        $insertQuery = "INSERT INTO requested (request_qty, request_price, prod_id) VALUES ($quantity, $finalPrice, $productId)";
        mysqli_query($conn, $insertQuery);
    }

    // Display a confirmation message
    echo "<script>alert('Product Requested\\nProduct ID: $productId\\nProduct Name: $productName\\nQuantity: $quantity');</script>";
}

// Fetch all requested products
$requestedProducts = getAllRequestedProducts($conn);

// Handle removal of requested product
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["removeReqId"])) {
    $removeReqId = $_POST["removeReqId"];

    // Check if $removeReqId is a valid integer
    if (!is_numeric($removeReqId) || $removeReqId <= 0) {
        die("Invalid request ID");
    }

    // Perform your database deletion here based on $removeReqId
    $deleteQuery = "DELETE FROM requested WHERE request_id = " . mysqli_real_escape_string($conn, $removeReqId);
    $deleteResult = mysqli_query($conn, $deleteQuery);

    if (!$deleteResult) {
        die("Error: " . mysqli_error($conn));
    }

    // Redirect to the same page to reflect changes
    header("Location: {$_SERVER['PHP_SELF']}");
    exit();
}

// Handle confirmation of requested product
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["confirmRequest"])) {
    $supplierId = $_POST["supplierId"];
    $empId = 1; // You mentioned inserting emp_id as 1

    // Fetch requested products with req_id as null
    $getRequestedQuery = "SELECT * FROM requested WHERE req_id IS NULL";
    $getRequestedResult = mysqli_query($conn, $getRequestedQuery);

    if (!$getRequestedResult) {
        die("Error: " . mysqli_error($conn));
    }

    // Check if there are requested products
    if (mysqli_num_rows($getRequestedResult) > 0) {
        // Perform the database insertion for requisition
        $insertRequisitionQuery = "INSERT INTO requisition (req_stat, req_date, emp_id, sup_id) VALUES ('Pending', current_timestamp, $empId, $supplierId)";
        $insertRequisitionResult = mysqli_query($conn, $insertRequisitionQuery);

        if (!$insertRequisitionResult) {
            die("Error: " . mysqli_error($conn));
        }

        // Get the last inserted req_id
        
        if (!$updateRequestedResult) {
            die("Error: " . mysqli_error($conn));
        }

        // Redirect to the same page to reflect changes
        header("Location: {$_SERVER['PHP_SELF']}");
        exit();
    } else {
        echo "No requested products to confirm.";
    }
}

$sqlSuppliers = "SELECT sup_id, sup_name FROM supplier";
$resultSuppliers = $conn->query($sqlSuppliers);

// Check if there are results
if ($resultSuppliers->num_rows > 0) {
    // Create an array to store supplier data
    $supplierData = array();

    // Fetch each supplier ID and name and store it in the array
    while ($rowSupplier = $resultSuppliers->fetch_assoc()) {
        $supplierData[] = $rowSupplier;
    }
} else {
    // Handle the case when there are no suppliers
    $supplierData = array();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submitRequest"])) {
    // Fetch a valid emp_id from the employee table (you may need to modify this based on your logic)
    $sqlEmployee = "SELECT emp_id FROM employee LIMIT 1";
    $resultEmployee = $conn->query($sqlEmployee);

if ($resultEmployee->num_rows > 0) {
    $rowEmployee = $resultEmployee->fetch_assoc();
    $empId = $rowEmployee['emp_id'];

    // Continue building the $insertRequisition SQL statement
    $insertRequisition = "INSERT INTO requisition (emp_id, sup_id) VALUES ";
    foreach ($supplierData as $supplier) {
        $insertRequisition .= "($empId, {$supplier['sup_id']}),";
    }
    // Remove the trailing comma
    $insertRequisition = rtrim($insertRequisition, ',');
    
    
    // Insert requisitions into the database
    if ($conn->query($insertRequisition) === TRUE) {
        echo "Requisitions inserted successfully";
        $lastReqId = mysqli_insert_id($conn);

        // Update the requested products with the corresponding requisition ID
        $updateRequestedQuery = "UPDATE requested SET req_id = $lastReqId WHERE req_id IS NULL";
        $updateRequestedResult = mysqli_query($conn, $updateRequestedQuery);

    } else {
        echo "Error inserting requisitions: " . $conn->error;
    }
} else {
    echo "No employee found to associate with requisition.";
}


}



?>

<html>
<head>
    <?php include "Style.php"; ?>
    <style>
        .request-form {
            display: none;
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
                <input type="text" id="search" name="search" placeholder="Type to search" onkeyup="searchProducts()">
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
                            <td>
                                <button type="button" onclick="showRequestForm('<?php echo $row['prod_id']; ?>', '<?php echo $row['prod_name']; ?>')">Request</button>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                </table>
            </form>

            <!-- Hidden form for requesting a product -->
            <form id="requestForm" class="request-form" method="post">
            <label for="requestQuantity">Quantity:</label>
            <input type="number" id="requestQuantity" name="requestQuantity" required>
            
            <!-- Add the textbox for price -->
            <label for="requestPrice">Price (optional):</label>
            <input type="number" id="requestPrice" name="requestPrice" placeholder="Leave empty to use default">

            <input type="hidden" id="requestProductId" name="requestProductId" value="">
            <input type="hidden" id="requestProductName" name="requestProductName" value="">
            <button type="submit" name="submitRequest">Submit Request</button>
            <button type="button" onclick="hideRequestForm()">Cancel</button>
        </form>
        </div>
    </div>

    <!-- Display Requested Products -->
    <div class="content">
    <div class="container">
        <h2>Requested Products</h2>
        <table>
            <tr>
                <th>Request ID</th>
                <th>Product Name</th>
                <th>Quantity</th>
                <th>Action</th>
            </tr>
            <?php
            while ($requestedRow = mysqli_fetch_assoc($requestedProducts)) {
                ?>
                <tr>
                    <td><?php echo isset($requestedRow['request_id']) ? $requestedRow['request_id'] : ''; ?></td>
                    <td><?php echo isset($requestedRow['prod_name']) ? $requestedRow['prod_name'] : ''; ?></td>
                    <td><?php echo isset($requestedRow['request_qty']) ? $requestedRow['request_qty'] : ''; ?></td>
                    <td>
                        <!-- Form for removing the product -->
                        <form method="post" action="">
                        <input type="hidden" name="removeReqId" value="<?php echo isset($requestedRow['request_id']) ? $requestedRow['request_id'] : ''; ?>">
                            <button type="submit">Remove</button>
                        </form>
                    </td>
                </tr>
                <?php
            }
            ?>
        </table>
        <form method="post" action="">
    <!-- Dropdown for selecting a supplier -->
    <label for="supplierId">Select Supplier: </label>
    <select id="supplierId" name="supplierId" required>
        <?php
        // Fetch and display supplier options from the supplier table
        $supplierQuery = "SELECT * FROM supplier";
        $supplierResult = mysqli_query($conn, $supplierQuery);

        if (!$supplierResult) {
            die("Error: " . mysqli_error($conn));
        }

        while ($supplierRow = mysqli_fetch_assoc($supplierResult)) {
            echo "<option value='{$supplierRow['sup_id']}'>{$supplierRow['sup_name']}</option>";
        }
        ?>
    </select>

    <button type="submit" name="confirmRequest">Confirm</button>
</form>
    </div>
</div>
    <script>
        // JavaScript function to show the request form
        function showRequestForm(productId, productName) {
            document.getElementById('requestProductId').value = productId;
            document.getElementById('requestProductName').value = productName;
            document.getElementById('requestForm').style.display = 'block';
        }

        // JavaScript function to hide the request form
        function hideRequestForm() {
            document.getElementById('requestForm').style.display = 'none';
        }

        // JavaScript function to search products
        function searchProducts() {
            let input, filter, table, tr, td, i, txtValue;
            input = document.getElementById("search");
            filter = input.value.toUpperCase();
            table = document.querySelector("table");
            tr = table.getElementsByTagName("tr");

            for (i = 0; i < tr.length; i++) {
                td = tr[i].getElementsByTagName("td")[1]; // Assuming the product name is in the second column
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
        function removeProduct(reqId) {
        alert('Removing product with req_id: ' + reqId);
        }
    </script>
</body>
</html>
