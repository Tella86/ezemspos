<?php
// Include database connection
include 'db_connect.php';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['id'])) {
    $invoice_id = $_GET['id'];

    // Get the updated client ID and invoice date
    $client_id = $_POST['client_id'];
    $invoice_date = $_POST['invoice_date'];

    // Update the invoice details
    $update_invoice_sql = "UPDATE invoices SET client_id = ?, invoice_date = ? WHERE id = ?";
    $stmt = $conn->prepare($update_invoice_sql);
    $stmt->bind_param("isi", $client_id, $invoice_date, $invoice_id);
    $stmt->execute();

    // Delete the old invoice items
    $delete_items_sql = "DELETE FROM invoice_items WHERE invoice_id = ?";
    $stmt = $conn->prepare($delete_items_sql);
    $stmt->bind_param("i", $invoice_id);
    $stmt->execute();

    // Insert the updated items
    if (!empty($_POST['products'])) {
        foreach ($_POST['products'] as $item) {
            $product_id = $item['product_id'];
            $quantity = $item['quantity'];
            $price = $item['price'];

            // Insert updated items into invoice_items table
            $insert_item_sql = "INSERT INTO invoice_items (invoice_id, product_id, quantity, price) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($insert_item_sql);
            $stmt->bind_param("iiid", $invoice_id, $product_id, $quantity, $price);
            $stmt->execute();
        }
    }

    // Redirect to the invoice list page
    header("Location: invoice.php");
    exit();
} else {
    echo "Invalid request!";
}
