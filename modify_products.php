<?php

// Database connection
$conn = new mysqli('localhost', 'root', '', 'rch_db'); // Replace with your actual DB credentials

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize the message variable
$alertMessage = '';

// Handle form submission for updating product
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check if a product is selected
    if (empty($_POST['prod-id'])) {
        $alertMessage = "Please select a product to update.";
    } else {
        // Get form data
        $prod_id = $_POST['prod-id'];
        $prod_name = $_POST['prod-name'];
        $prod_price_hot = $_POST['prod-price-hot'];
        $prod_price_cold = $_POST['prod-price-cold'];
        $prod_qty = $_POST['prod-qty'];

        // Update query
        $update_query = "UPDATE productstbl SET product_name = ?, price_hot = ?, price_cold = ?, quantity = ? WHERE product_id = ?";
        
        $stmt = $conn->prepare($update_query);
        $stmt->bind_param("sdidi", $prod_name, $prod_price_hot, $prod_price_cold, $prod_qty, $prod_id);

        if ($stmt->execute()) {
            // Set success message for JavaScript prompt
            $alertMessage = "Product updated successfully!";
        } else {
            // Set error message for JavaScript prompt
            $alertMessage = "Error updating product: " . $stmt->error;
        }
        $stmt->close();
    }
}

// Fetch all products from the database
$products_query = "SELECT * FROM productstbl";
$products_result = $conn->query($products_query);
?>


<?php
include 'header.php';
include 'navbar.php';
include 'sidebar.php';
?>

<div class="dashboard-grid">
    <div class="db-content">
        <div class="prod-container">
            <p class="heading">Modify Product</p>
            <hr>

            <form action="" method="POST" class="prod-form">
                <!-- Dropdown for selecting a product -->
                <div class="form-group">
                    <label for="prod-id">Select Product</label>
                    <select id="prod-id" name="prod-id" onchange="fetchProductDetails(this.value)">
                        <option value="">-- Select a Product --</option>
                        <?php
                        if ($products_result->num_rows > 0) {
                            while ($row = $products_result->fetch_assoc()) {
                                echo "<option value='" . $row['product_id'] . "'>" . $row['product_name'] . "</option>";
                            }
                        }
                        ?>
                    </select>
                </div>

                <!-- Product Details Section -->
                <div id="product-details">
                    <!-- This section will be dynamically populated -->
                </div>

                <div class="button-group">
                    <button type="submit" class="savebtn">Update</button>
                    <!-- Cancel button with JavaScript redirection -->
                    <button type="button" class="cancelbtn" onclick="window.location.href='admin_dashboard.php';">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Function to fetch and display product details
function fetchProductDetails(productId) {
    if (productId === "") {
        document.getElementById('product-details').innerHTML = "";
        return;
    }

    // Send an AJAX request to fetch product details
    const xhr = new XMLHttpRequest();
    xhr.open("GET", "fetch_product.php?product_id=" + productId, true);
    xhr.onload = function() {
        if (this.status === 200) {
            document.getElementById('product-details').innerHTML = this.responseText;
        }
    };
    xhr.send();
}

// JavaScript to show alert message after form submission
<?php if ($alertMessage): ?>
    alert("<?php echo $alertMessage; ?>");
<?php endif; ?>
</script>

<?php
include 'footer.php';
?>
