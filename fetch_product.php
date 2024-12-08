<?php
// Database connection
$conn = new mysqli('localhost', 'root', '', 'rch_db'); // Replace with your actual DB credentials

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];

    // Fetch product details
    $query = "SELECT * FROM productstbl WHERE product_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
        echo "
            <div class='form-group'>
                <label for='prod-name'>Product Name</label>
                <input type='text' id='prod-name' name='prod-name' value='" . htmlspecialchars($product['product_name']) . "' readonly>
            </div>
            <div class='form-group price-group' style='margin-top: 15px';>
                <div>
                    <label for='prod-price-hot'>Price (Hot)</label>
                    <input type='number' id='prod-price-hot' name='prod-price-hot' value='" . $product['price_hot'] . "' required>
                </div>
                <div>
                    <label for='prod-price-cold'>Price (Cold)</label>
                    <input type='number' id='prod-price-cold' name='prod-price-cold' value='" . $product['price_cold'] . "' required>
                </div>
                <div>
                    <label for='prod-qty'>Quantity</label>
                    <input type='number' id='prod-qty' name='prod-qty' value='" . $product['quantity'] . "' required>
                </div>
            </div>
        ";
    } else {
        echo "<p>Product not found.</p>";
    }

    $stmt->close();
}

$conn->close();
?>
