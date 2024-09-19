<?php
// Example PHP code to calculate totals for dynamic dashboard
include 'db_connect.php';
$totalProducts = $conn->query("SELECT COUNT(*) FROM products")->fetch_row()[0];
$totalSales = $conn->query("SELECT SUM(total_amount) FROM sales")->fetch_row()[0];
?>
<script>
    // Example of how to update dynamic dashboard widgets using JS
    document.getElementById('totalProducts').innerText = <?= $totalProducts ?>;
    document.getElementById('totalSales').innerText = <?= $totalSales ?>;
</script>
