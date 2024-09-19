<?php
include 'db_connect.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch client data from the database
    $result = $conn->query("SELECT * FROM clients WHERE id = $id");
    if ($result->num_rows > 0) {
        $client = $result->fetch_assoc();
    } else {
        echo "Client not found!";
        exit;
    }
} else {
    echo "Invalid Request!";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $client_name = $_POST['client_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];

    // Update the client details in the database
    $sql = "UPDATE clients SET client_name='$client_name', email='$email', phone='$phone', address='$address' WHERE id='$id'";
    
    if ($conn->query($sql) === TRUE) {
        echo "Client updated successfully!";
        header("Location: clients.php");
        exit;
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Client</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h2>Edit Client</h2>
    <form action="edit_client.php?id=<?php echo $id; ?>" method="POST">
        <div class="mb-3">
            <label for="clientName" class="form-label">Client Name</label>
            <input type="text" class="form-control" id="clientName" name="client_name" value="<?php echo $client['client_name']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="<?php echo $client['email']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="phone" class="form-label">Phone</label>
            <input type="text" class="form-control" id="phone" name="phone" value="<?php echo $client['phone']; ?>">
        </div>
        <div class="mb-3">
            <label for="address" class="form-label">Address</label>
            <textarea class="form-control" id="address" name="address"><?php echo $client['address']; ?></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Update Client</button>
        <a href="clients.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
