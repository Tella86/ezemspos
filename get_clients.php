<?php
// Include database connection
include 'db_connect.php';

$result = $conn->query("SELECT id, client_name AS name, email, phone FROM clients");
$clients = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $clients[] = $row;
    }
}

echo json_encode($clients);
?>
