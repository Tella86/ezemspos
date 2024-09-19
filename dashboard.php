<?php
// Include database connection
include 'db_connect.php';

// Fetch total sales
$totalSalesResult = $conn->query("SELECT SUM(total_price) AS total_sales FROM sales");
$totalSales = $totalSalesResult->fetch_assoc()['total_sales'];

// Fetch total purchases
$totalPurchasesResult = $conn->query("SELECT SUM(total_cost) AS total_purchases FROM purchases");
$totalPurchases = $totalPurchasesResult->fetch_assoc()['total_purchases'];

// Fetch total products
$totalProductsResult = $conn->query("SELECT COUNT(*) AS total_products FROM products");
$totalProducts = $totalProductsResult->fetch_assoc()['total_products'];

// Fetch sales data for the chart
$salesDataResult = $conn->query("SELECT DATE(sale_date) AS sale_date, SUM(total_price) AS daily_sales FROM sales GROUP BY sale_date ORDER BY sale_date");
$salesData = [];
$dates = [];
while ($row = $salesDataResult->fetch_assoc()) {
    $salesData[] = $row['daily_sales'];
    $dates[] = $row['sale_date'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dynamic Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
<div class="container mt-4">
    <h2>Dashboard</h2>

    <div class="row">
        <div class="col-md-3">
            <div class="card text-white bg-primary mb-3">
                <div class="card-body">
                    <h5 class="card-title">Total Sales</h5>
                    <p class="card-text">$<?php echo number_format($totalSales, 2); ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-success mb-3">
                <div class="card-body">
                    <h5 class="card-title">Total Purchases</h5>
                    <p class="card-text">$<?php echo number_format($totalPurchases, 2); ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-warning mb-3">
                <div class="card-body">
                    <h5 class="card-title">Total Products</h5>
                    <p class="card-text"><?php echo $totalProducts; ?></p>
                </div>
            </div>
        </div>
    </div>

    <h4>Sales Over Time</h4>
    <canvas id="salesChart" width="400" height="200"></canvas>
</div>

<script>
    const ctx = document.getElementById('salesChart').getContext('2d');
    const salesChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: <?php echo json_encode($dates); ?>,
            datasets: [{
                label: 'Daily Sales',
                data: <?php echo json_encode($salesData); ?>,
                borderColor: 'rgba(75, 192, 192, 1)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderWidth: 1,
                fill: true
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
