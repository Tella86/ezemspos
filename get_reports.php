<?php
// Include database connection
include 'db_connect.php';

// Total Sales
$salesResult = $conn->query("SELECT SUM(total_price) AS total_sales FROM sales");
$salesData = $salesResult->fetch_assoc();

// Total Purchases
$purchasesResult = $conn->query("SELECT SUM(total_cost) AS total_purchases FROM purchases");
$purchasesData = $purchasesResult->fetch_assoc();

$reports = [
    'total_sales' => $salesData['total_sales'] ?? 0,
    'total_purchases' => $purchasesData['total_purchases'] ?? 0
];

echo json_encode($reports);
?>
