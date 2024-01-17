<?php
include "components/db.php";

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch top-selling products
$sql = "SELECT product.prod_name, SUM(purchase.pur_qty) AS total_quantity
        FROM purchase
        INNER JOIN product ON purchase.prod_id = product.prod_id
        WHERE purchase.pur_status = 'done'
        GROUP BY product.prod_id
        ORDER BY total_quantity DESC
        LIMIT 5"; // Adjust the LIMIT based on how many top products you want to display

$result = $conn->query($sql);

if (!$result) {
    die("Error executing the query: " . $conn->error);
}

$data = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = array("y" => (int)$row["total_quantity"], "label" => $row["prod_name"]);
    }
}

$conn->close();
?>

<html>
<head>
    <?php include "components/head.php"; ?>
    <script>
    window.onload = function () {
        renderChart(<?php echo json_encode($data); ?>);
    }

    function renderChart(data) {
        var chart = new CanvasJS.Chart("chartContainer", {
            animationEnabled: true,
            theme: "light2",
            title: {
                text: "Top Selling Products"
            },
            axisY: {
                title: "Total Quantity Sold"
            },
            data: [{
                type: "column",
                showInLegend: true,
                legendMarkerColor: "grey",
                legendText: "Quantity",
                dataPoints: data
            }]
        });
        chart.render();
    }
    </script>
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
    <div class = content>
    <div class = container>
    <div id="chartContainer" style="height: 370px; width: 100%;"></div>
    <script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>
</div>
</div>
</body>
</html>
