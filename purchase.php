<?php
session_start();
include "components/db.php";


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
        <h2>Approved Requisitions</h2>

        <table border="1">
            <tr>
                <th>Requisition ID</th>
                <th>Status</th>
                <th>Date</th>
                <th>Employee ID</th>
                <th>Supplier Name</th>
                <th>Action</th> <!-- New column for actions -->
            </tr>

            <?php
            // Fetch requisition records with the status "APPROVED"
            $sqlApprovedRequisitions = "SELECT * FROM requisition WHERE req_stat = 'APPROVED'";
            $resultApprovedRequisitions = $conn->query($sqlApprovedRequisitions);

            if ($resultApprovedRequisitions->num_rows > 0) {
                while ($rowApprovedRequisition = $resultApprovedRequisitions->fetch_assoc()) {
                    // Output approved requisition data
                    echo "<tr>";
                    echo "<td>{$rowApprovedRequisition['req_id']}</td>";
                    echo "<td>{$rowApprovedRequisition['req_stat']}</td>";
                    echo "<td>{$rowApprovedRequisition['req_date']}</td>";
                    echo "<td>{$rowApprovedRequisition['emp_id']}</td>";

                    // Fetch supplier name based on sup_id
                    $supId = $rowApprovedRequisition['sup_id'];
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
                    echo "<form method='post' action=''>";
                    echo "<input type='hidden' name='req_id' value='{$rowApprovedRequisition['req_id']}'>";
                    echo "<button type='submit' name='purchase' value='1'>Purchase</button>";
                    echo "</form>";
                    echo "</td>";

                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='6'>No approved requisitions found</td></tr>";
            }
            ?>
        </table>
    </div>
</div>
<?php
// Handle form submission for the Purchase button
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["purchase"])) {
    $reqId = $_POST["req_id"];

    // Update req_stat to "IN TRANSIT"
    $updateQuery = "UPDATE requisition SET req_stat = 'IN TRANSIT' WHERE req_id = $reqId";

    // Run the query and check for errors
    if ($conn->query($updateQuery) === TRUE) {
        echo "<script>alert('Purchase successful');</script>";
    } else {
        echo "Error updating record: " . $conn->error;
    }
}
?>

</body>
</html>