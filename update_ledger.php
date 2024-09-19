<?php
// Include database connection
include 'db_connect.php';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['id'])) {
    $id = $_GET['id'];
    $date = $_POST['date'];
    $description = $_POST['description'];
    $amount = $_POST['amount'];
    $type = $_POST['type'];

    // Update ledger entry
    $sql = "UPDATE ledger SET date = ?, description = ?, amount = ?, type = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssdsi", $date, $description, $amount, $type, $id);
    
    if ($stmt->execute()) {
        header("Location: ledger.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>
