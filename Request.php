<?php
include "components/db.php";

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["req_id"])) {
    $reqId = $_POST["req_id"];

    // Check if the form was submitted for approval
    if (isset($_POST["approve"])) {
        // Update req_stat to "APPROVED"
        $updateQuery = "UPDATE requisition SET req_stat = 'APPROVED' WHERE req_id = $reqId";

        // Run the query and check for errors
        if ($conn->query($updateQuery) === TRUE) {
            echo "<script>alert('Record updated successfully');</script>";
        } else {
            echo "Error updating record: " . $conn->error;
        }
    } elseif (isset($_POST["receive"])) {
        // Update req_stat to "RECEIVED"
        $updateQuery = "UPDATE requisition SET req_stat = 'RECEIVED' WHERE req_id = $reqId";

        // Run the query and check for errors
        if ($conn->query($updateQuery) === TRUE) {
            // Update inventory based on requisition details
            $sqlRequisitionDetails = "SELECT * FROM requested WHERE req_id = $reqId";
            $resultRequisitionDetails = $conn->query($sqlRequisitionDetails);

            if ($resultRequisitionDetails->num_rows > 0) {
                while ($rowDetail = $resultRequisitionDetails->fetch_assoc()) {
                    $productId = $rowDetail['prod_id'];
                    $requestedQty = $rowDetail['request_qty'];

                    // Check if the product exists in the inventory
                    $sqlCheckProduct = "SELECT * FROM inventory WHERE prod_id = $productId";
                    $resultCheckProduct = $conn->query($sqlCheckProduct);

                    if ($resultCheckProduct->num_rows > 0) {
                        // Product exists, update the inventory quantity
                        $updateInventory = "UPDATE inventory SET inv_item_qty = inv_item_qty + $requestedQty 
                                           WHERE prod_id = $productId";
                        $conn->query($updateInventory);
                    } else {
                        // Product doesn't exist, insert a new record in the inventory
                        $insertInventory = "INSERT INTO inventory (prod_id, inv_item_qty) VALUES ($productId, $requestedQty)";
                        $conn->query($insertInventory);
                    }
                }
            }

            echo "<script>alert('Record received successfully');</script>";
        } else {
            echo "Error updating record: " . $conn->error;
        }
    } elseif (isset($_POST["remove"])) {
        // Update req_stat to "REMOVED"
        $updateQuery = "UPDATE requisition SET req_stat = 'REMOVED' WHERE req_id = $reqId";

        // Run the query and check for errors
        if ($conn->query($updateQuery) === TRUE) {
            echo "<script>alert('Record removed successfully');</script>";
        } else {
            echo "Error updating record: " . $conn->error;
        }
    }
}

// Fetch supplier IDs and names from the supplier table
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
?>

<html>
<head>
    <?php include "Style.php"; ?>
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
                <h2>Requisition Table</h2>

                <table border="1">
                    <tr>
                        <th>Requisition ID</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Employee ID</th>
                        <th>Supplier Name</th>
                        <th>Action</th>
                    </tr>

                    <?php
                    // Fetch requisition records excluding those with req_stat as "REMOVED"
                    $sqlRequisitions = "SELECT * FROM requisition WHERE req_stat != 'REMOVED'";
                    $resultRequisitions = $conn->query($sqlRequisitions);

                    if ($resultRequisitions->num_rows > 0) {
                        while ($rowRequisition = $resultRequisitions->fetch_assoc()) {
                            // Output requisition data
                            echo "<tr>";
                            echo "<td>{$rowRequisition['req_id']}</td>";
                            echo "<td>{$rowRequisition['req_stat']}</td>";
                            echo "<td>{$rowRequisition['req_date']}</td>";
                            echo "<td>{$rowRequisition['emp_id']}</td>";

                            // Fetch supplier name based on sup_id
                            $supId = $rowRequisition['sup_id'];
                            $supplierName = "N/A";

                            foreach ($supplierData as $supplier) {
                                if ($supplier['sup_id'] == $supId) {
                                    $supplierName = $supplier['sup_name'];
                                    break;
                                }
                            }

                            echo "<td>$supplierName</td>";

                            // Action buttons
                            echo "<td>";
                            if ($rowRequisition['req_stat'] == "PENDING") {
                                echo "<input type='hidden' name='req_id' value='{$rowRequisition['req_id']}'>";
                                echo "<button type='submit' name='approve' value='1'>Approve</button>";
                                echo "<button type='submit' name='remove' value='1'>Remove</button>";
                            } elseif ($rowRequisition['req_stat'] == "APPROVED") {
                                echo "<input type='hidden' name='req_id' value='{$rowRequisition['req_id']}'>";
                                echo "<button type='submit' name='receive' value='1'>Receive</button>";
                                echo "<button type='submit' name='remove' value='1'>Remove</button>";
                            } else {
                                echo "DONE";
                            }
                            echo "</td>";

                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6'>No requisitions found</td></tr>";
                    }
                    ?>
                </table>
            </div>
        </div>
    </form>

    <script>
        // JavaScript function to set the requisition ID and submit the form for approval
        function approveRequisition(reqId) {
            document.getElementById("req_id").value = reqId;
            document.forms[0].submit();
        }
        <?php include "Script.php"; ?>
    </script>
</body>
</html>
