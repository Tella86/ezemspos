<?php
include 'db_connect.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch purchase data from the database
    $result = $conn->query("SELECT * FROM purchases WHERE id = $id");
    if ($result->num_rows > 0) {
        $purchase = $result->fetch_assoc();
    } else {
        echo "Purchase not found!";
        exit;
    }
} else {
    echo "Invalid Request!";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $supplier_id = $_POST['supplier_id'];
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    $purchase_date = $_POST['purchase_date'];

    // Calculate the total cost based on product price and quantity
    $product_result = $conn->query("SELECT price FROM products WHERE id = $product_id");
    $product = $product_result->fetch_assoc();
    $total_cost = $product['price'] * $quantity;

    // Update the purchase in the database
    $sql = "UPDATE purchases SET supplier_id='$supplier_id', product_id='$product_id', quantity='$quantity', purchase_date='$purchase_date', total_cost='$total_cost' WHERE id='$id'";

    if ($conn->query($sql) === TRUE) {
        echo "Purchase updated successfully!";
        header("Location: purchase.php");
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
    <title>Edit Purchase</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h2>Edit Purchase</h2>
    <form action="edit_purchase.php?id=<?php echo $id; ?>" method="POST">
        <div class="mb-3">
            <label for="supplier" class="form-label">Supplier</label>
            <select class="form-control" id="supplier" name="supplier_id" required>
                <?php
                $supplier_result = $conn->query("SELECT id, supplier_name FROM suppliers");
                while ($supplier = $supplier_result->fetch_assoc()) {
                    $selected = $supplier['id'] == $purchase['supplier_id'] ? "selected" : "";
                    echo "<option value='{$supplier['id']}' $selected>{$supplier['supplier_name']}</option>";
                }
                ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="product" class="form-label">Product</label>
            <select class="form-control" id="product" name="product_id" required>
                <?php
                $product_result = $conn->query("SELECT id, product_name FROM products");
                while ($product = $product_result->fetch_assoc()) {
                    $selected = $product['id'] == $purchase['product_id'] ? "selected" : "";
                    echo "<option value='{$product['id']}' $selected>{$product['product_name']}</option>";
                }
                ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="quantity" class="form-label">Quantity</label>
            <input type="number" class="form-control" id="quantity" name="quantity" value="<?php echo $purchase['quantity']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="purchaseDate" class="form-label">Purchase Date</label>
            <input type="date" class="form-control" id="purchaseDate" name="purchase_date" value="<?php echo $purchase['purchase_date']; ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Update Purchase</button>
    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
