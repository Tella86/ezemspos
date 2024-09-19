<?php
// Include database connection
include 'db_connect.php';

if (isset($_GET['id'])) {
    $transaction_id = $_GET['id'];
    
    // Delete transaction
    $stmt = $conn->prepare("DELETE FROM ledger WHERE id = ?");
    $stmt->bind_param("i", $transaction_id);
    $stmt->execute();
    
    // Redirect to the ledger page
    header("Location: ledger.php");
    exit();
} else {
    echo "Invalid request!";
}
?>
