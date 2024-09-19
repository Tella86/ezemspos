<?php
include 'db_connect.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "DELETE FROM purchases WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        header("Location: purchase.php");
    } else {
        echo "Error: " . $conn->error;
    }
} else {
    echo "Invalid Request!";
}
?>
