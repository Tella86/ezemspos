<?php
// Include database connection
include 'db_connect.php';

// Fetch accounts
$result = $conn->query("SELECT * FROM accounts ORDER BY account_name ASC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accounts Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h2>Accounts Management</h2>

    <!-- Add Account Form -->
    <form action="add_account.php" method="POST" class="mb-4">
        <div class="mb-3">
            <label for="account_name" class="form-label">Account Name</label>
            <input type="text" class="form-control" id="account_name" name="account_name" required>
        </div>
        <div class="mb-3">
            <label for="account_type" class="form-label">Account Type</label>
            <select class="form-control" id="account_type" name="account_type" required>
                <option value="Savings">Savings</option>
                <option value="Current">Current</option>
                <option value="Credit">Credit</option>
                <option value="Loan">Loan</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="balance" class="form-label">Balance</label>
            <input type="number" step="0.01" class="form-control" id="balance" name="balance" required>
        </div>
        <button type="submit" class="btn btn-primary">Add Account</button>
    </form>

    <!-- Accounts Table -->
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Account Name</th>
                <th>Account Type</th>
                <th>Balance</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                        <td>{$row['account_name']}</td>
                        <td>{$row['account_type']}</td>
                        <td>{$row['balance']}</td>
                        <td>
                            <a href='edit_account.php?id={$row['id']}' class='btn btn-warning btn-sm'>Edit</a>
                            <a href='delete_account.php?id={$row['id']}' class='btn btn-danger btn-sm' onclick=\"return confirm('Are you sure you want to delete this account?');\">Delete</a>
                        </td>
                    </tr>";
                }
            } else {
                echo "<tr><td colspan='4'>No accounts found</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
