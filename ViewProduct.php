<?php
include "components/db.php";

// Function to get all products along with their inventory status
function getAllProductsWithInventoryStatus($conn, $search = "") {
    $query = "SELECT product.*, inventory.inv_item_status FROM product
              LEFT JOIN inventory ON product.prod_id = inventory.prod_id";

    // Add search condition if provided
    if (!empty($search)) {
        $query .= " WHERE product.prod_name LIKE '%$search%'";
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
                <input type="text" id="search" name="search" placeholder="Type to search" oninput="this.form.submit()" value="<?php echo $searchTerm; ?>">
            </form>
            <br>

            <table>
                <tr>
                    <th>Product ID</th>
                    <th>Product Name</th>
                    <th>Product Description</th>
                    <th>Product Price</th>
                    <th>Brand</th>
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
                        <td><?php echo isset($row['inv_item_status']) ? $row['inv_item_status'] : 'N/A'; ?></td>
                    </tr>
                    <?php
                }
                ?>
            </table>
        </div>
    </div>
</body>
</html>