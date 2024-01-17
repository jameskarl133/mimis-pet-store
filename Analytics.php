<?php
session_start();
include "components/db.php";

// Fetch supplier IDs and names from the supplier table
$sqlSuppliers = "SELECT sup_id, sup_name FROM supplier";
$resultSuppliers = $conn->query($sqlSuppliers);

// Check if there are results
if ($resultSuppliers->num_rows > 0) {
    // Create an array to store supplier data
    $supplierData = array();

    // Fetch each supplier ID and name and store it in the array
    while ($rowSupplier = $resultSuppliers->fetch_assoc()) {
        $supplierData[$rowSupplier['sup_id']] = $rowSupplier['sup_name'];
    }
} else {
    // Handle the case when there are no suppliers
    $supplierData = array();
}

// Fetch supplier-wise requisition count
$sqlRequisitionCount = "SELECT sup_id, COUNT(*) AS count FROM requisition GROUP BY sup_id";
$resultRequisitionCount = $conn->query($sqlRequisitionCount);

// Create data arrays for Chart.js
$supplierLabels = array();
$requisitionCounts = array();

while ($rowRequisitionCount = $resultRequisitionCount->fetch_assoc()) {
    $supplierLabels[] = $supplierData[$rowRequisitionCount['sup_id']];
    $requisitionCounts[] = $rowRequisitionCount['count'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include "Style.php"; ?>
    
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supplier Requisition Chart</title>
    <!-- Include Chart.js library -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <?php include "header.php"; ?>
<div class="content">
    <div class="container">
        <h2>Order From Supplier</h2>
        <canvas id="requisitionChart" width="400" height="200"></canvas>
    </div>
</div>
    <script>
        // Create a bar chart using Chart.js
        var ctx = document.getElementById('requisitionChart').getContext('2d');
        var chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($supplierLabels); ?>,
                datasets: [{
                    label: 'Order From Supplier Count',
                    data: <?php echo json_encode($requisitionCounts); ?>,
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</body>
</html>
