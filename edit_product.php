<?php
// Include the database connection file
include 'db_connect.php';

// Check if ID is set in the URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch product data from the database
    $result = $conn->query("SELECT * FROM products WHERE id = $id");
    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
    } else {
        echo "Product not found!";
        exit;
    }
} else {
    echo "Invalid Request!";
    exit;
}

// Handle form submission to update the product
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_name = $_POST['product_name'];
    $quantity = $_POST['quantity'];
    $price = $_POST['price'];

    // Update the product details in the database
    $sql = "UPDATE products SET product_name='$product_name', quantity='$quantity', price='$price' WHERE id='$id'";
    
    if ($conn->query($sql) === TRUE) {
        echo "Product updated successfully!";
        // Redirect back to inventory.php after update
        header("Location: inventory.php");
        exit;
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h2>Edit Product</h2>
    <form action="edit_product.php?id=<?php echo $id; ?>" method="POST">
        <div class="mb-3">
            <label for="productName" class="form-label">Product Name</label>
            <input type="text" class="form-control" id="productName" name="product_name" value="<?php echo $product['product_name']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="quantity" class="form-label">Quantity</label>
            <input type="number" class="form-control" id="quantity" name="quantity" value="<?php echo $product['quantity']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="price" class="form-label">Price</label>
            <input type="number" step="0.01" class="form-control" id="price" name="price" value="<?php echo $product['price']; ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Update Product</button>
        <a href="inventory.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
