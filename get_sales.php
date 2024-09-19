<?php
// Include database connection
include 'db_connect.php';

$result = $conn->query("SELECT s.id, p.product_name AS product, c.client_name AS client, s.quantity, s.sale_date, s.total_price
                         FROM sales s
                         JOIN products p ON s.product_id = p.id
                         JOIN clients c ON s.client_id = c.id");
$sales = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $sales[] = $row;
    }
}

echo json_encode($sales);
?>
