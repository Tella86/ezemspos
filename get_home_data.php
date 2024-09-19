<?php
// Include database connection
include 'db_connect.php';

// Total Clients
$clientsResult = $conn->query("SELECT COUNT(*) AS total_clients FROM clients");
$clientsData = $clientsResult->fetch_assoc();

// Total Products
$productsResult = $conn->query("SELECT COUNT(*) AS total_products FROM products");
$productsData = $productsResult->fetch_assoc();

// Total Sales
$salesResult = $conn->query("SELECT COUNT(*) AS total_sales FROM sales");
$salesData = $salesResult->fetch_assoc();

$homeData = [
    'total_clients' => $clientsData['total_clients'] ?? 0,
    'total_products' => $productsData['total_products'] ?? 0,
    'total_sales' => $salesData['total_sales'] ?? 0
];

echo json_encode($homeData);
?>
