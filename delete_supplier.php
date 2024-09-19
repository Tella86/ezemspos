<?php
// Include database connection
include 'db_connect.php';

$supplier_id = $_GET['id'];
$sql = "DELETE FROM suppliers WHERE id=$supplier_id";

if ($conn->query($sql) === TRUE) {
    header("Location: suppliers.php");
    exit();
} else {
    echo "Error deleting record: " . $conn->error;
}

$conn->close();
?>
