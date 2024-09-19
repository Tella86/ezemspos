<?php
// Include database connection
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $account_name = $_POST['account_name'];
    $account_type = $_POST['account_type'];
    $balance = $_POST['balance'];

    // Insert new account into the accounts table
    $stmt = $conn->prepare("INSERT INTO accounts (account_name, account_type, balance) VALUES (?, ?, ?)");
    $stmt->bind_param("ssd", $account_name, $account_type, $balance);
    $stmt->execute();

    // Redirect back to accounts.php
    header("Location: accounts.php");
    exit();
}
?>
