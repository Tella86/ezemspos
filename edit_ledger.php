<?php
// Include database connection
include 'db_connect.php';

// Check if an ID is passed
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $result = $conn->query("SELECT * FROM ledger WHERE id = $id");
    $entry = $result->fetch_assoc();
} else {
    echo "No entry selected!";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Ledger Entry</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h2>Edit Ledger Entry</h2>

    <form action="update_ledger.php?id=<?php echo $id; ?>" method="POST">
        <div class="mb-3">
            <label for="date" class="form-label">Date</label>
            <input type="date" class="form-control" id="date" name="date" value="<?php echo $entry['date']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <input type="text" class="form-control" id="description" name="description" value="<?php echo $entry['description']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="amount" class="form-label">Amount</label>
            <input type="number" step="0.01" class="form-control" id="amount" name="amount" value="<?php echo $entry['amount']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="type" class="form-label">Type</label>
            <select class="form-control" id="type" name="type" required>
                <option value="income" <?php echo ($entry['type'] == 'income') ? 'selected' : ''; ?>>Income</option>
                <option value="expense" <?php echo ($entry['type'] == 'expense') ? 'selected' : ''; ?>>Expense</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Update Entry</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
