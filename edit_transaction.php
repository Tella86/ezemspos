<?php
// Include database connection
include 'db_connect.php';

// Check if a transaction ID is passed
if (isset($_GET['id'])) {
    $transaction_id = $_GET['id'];

    // Fetch transaction details
    $result = $conn->query("SELECT * FROM ledger WHERE id = $transaction_id");
    $transaction = $result->fetch_assoc();
} else {
    echo "No transaction selected!";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $date = $_POST['date'];
    $description = $_POST['description'];
    $amount = $_POST['amount'];
    $type = $_POST['type'];

    // Update transaction
    $stmt = $conn->prepare("UPDATE ledger SET date = ?, description = ?, amount = ?, type = ? WHERE id = ?");
    $stmt->bind_param("ssdsi", $date, $description, $amount, $type, $transaction_id);
    $stmt->execute();

    // Redirect to the ledger page
    header("Location: ledger.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Transaction</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h2>Edit Transaction</h2>
    <form action="" method="POST">
        <div class="mb-3">
            <label for="date" class="form-label">Date</label>
            <input type="date" class="form-control" id="date" name="date" value="<?php echo $transaction['date']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <input type="text" class="form-control" id="description" name="description" value="<?php echo $transaction['description']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="amount" class="form-label">Amount</label>
            <input type="number" class="form-control" id="amount" name="amount" value="<?php echo $transaction['amount']; ?>" step="0.01" required>
        </div>
        <div class="mb-3">
            <label for="type" class="form-label">Type</label>
            <select class="form-control" id="type" name="type" required>
                <option value="Credit" <?php echo $transaction['type'] == 'Credit' ? 'selected' : ''; ?>>Credit</option>
                <option value="Debit" <?php echo $transaction['type'] == 'Debit' ? 'selected' : ''; ?>>Debit</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Update Transaction</button>
        <a href="ledger.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
