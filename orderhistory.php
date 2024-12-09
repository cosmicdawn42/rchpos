<?php
include 'header.php';
include 'navbar.php';
include 'sidebar.php';

// Database connection
$conn = new mysqli('localhost', 'root', '', 'rch_db');
//$conn = new mysqli('sql.freedb.tech', 'freedb_etheria2024', 'EXH$fvdNh78zv*J', 'freedb_rch_db');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch orders
$query = "SELECT * FROM orderstbl ORDER BY date_created DESC";
$result = $conn->query($query);
?>

<div class="order-history">
    <h1>Order History</h1>
    <table class="order-table">
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Product Name</th>
                <th>Price</th>
                <th>Qty</th>
                <th>Total</th>
                <th>Cash</th>
                <th>Change</th>
                <th>Date Created</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['order_id']); ?></td>
                        <td><?php echo htmlspecialchars($row['product_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['price']); ?></td>
                        <td><?php echo htmlspecialchars($row['quantity']); ?></td>
                        <td><?php echo htmlspecialchars($row['total_price']); ?></td>
                        <td><?php echo htmlspecialchars($row['cash_given']); ?></td>
                        <td><?php echo htmlspecialchars($row['change']); ?></td>
                        <td><?php echo htmlspecialchars($row['date_created']); ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="8">No orders found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php
$conn->close();
include 'footer.php';
?>
