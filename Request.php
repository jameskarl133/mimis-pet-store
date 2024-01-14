<?php 
include "components/db.php";

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
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
                </tr>

                <?php
                // Fetch requisition records
                $sqlRequisitions = "SELECT * FROM requisition";
                $resultRequisitions = $conn->query($sqlRequisitions);

                if ($resultRequisitions->num_rows > 0) {
                    while ($rowRequisition = $resultRequisitions->fetch_assoc()) {
                        // Output requisition data
                        echo "<tr>";
                        echo "<td>{$rowRequisition['req_id']}</td>";
                        echo "<td>{$rowRequisition['req_status']}</td>";
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
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>No requisitions found</td></tr>";
                }
                ?>
            </table>
        </div>
    </div>
</form>

</body>
</html>
