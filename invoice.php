<?php
// Include database connection
include 'db_connect.php';

// Generate a unique invoice number (based on timestamp)
function generateInvoiceNumber() {
    return 'INV-' . date('YmdHis'); // Format: INV-YYYYMMDDHHMMSS
}

// Handle form submission for adding a new invoice
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $client_id = $_POST['client_id'];
    $invoice_date = date('Y-m-d'); // Automatically set the current date
    $invoice_number = generateInvoiceNumber(); // Auto-generated invoice number
    $total_amount = 0;

    // Insert the invoice into the database
    $sql = "INSERT INTO invoices (client_id, invoice_date, invoice_number, total_amount) VALUES ('$client_id', '$invoice_date', '$invoice_number', '$total_amount')";
    if ($conn->query($sql) === TRUE) {
        $invoice_id = $conn->insert_id;

        // Add invoice items based on form data
        foreach ($_POST['products'] as $product_id => $details) {
            // Ensure quantity and price are numeric before multiplying
            $quantity = (int) $details['quantity'];  // Convert to integer
            $price = (float) $details['price'];  // Convert to float
            $total = $quantity * $price;  // Perform multiplication as numeric types
            $total_amount += $total;

            // Insert each item into the invoice_items table
            $conn->query("INSERT INTO invoice_items (invoice_id, product_id, quantity, price) VALUES ('$invoice_id', '$product_id', '$quantity', '$price')");
        }

        // Calculate VAT (16%) and grand total
        $vat = 0.16 * $total_amount;
        $grand_total = $total_amount + $vat;

        // Update the total amount and VAT for the invoice
        $conn->query("UPDATE invoices SET total_amount = '$grand_total', vat = '$vat' WHERE id = '$invoice_id'");

        echo "Invoice created successfully with Invoice Number: $invoice_number, Total: KES $grand_total (including VAT of KES $vat)!";
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
    <title>Invoice Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h2>Invoice Management</h2>

    <!-- Form to create a new invoice -->
    <form action="invoice.php" method="POST">
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
            <label for="invoiceDate" class="form-label">Invoice Date</label>
            <input type="date" class="form-control" id="invoiceDate" name="invoice_date" value="<?php echo date('Y-m-d'); ?>" readonly>
        </div>

        <h4>Invoice Items</h4>
        <div id="invoice-items">
            <div class="mb-3 row">
                <div class="col-md-4">
                    <label for="product" class="form-label">Product</label>
                    <select class="form-control" name="products[0][product_id]" required>
                        <?php
                        // Fetch products from the database
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
                    <input type="number" class="form-control" name="products[0][quantity]" required>
                </div>
                <div class="col-md-3">
                    <label for="price" class="form-label">Price</label>
                    <input type="text" class="form-control" name="products[0][price]" readonly>
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-danger mt-4 remove-item">Remove</button>
                </div>
            </div>
        </div>

        <button type="button" class="btn btn-secondary" id="add-item">Add Another Item</button>
        <button type="submit" class="btn btn-primary">Create Invoice</button>
    </form>

    <hr>

    <!-- Display the list of invoices -->
    <h3>Invoices</h3>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Client</th>
                <th>Date</th>
                <th>Total Amount</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Fetch invoices from the database
            $invoice_result = $conn->query("
                SELECT invoices.id, clients.client_name, invoices.invoice_date, invoices.total_amount
                FROM invoices
                JOIN clients ON invoices.client_id = clients.id
            ");
            if ($invoice_result->num_rows > 0) {
                while ($invoice = $invoice_result->fetch_assoc()) {
                    echo "<tr>
                        <td>{$invoice['client_name']}</td>
                        <td>{$invoice['invoice_date']}</td>
                        <td>KES {$invoice['total_amount']}</td>
                        <td>
                            <a href='edit_invoice.php?id={$invoice['id']}' class='btn btn-warning btn-sm'>Edit</a>
                            <a href='delete_invoice.php?id={$invoice['id']}' class='btn btn-danger btn-sm' onclick=\"return confirm('Are you sure you want to delete this invoice?');\">Delete</a>
                        </td>
                    </tr>";
                }
            } else {
                echo "<tr><td colspan='4'>No invoices found</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    let itemIndex = 1;

    // Add new item to invoice form
    document.getElementById('add-item').addEventListener('click', function () {
        let newItem = `
            <div class="mb-3 row">
                <div class="col-md-4">
                    <label for="product" class="form-label">Product</label>
                    <select class="form-control" name="products[${itemIndex}][product_id]" required>
                        <?php
                        // Fetch products for the additional item
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
