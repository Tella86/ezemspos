<?php
// Include the database connection file
include 'db_connect.php';

// Handle form submission for adding a new client
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $client_name = $_POST['client_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];

    // Insert new client into the database
    $sql = "INSERT INTO clients (client_name, email, phone, address) VALUES ('$client_name', '$email', '$phone', '$address')";
    
    if ($conn->query($sql) === TRUE) {
        echo "New client added successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container">
    <h2>Client Management</h2>

    <!-- Form to add a new client -->
    <form action="clients.php" method="POST">
        <div class="mb-3">
            <label for="clientName" class="form-label">Client Name</label>
            <input type="text" class="form-control" id="clientName" name="client_name" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <div class="mb-3">
            <label for="phone" class="form-label">Phone</label>
            <input type="text" class="form-control" id="phone" name="phone">
        </div>
        <div class="mb-3">
            <label for="address" class="form-label">Address</label>
            <textarea class="form-control" id="address" name="address"></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Add Client</button>
    </form>

    <hr>

    <!-- Table to display clients -->
    <h3>Client List</h3>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Client Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Address</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Fetch clients from the database
            $result = $conn->query("SELECT * FROM clients");
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                        <td>{$row['client_name']}</td>
                        <td>{$row['email']}</td>
                        <td>{$row['phone']}</td>
                        <td>{$row['address']}</td>
                        <td>
                            <a href='edit_client.php?id={$row['id']}' class='btn btn-warning btn-sm'>Edit</a>
                            <a href='delete_client.php?id={$row['id']}' class='btn btn-danger btn-sm' onclick=\"return confirm('Are you sure you want to delete this client?');\">Delete</a>
                        </td>
                    </tr>";
                }
            } else {
                echo "<tr><td colspan='5'>No clients found</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
