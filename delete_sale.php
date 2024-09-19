<?php
include 'db_connect.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "DELETE FROM sales WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        header("Location: sales.php");
    } else {
        echo "Error: " . $conn->error;
    }
} else {
    echo "Invalid Request!";
}
?>
