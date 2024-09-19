<?php
// Include database connection
include 'db_connect.php';

$result = $conn->query("SELECT id, supplier_name AS name, email, phone FROM suppliers");
$suppliers = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $suppliers[] = $row;
    }
}

echo json_encode($suppliers);
?>
