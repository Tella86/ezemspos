<?php
// Include database connection
include 'db_connect.php';

if (isset($_GET['id'])) {
    $account_id = $_GET['id'];
    
    // Delete the account
    $stmt = $conn->prepare("DELETE FROM accounts WHERE id = ?");
    $stmt->bind_param("i", $account_id);
    $stmt->execute();
    
    // Redirect back to accounts.php
    header("Location: accounts.php");
    exit();
} else {
    echo "Invalid request!";
}
?>
