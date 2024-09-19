<div class="container mt-4">
    <h2>Supplier Management</h2>
    <form id="supplierForm" action="add_supplier.php" method="POST">
        <div class="mb-3">
            <label for="supplierName" class="form-label">Supplier Name</label>
            <input type="text" class="form-control" id="supplierName" name="supplier_name">
        </div>
        <div class="mb-3">
            <label for="contact" class="form-label">Contact</label>
            <input type="text" class="form-control" id="contact" name="contact">
        </div>
        <button type="submit" class="btn btn-primary">Add Supplier</button>
    </form>
    <hr>
    <h3>Supplier List</h3>
    <table class="table">
        <thead>
            <tr>
                <th>Supplier Name</th>
                <th>Contact</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <!-- Populate supplier data dynamically using PHP -->
            <?php
            $result = $conn->query("SELECT * FROM suppliers");
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['supplier_name']}</td>
                        <td>{$row['contact']}</td>
                        <td>
                            <a href='edit_supplier.php?id={$row['id']}' class='btn btn-warning'>Edit</a>
                            <a href='delete_supplier.php?id={$row['id']}' class='btn btn-danger'>Delete</a>
                        </td>
                    </tr>";
            }
            ?>
        </tbody>
    </table>
</div>
