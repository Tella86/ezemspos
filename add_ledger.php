<?php
// Include database connection
include 'db_connect.php';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $date = $_POST['date'];
    $description = $_POST['description'];
    $amount = $_POST['amount'];
    $type = $_POST['type'];

    // Insert into ledger table
    $sql = "INSERT INTO ledger (date, description, amount, type) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssds", $date, $description, $amount, $type);
    
    if ($stmt->execute()) {
        header("Location: ledger.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>
