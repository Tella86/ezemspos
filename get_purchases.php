<?php
// Include database connection
include 'db_connect.php';

$result = $conn->query("SELECT p.id, s.supplier_name AS supplier, prod.product_name AS product, p.quantity, p.purchase_date, p.total_cost
                         FROM purchases p
                         JOIN suppliers s ON p.supplier_id = s.id
                         JOIN products prod ON p.product_id = prod.id");
$purchases = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $purchases[] = $row;
    }
}

echo json_encode($purchases);
?>
