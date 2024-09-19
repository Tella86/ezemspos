<?php
include 'db_connect.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch the sale data from the database
    $result = $conn->query("SELECT * FROM sales WHERE id = $id");
    if ($result->num_rows > 0) {
        $sale = $result->fetch_assoc();
    } else {
        echo "Sale not found!";
        exit;
    }
} else {
    echo "Invalid Request!";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_id = $_POST['product_id'];
    $client_id = $_POST['client_id'];
    $quantity = $_POST['quantity'];
    $sale_date = $_POST['sale_date'];

    // Calculate total price based on product price and quantity
    $product_result = $conn->query("SELECT price FROM products WHERE id = $product_id");
    $product = $product_result->fetch_assoc();
    $total_price = $product['price'] * $quantity;

    // Update the sale in the database
    $sql = "UPDATE sales SET product_id='$product_id', client_id='$client_id', quantity='$quantity', sale_date='$sale_date', total_price='$total_price' WHERE id='$id'";
    
    if ($conn->query($sql) === TRUE) {
        echo "Sale updated successfully!";
        header("Location: sales.php");
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
    <title>Edit Sale</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h2>Edit Sale</h2>
    <form action="edit_sale.php?id=<?php echo $id; ?>" method="POST">
        <div class="mb-3">
            <label for="product" class="form-label">Product</label>
            <select class="form-control" id="product" name="product_id" required>
                <?php
                $product_result = $conn->query("SELECT id, product_name FROM products");
                while ($product = $product_result->fetch_assoc()) {
                    $selected = $product['id'] == $sale['product_id'] ? "selected" : "";
                    echo "<option value='{$product['id']}' $selected>{$product['product_name']}</option>";
                }
                ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="client" class="form-label">Client</label>
            <select class="form-control" id="client" name="client_id" required>
                <?php
                $client_result = $conn->query("SELECT id, client_name FROM clients");
                while ($client = $client_result->fetch_assoc()) {
                    $selected = $client['id'] == $sale['client_id'] ? "selected" : "";
                    echo "<option value='{$client['id']}' $selected>{$client['client_name']}</option>";
                }
                ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="quantity" class="form-label">Quantity</label>
            <input type="number" class="form-control" id="quantity" name="quantity" value="<?php echo $sale['quantity']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="saleDate" class="form-label">Sale Date</label>
            <input type="date" class="form-control" id="saleDate" name="sale_date" value="<?php echo $sale['sale_date']; ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Update Sale</button>
    </form>
</div>
</body>
</html>
