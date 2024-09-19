<?php
// Include the database connection file
include 'db_connect.php';

// Handle form submission for adding a new sale
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_id = $_POST['product_id'];
    $client_id = $_POST['client_id'];
    $quantity = $_POST['quantity'];
    $sale_date = $_POST['sale_date'];

    // Calculate total price based on product price and quantity
    $product_result = $conn->query("SELECT price FROM products WHERE id = $product_id");
    $product = $product_result->fetch_assoc();
    $total_price = $product['price'] * $quantity;

    // Insert new sale into the database
    $sql = "INSERT INTO sales (product_id, client_id, quantity, sale_date, total_price) VALUES ('$product_id', '$client_id', '$quantity', '$sale_date', '$total_price')";
    
    if ($conn->query($sql) === TRUE) {
        echo "New sale added successfully!";
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
    <title>Sales Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container">
    <h2>Sales Management</h2>

    <!-- Form to add a new sale -->
    <form action="sales.php" method="POST">
        <div class="mb-3">
            <label for="product" class="form-label">Product</label>
            <select class="form-control" id="product" name="product_id" required>
                <?php
                // Fetch products from the database
                $product_result = $conn->query("SELECT id, product_name FROM products");
                if ($product_result->num_rows > 0) {
                    while ($product = $product_result->fetch_assoc()) {
                        echo "<option value='{$product['id']}'>{$product['product_name']}</option>";
                    }
                }
                ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="client" class="form-label">Client</label>
            <select class="form-control" id="client" name="client_id" required>
                <?php
                // Fetch clients from the database
                $client_result = $conn->query("SELECT id, client_name FROM clients");
                if ($client_result->num_rows > 0) {
                    while ($client = $client_result->fetch_assoc()) {
                        echo "<option value='{$client['id']}'>{$client['client_name']}</option>";
                    }
                }
                ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="quantity" class="form-label">Quantity</label>
            <input type="number" class="form-control" id="quantity" name="quantity" required>
        </div>
        <div class="mb-3">
            <label for="saleDate" class="form-label">Sale Date</label>
            <input type="date" class="form-control" id="saleDate" name="sale_date" required>
        </div>
        <button type="submit" class="btn btn-primary">Add Sale</button>
    </form>

    <hr>

    <!-- Table to display sales -->
    <h3>Sales List</h3>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Product</th>
                <th>Client</th>
                <th>Quantity</th>
                <th>Total Price</th>
                <th>Sale Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Fetch sales from the database
            $sales_result = $conn->query("
                SELECT sales.id, products.product_name, clients.client_name, sales.quantity, sales.total_price, sales.sale_date 
                FROM sales
                JOIN products ON sales.product_id = products.id
                JOIN clients ON sales.client_id = clients.id
            ");
            if ($sales_result->num_rows > 0) {
                while ($row = $sales_result->fetch_assoc()) {
                    echo "<tr>
                        <td>{$row['product_name']}</td>
                        <td>{$row['client_name']}</td>
                        <td>{$row['quantity']}</td>
                        <td>\${$row['total_price']}</td>
                        <td>{$row['sale_date']}</td>
                        <td>
                            <a href='edit_sale.php?id={$row['id']}' class='btn btn-warning btn-sm'>Edit</a>
                            <a href='delete_sale.php?id={$row['id']}' class='btn btn-danger btn-sm' onclick=\"return confirm('Are you sure you want to delete this sale?');\">Delete</a>
                        </td>
                    </tr>";
                }
            } else {
                echo "<tr><td colspan='6'>No sales found</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
