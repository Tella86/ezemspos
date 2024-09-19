<?php
// Include the database connection file
include 'db_connect.php';

// Check if ID is set in the URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Delete the product from the database
    $sql = "DELETE FROM products WHERE id = $id";
    if ($conn->query($sql) === TRUE) {
        echo "Product deleted successfully!";
        // Redirect back to inventory.php after deletion
        header("Location: inventory.php");
        exit;
    } else {
        echo "Error deleting product: " . $conn->error;
    }
} else {
    echo "Invalid request!";
}
?>
