<?php
// Include database connection
include 'db_connect.php';

$result = $conn->query("SELECT id, date, description, amount, type FROM ledger ORDER BY date DESC");
$ledger = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $ledger[] = $row;
    }
}

echo json_encode($ledger);
?>
