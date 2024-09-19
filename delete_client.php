<?php
include 'db_connect.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Delete the client from the database
    $sql = "DELETE FROM clients WHERE id = $id";
    if ($conn->query($sql) === TRUE) {
        echo "Client deleted successfully!";
        header("Location: clients.php");
        exit;
    } else {
        echo "Error deleting client: " . $conn->error;
    }
} else {
    echo "Invalid request!";
}
?>
