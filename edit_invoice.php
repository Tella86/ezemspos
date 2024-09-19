<?php
// Include database connection
include 'db_connect.php';

// Check if an invoice ID is passed
if (isset($_GET['id'])) {
    $invoice_id = $_GET['id'];

    // Fetch invoice details
    $invoice_result = $conn->query("SELECT * FROM invoices WHERE id = $invoice_id");
    $invoice = $invoice_result->fetch_assoc();

    // Fetch invoice items
    $items_result = $conn->query("SELECT invoice_items.*, products.product_name FROM invoice_items JOIN products ON invoice_items.product_id = products.id WHERE invoice_id = $invoice_id");
} else {
    echo "No invoice selected!";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Invoice</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h2>Edit Invoice</h2>

    <!-- Form to edit an invoice -->
    <form action="update_invoice.php?id=<?php echo $invoice_id; ?>" method="POST">
        <div class="mb-3">
            <label for="client" class="form-label">Client</label>
            <select class="form-control" id="client" name="client_id" required>
                <?php
                // Fetch clients from the database
                $client_result = $conn->query("SELECT id, client_name FROM clients");
                if ($client_result->num_rows > 0) {
                    while ($client = $client_result->fetch_assoc()) {
                        $selected = $client['id'] == $invoice['client_id'] ? "selected" : "";
                        echo "<option value='{$client['id']}' $selected>{$client['client_name']}</option>";
                    }
                }
                ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="invoiceDate" class="form-label">Invoice Date</label>
            <input type="date" class="form-control" id="invoiceDate" name="invoice_date" value="<?php echo $invoice['invoice_date']; ?>" required>
        </div>

        <h4>Invoice Items</h4>
        <div id="invoice-items">
            <?php
            $item_index = 0;
            while ($item = $items_result->fetch_assoc()) {
                ?>
                <div class="mb-3 row">
                    <div class="col-md-4">
                        <label for="product" class="form-label">Product</label>
                        <select class="form-control" name="products[<?php echo $item_index; ?>][product_id]" required>
                            <?php
                            // Fetch products from the database
                            $product_result = $conn->query("SELECT id, product_name, price FROM products");
                            if ($product_result->num_rows > 0) {
                                while ($product = $product_result->fetch_assoc()) {
                                    $selected = $product['id'] == $item['product_id'] ? "selected" : "";
                                    echo "<option value='{$product['id']}' $selected>{$product['product_name']} - \${$product['price']}</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="quantity" class="form-label">Quantity</label>
                        <input type="number" class="form-control" name="products[<?php echo $item_index; ?>][quantity]" value="<?php echo $item['quantity']; ?>" required>
                    </div>
                    <div class="col-md-3">
                        <label for="price" class="form-label">Price</label>
                        <input type="text" class="form-control" name="products[<?php echo $item_index; ?>][price]" value="<?php echo $item['price']; ?>" readonly>
                    </div>
                    <div class="col-md-2">
                        <button type="button" class="btn btn-danger mt-4 remove-item">Remove</button>
                    </div>
                </div>
                <?php
                $item_index++;
            }
            ?>
        </div>

        <button type="button" class="btn btn-secondary" id="add-item">Add Another Item</button>
        <button type="submit" class="btn btn-primary">Update Invoice</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    let itemIndex = <?php echo $item_index; ?>;

    // Add new item to invoice form
    document.getElementById('add-item').addEventListener('click', function () {
        let newItem = `
            <div class="mb-3 row">
                <div class="col-md-4">
                    <label for="product" class="form-label">Product</label>
                    <select class="form-control" name="products[${itemIndex}][product_id]" required>
                        <?php
                        // Fetch products for the new item
                        $product_result = $conn->query("SELECT id, product_name, price FROM products");
                        if ($product_result->num_rows > 0) {
                            while ($product = $product_result->fetch_assoc()) {
                                echo "<option value='{$product['id']}' data-price='{$product['price']}'>{$product['product_name']} - \${$product['price']}</option>";
                            }
                        }
                        ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="quantity" class="form-label">Quantity</label>
                    <input type="number" class="form-control" name="products[${itemIndex}][quantity]" required>
                </div>
                <div class="col-md-3">
                    <label for="price" class="form-label">Price</label>
                    <input type="text" class="form-control" name="products[${itemIndex}][price]" readonly>
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-danger mt-4 remove-item">Remove</button>
                </div>
            </div>`;
        document.getElementById('invoice-items').insertAdjacentHTML('beforeend', newItem);
        itemIndex++;
    });

    // Remove an item from the invoice form
    document.getElementById('invoice-items').addEventListener('click', function (e) {
        if (e.target.classList.contains('remove-item')) {
            e.target.closest('.row').remove();
        }
    });
</script>
</body>
</html>
