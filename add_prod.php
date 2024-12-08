<?php

// Database connection
// $conn = new mysqli('localhost', 'root', '', 'rch_db');
$conn = new mysqli('sql.freedb.tech', 'freedb_etheria2024', 'EXH$fvdNh78zv*J', 'freedb_rch_db');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $prod_name = $_POST['prod-name'];
    $prod_price_hot = $_POST['prod-price-hot'];
    $prod_price_cold = $_POST['prod-price-cold'];
    $prod_qty = $_POST['prod-qty'];

    // Check for duplicate product name
    $check_query = "SELECT * FROM productstbl WHERE product_name = ?";
    $stmt = $conn->prepare($check_query);
    $stmt->bind_param("s", $prod_name);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<script>alert('Failed to add product. Product name already exists.');</script>";
    } else {
        // Insert product
        $insert_query = "INSERT INTO productstbl (product_name, price_hot, price_cold, quantity) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($insert_query);
        $stmt->bind_param("sddi", $prod_name, $prod_price_hot, $prod_price_cold, $prod_qty);

        if ($stmt->execute()) {
            echo "<script>alert('Product added successfully.');</script>";
        } else {
            echo "<script>alert('Failed to add product.');</script>";
        }
    }
    $stmt->close();
}
$conn->close();
?>

<?php
include 'header.php';
include 'navbar.php';
include 'sidebar.php';
?>

    <div class="dashboard-grid">
        <div class="db-content">
            <div class="prod-container">
                <p class="heading">Add New Product</p>
                <hr>
                <form action="" method="POST" class="prod-form">
                    <div class="form-group">
                        <label for="prod-name">Name</label>
                        <input type="text" id="prod-name" name="prod-name" placeholder="Enter product name" required>
                    </div>
                    <div class="form-group price-group">
                        <div>
                            <label for="prod-price-hot">Price (Hot)</label>
                            <input type="number" id="prod-price-hot" name="prod-price-hot" placeholder="Enter product price" required>
                        </div>
                        <div>
                            <label for="prod-price-cold">Price (Cold)</label>
                            <input type="number" id="prod-price-cold" name="prod-price-cold" placeholder="Enter product price" required>
                        </div>
                        <div>
                            <label for="prod-qty">Quantity</label>
                            <input type="number" id="prod-qty" name="prod-qty" placeholder="Enter product quantity" required>
                        </div>
                    </div>
                    <div class="button-group">
                        <button type="submit" class="savebtn" onclick="">Save</button>
                        <button type="button" class="cancelbtn" onclick="handleCancel()">Cancel</button>
                    </div>

                    <script>
                        function handleCancel() {
                        // Clear all input fields
                        document.getElementById('prod-name').value = '';
                        document.getElementById('prod-price-hot').value = '';
                        document.getElementById('prod-price-cold').value = '';
                        document.getElementById('prod-qty').value = '';

                        // Redirect to admin_dashboard.php
                        window.location.href = 'admin_dashboard.php';
                        }
                    </script>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include'footer.php' ?>