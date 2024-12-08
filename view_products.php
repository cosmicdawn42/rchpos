<?php
// Database connection
$conn = new mysqli('localhost', 'root', '', 'rch_db'); // Replace with your actual DB credentials

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle delete product
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];

    // Delete query
    $delete_query = "DELETE FROM productstbl WHERE product_id = ?";
    $stmt = $conn->prepare($delete_query);
    $stmt->bind_param("i", $delete_id);

    if ($stmt->execute()) {
        // Redirect to the same page to refresh the list after deleting
        header("Location: view_products.php");
        exit;
    } else {
        echo "<p>Error deleting product: " . $stmt->error . "</p>";
    }
    $stmt->close();
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
            <p class="heading">View Products</p>
            <hr>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Product Name</th>
                        <th>Hot Price</th>
                        <th>Cold Price</th>
                        <th>Quantity</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($products_result->num_rows > 0) {
                        while ($row = $products_result->fetch_assoc()) {
                            echo "<tr>
                                    <td>" . $row['product_id'] . "</td>
                                    <td>" . $row['product_name'] . "</td>
                                    <td>" . $row['price_hot'] . "</td>
                                    <td>" . $row['price_cold'] . "</td>
                                    <td>" . $row['quantity'] . "</td>
                                    <td> 
                                        <a href='view_products.php?delete_id=" . $row['product_id'] . "' onclick='return confirm(\"Are you sure you want to delete this product?\")'>Delete</a>
                                    </td>
                                  </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5'>No products found</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php
include 'footer.php';
?>
