<?php
// Include the database connection file
include 'db_connect.php';

// Add new product to the database
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_name = $_POST['product_name'];
    $quantity = $_POST['quantity'];
    $price = $_POST['price'];

    // Insert product into database
    $sql = "INSERT INTO products (product_name, quantity, price) VALUES ('$product_name', '$quantity', '$price')";
    if ($conn->query($sql) === TRUE) {
        echo "New product added successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            margin-top: 50px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Inventory Management</h2>
    <form id="inventoryForm" action="inventory.php" method="POST">
        <div class="mb-3">
            <label for="productName" class="form-label">Product Name</label>
            <input type="text" class="form-control" id="productName" name="product_name" required>
        </div>
        <div class="mb-3">
            <label for="quantity" class="form-label">Quantity</label>
            <input type="number" class="form-control" id="quantity" name="quantity" required>
        </div>
        <div class="mb-3">
            <label for="price" class="form-label">Price</label>
            <input type="number" step="0.01" class="form-control" id="price" name="price" required>
        </div>
        <button type="submit" class="btn btn-primary">Add Product</button>
    </form>

    <hr>

    <h3>Product List</h3>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Product Name</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
    <?php
    // Fetch products from the database
    $result = $conn->query("SELECT * FROM products");
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                <td>{$row['product_name']}</td>
                <td>{$row['quantity']}</td>
                <td>{$row['price']}</td>
                <td>
                    <a href='edit_product.php?id={$row['id']}' class='btn btn-warning btn-sm'>Edit</a>
                    <a href='delete_product.php?id={$row['id']}' class='btn btn-danger btn-sm' onclick=\"return confirm('Are you sure you want to delete this product?');\">Delete</a>
                </td>
            </tr>";
        }
    } else {
        echo "<tr><td colspan='4'>No products found</td></tr>";
    }
    ?>
</tbody>

    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
