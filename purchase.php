<?php
// Include database connection
include 'db_connect.php';

// Handle form submission for adding a new purchase
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $supplier_id = $_POST['supplier_id'];
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    $purchase_date = $_POST['purchase_date'];

    // Calculate the total cost based on product price and quantity
    $product_result = $conn->query("SELECT price FROM products WHERE id = $product_id");
    $product = $product_result->fetch_assoc();
    $total_cost = $product['price'] * $quantity;

    // Insert the new purchase into the database
    $sql = "INSERT INTO purchases (supplier_id, product_id, quantity, purchase_date, total_cost) VALUES ('$supplier_id', '$product_id', '$quantity', '$purchase_date', '$total_cost')";

    if ($conn->query($sql) === TRUE) {
        // Initiate M-Pesa payment after successful insertion
        $response = initiateMPesaPayment($total_cost, 'Purchase from Supplier ID: '.$supplier_id);
        if ($response) {
            echo "Payment initiated. Check your phone for the payment link.";
        } else {
            echo "Failed to initiate payment.";
        }
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

function initiateMPesaPayment($amount, $reference) {
    // M-Pesa API endpoint and credentials
    $url = 'https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest';
    $shortcode = 'Your_Lipa_Na_Mpesa_Shortcode';
    $lipa_na_mpesa_online_key = 'Your_Consumer_Key';
    $lipa_na_mpesa_online_secret = 'Your_Consumer_Secret';

    // Generate access token
    $token = getAccessToken($lipa_na_mpesa_online_key, $lipa_na_mpesa_online_secret);

    // Set headers
    $headers = [
        'Authorization: Bearer ' . $token,
        'Content-Type: application/json'
    ];

    // Prepare the request payload
    $payload = json_encode([
        'BusinessShortCode' => $shortcode,
        'Password' => base64_encode($shortcode . $lipa_na_mpesa_online_key . time()),
        'Timestamp' => time(),
        'TransactionType' => 'CustomerPayBillOnline',
        'Amount' => $amount,
        'PartyA' => 'Your_Phone_Number', // Buyer phone number
        'PartyB' => $shortcode,
        'PhoneNumber' => 'Your_Phone_Number', // Buyer phone number
        'CallBackURL' => 'https://yourcallbackurl.com/callback', // Change to your callback URL
        'AccountReference' => $reference,
        'TransactionDesc' => 'Payment for purchase'
    ]);

    // Initialize cURL
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    
    // Execute cURL request
    $result = curl_exec($ch);
    curl_close($ch);

    return json_decode($result, true);
}

function getAccessToken($key, $secret) {
    $url = 'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';
    $credentials = base64_encode($key . ':' . $secret);

    $headers = [
        'Authorization: Basic ' . $credentials
    ];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
    $result = curl_exec($ch);
    curl_close($ch);

    $response = json_decode($result, true);
    return $response['access_token'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container">
    <h2>Purchase Management</h2>

    <!-- Form to add a new purchase -->
    <form action="purchase.php" method="POST">
        <div class="mb-3">
            <label for="supplier" class="form-label">Supplier</label>
            <select class="form-control" id="supplier" name="supplier_id" required>
                <?php
                // Fetch suppliers from the database
                $supplier_result = $conn->query("SELECT id, supplier_name FROM suppliers");
                if ($supplier_result->num_rows > 0) {
                    while ($supplier = $supplier_result->fetch_assoc()) {
                        echo "<option value='{$supplier['id']}'>{$supplier['supplier_name']}</option>";
                    }
                }
                ?>
            </select>
        </div>
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
            <label for="quantity" class="form-label">Quantity</label>
            <input type="number" class="form-control" id="quantity" name="quantity" required>
        </div>
        <div class="mb-3">
            <label for="purchaseDate" class="form-label">Purchase Date</label>
            <input type="date" class="form-control" id="purchaseDate" name="purchase_date" required>
        </div>
        <button type="submit" class="btn btn-primary">Add Purchase</button>
    </form>

    <hr>

    <!-- Table to display purchases -->
    <h3>Purchase List</h3>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Supplier</th>
                <th>Product</th>
                <th>Quantity</th>
                <th>Total Cost</th>
                <th>Purchase Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Fetch purchases from the database
            $purchase_result = $conn->query("
                SELECT purchases.id, suppliers.supplier_name, products.product_name, purchases.quantity, purchases.total_cost, purchases.purchase_date 
                FROM purchases
                JOIN suppliers ON purchases.supplier_id = suppliers.id
                JOIN products ON purchases.product_id = products.id
            ");
            if ($purchase_result->num_rows > 0) {
                while ($row = $purchase_result->fetch_assoc()) {
                    echo "<tr>
                        <td>{$row['supplier_name']}</td>
                        <td>{$row['product_name']}</td>
                        <td>{$row['quantity']}</td>
                        <td>\${$row['total_cost']}</td>
                        <td>{$row['purchase_date']}</td>
                        <td>
                            <a href='edit_purchase.php?id={$row['id']}' class='btn btn-warning btn-sm'>Edit</a>
                            <a href='delete_purchase.php?id={$row['id']}' class='btn btn-danger btn-sm' onclick=\"return confirm('Are you sure you want to delete this purchase?');\">Delete</a>
                        </td>
                    </tr>";
                }
            } else {
                echo "<tr><td colspan='6'>No purchases found</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
