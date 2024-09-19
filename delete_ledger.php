<?php
// Include database connection
include 'db_connect.php';

// Check if an ID is passed
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Delete entry from ledger table
    $sql = "DELETE FROM ledger WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: ledger.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
} else {
    echo "Invalid request!";
}
?>
