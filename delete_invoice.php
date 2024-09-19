<?php
include 'db_connect.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "DELETE FROM invoices WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        header("Location: invoice.php");
    } else {
        echo "Error: " . $conn->error;
    }
} else {
    echo "Invalid Request!";
}
?>
