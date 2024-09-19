<?php
// Include database connection
include 'db_connect.php';

// Initialize report data
$reportData = [];
$reportType = '';
$startDate = '';
$endDate = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $reportType = $_POST['report_type'];
    $startDate = $_POST['start_date'];
    $endDate = $_POST['end_date'];

    // Query to fetch data based on report type
    if ($reportType === 'sales') {
        $query = "SELECT * FROM sales WHERE date BETWEEN ? AND ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ss", $startDate, $endDate);
    } elseif ($reportType === 'purchases') {
        $query = "SELECT * FROM purchases WHERE date BETWEEN ? AND ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ss", $startDate, $endDate);
    } elseif ($reportType === 'ledger') {
        $query = "SELECT * FROM ledger WHERE date BETWEEN ? AND ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ss", $startDate, $endDate);
    }
    
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $reportData[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Full Reporting</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h2>Full Reporting</h2>

    <!-- Report Generation Form -->
    <form action="" method="POST" class="mb-4">
        <div class="mb-3">
            <label for="report_type" class="form-label">Select Report Type</label>
            <select class="form-control" id="report_type" name="report_type" required>
                <option value="">-- Select Report Type --</option>
                <option value="sales" <?php echo ($reportType == 'sales') ? 'selected' : ''; ?>>Sales</option>
                <option value="purchases" <?php echo ($reportType == 'purchases') ? 'selected' : ''; ?>>Purchases</option>
                <option value="ledger" <?php echo ($reportType == 'ledger') ? 'selected' : ''; ?>>Ledger</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="start_date" class="form-label">Start Date</label>
            <input type="date" class="form-control" id="start_date" name="start_date" value="<?php echo $startDate; ?>" required>
        </div>
        <div class="mb-3">
            <label for="end_date" class="form-label">End Date</label>
            <input type="date" class="form-control" id="end_date" name="end_date" value="<?php echo $endDate; ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Generate Report</button>
    </form>

    <!-- Report Table -->
    <?php if (!empty($reportData)): ?>
        <h4>Report Results</h4>
        <table class="table table-striped">
            <thead>
                <tr>
                    <?php if ($reportType === 'sales'): ?>
                        <th>Date</th>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Total</th>
                    <?php elseif ($reportType === 'purchases'): ?>
                        <th>Date</th>
                        <th>Supplier</th>
                        <th>Amount</th>
                    <?php elseif ($reportType === 'ledger'): ?>
                        <th>Date</th>
                        <th>Description</th>
                        <th>Amount</th>
                        <th>Type</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($reportData as $data): ?>
                    <tr>
                        <?php if ($reportType === 'sales'): ?>
                            <td><?php echo $data['date']; ?></td>
                            <td><?php echo $data['product_name']; ?></td>
                            <td><?php echo $data['quantity']; ?></td>
                            <td><?php echo $data['total']; ?></td>
                        <?php elseif ($reportType === 'purchases'): ?>
                            <td><?php echo $data['date']; ?></td>
                            <td><?php echo $data['supplier_name']; ?></td>
                            <td><?php echo $data['amount']; ?></td>
                        <?php elseif ($reportType === 'ledger'): ?>
                            <td><?php echo $data['date']; ?></td>
                            <td><?php echo $data['description']; ?></td>
                            <td><?php echo $data['amount']; ?></td>
                            <td><?php echo $data['type']; ?></td>
                        <?php endif; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php elseif ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
        <div class="alert alert-warning">No records found for the selected criteria.</div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
