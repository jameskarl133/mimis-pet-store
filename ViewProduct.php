<?php
include "components/db.php";

// Function to get all products along with their inventory status
function getAllProductsWithInventoryStatus($conn, $search = "") {
    $query = "SELECT product.*, inventory.inv_item_status, inventory.inv_item_qty FROM product
              LEFT JOIN inventory ON product.prod_id = inventory.prod_id";

    // Add search condition if provided
    if (!empty($search)) {
        $searchTerm = mysqli_real_escape_string($conn, $search);
        $query .= " WHERE product.prod_name LIKE '%$searchTerm%'";
    }

    $result = mysqli_query($conn, $query);

    if (!$result) {
        die("Error: " . mysqli_error($conn));
    }

    return $result;
}

// Fetch all products with their inventory status
$searchTerm = isset($_POST['search']) ? $_POST['search'] : "";
$productResultWithStatus = getAllProductsWithInventoryStatus($conn, $searchTerm);
?>

<html>
<head>
    <?php include "Style.php"; ?>
</head>
<body>
    <?php include "header.php"; ?>

    <div class="content">
        <div class="container">
            <h2>Products</h2>

            <!-- Search Box -->
            <form action="" method="post">
                <label for="search">Search Product:</label>
                <input type="text" id="search" name="search" placeholder="Type to search" oninput="searchProducts()" value="<?php echo htmlspecialchars($searchTerm, ENT_QUOTES, 'UTF-8'); ?>">
            </form>
            <br>

            <table>
                <tr>
                    <th>Product ID</th>
                    <th>Product Name</th>
                    <th>Product Description</th>
                    <th>Product Price</th>
                    <th>Brand</th>
                    <th>Quantity</th>
                    <th>Inventory Status</th>
                </tr>
                <?php
                while ($row = mysqli_fetch_assoc($productResultWithStatus)) {
                    ?>
                    <tr>
                        <td><?php echo $row['prod_id']; ?></td>
                        <td><?php echo $row['prod_name']; ?></td>
                        <td><?php echo $row['prod_desc']; ?></td>
                        <td><?php echo $row['prod_price']; ?></td>
                        <td><?php echo $row['prod_brand']; ?></td>
                        <td><?php echo isset($row['inv_item_qty']) ? $row['inv_item_qty'] : 'N/A'; ?></td>
                        <td><?php echo isset($row['inv_item_status']) ? $row['inv_item_status'] : 'N/A'; ?></td>
                    </tr>
                    <?php
                }
                ?>
            </table>
        </div>
    </div>

    <script>
        function searchProducts() {
            let input, filter, table, tr, td, i, txtValue;
            input = document.getElementById("search");
            filter = input.value.toUpperCase();
            table = document.querySelector("table");
            tr = table.getElementsByTagName("tr");

            for (i = 0; i < tr.length; i++) {
                // Skip the header row
                if (i === 0) continue;

                let found = false;
                for (td of tr[i].getElementsByTagName("td")) {
                    txtValue = td.textContent || td.innerText;
                    if (txtValue.toUpperCase().indexOf(filter) > -1) {
                        found = true;
                        break;
                    }
                }
                tr[i].style.display = found ? "" : "none";
            }
        }
    </script>
</body>
</html>
