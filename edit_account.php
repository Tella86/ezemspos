<?php
// Include database connection
include 'db_connect.php';

// Check if an account ID is passed
if (isset($_GET['id'])) {
    $account_id = $_GET['id'];

    // Fetch the existing account
    $result = $conn->query("SELECT * FROM accounts WHERE id = $account_id");
    $account = $result->fetch_assoc();
} else {
    echo "No account selected!";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $account_name = $_POST['account_name'];
    $account_type = $_POST['account_type'];
    $balance = $_POST['balance'];

    // Update the account entry
    $stmt = $conn->prepare("UPDATE accounts SET account_name = ?, account_type = ?, balance = ? WHERE id = ?");
    $stmt->bind_param("ssdi", $account_name, $account_type, $balance, $account_id);
    $stmt->execute();

    // Redirect back to accounts.php
    header("Location: accounts.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Account</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h2>Edit Account</h2>
    <form action="" method="POST">
        <div class="mb-3">
            <label for="account_name" class="form-label">Account Name</label>
            <input type="text" class="form-control" id="account_name" name="account_name" value="<?php echo $account['account_name']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="account_type" class="form-label">Account Type</label>
            <select class="form-control" id="account_type" name="account_type" required>
                <option value="Savings" <?php echo $account['account_type'] == 'Savings' ? 'selected' : ''; ?>>Savings</option>
                <option value="Current" <?php echo $account['account_type'] == 'Current' ? 'selected' : ''; ?>>Current</option>
                <option value="Credit" <?php echo $account['account_type'] == 'Credit' ? 'selected' : ''; ?>>Credit</option>
                <option value="Loan" <?php echo $account['account_type'] == 'Loan' ? 'selected' : ''; ?>>Loan</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="balance" class="form-label">Balance</label>
            <input type="number" step="0.01" class="form-control" id="balance" name="balance" value="<?php echo $account['balance']; ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Update Account</button>
        <a href="accounts.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
