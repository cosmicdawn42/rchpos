<?php include 'header.php'; ?>
<?php include 'navbar.php'; ?>
<?php include 'sidebar.php'; ?>

<?php
// Database connection
// $conn = new mysqli('localhost', 'root', '', 'rch_db');
$conn = new mysqli('sql.freedb.tech', 'freedb_etheria2024', 'EXH$fvdNh78zv*J', 'freedb_rch_db');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch total counts
$totalAdminsQuery = "SELECT COUNT(*) AS total_admins FROM admintbl WHERE isActive = 1";
$totalUsersQuery = "SELECT COUNT(*) AS total_users FROM usertbl WHERE isActive = 1";
$totalProductsQuery = "SELECT COUNT(*) AS total_products FROM productstbl";
$todaysOrdersQuery = "SELECT COUNT(*) AS todays_orders FROM orderstbl WHERE DATE(date_created) = CURDATE()";
$totalOrdersQuery = "SELECT COUNT(*) AS total_orders FROM orderstbl";

$totalAdmins = $conn->query($totalAdminsQuery)->fetch_assoc()['total_admins'];
$totalUsers = $conn->query($totalUsersQuery)->fetch_assoc()['total_users'];
$totalProducts = $conn->query($totalProductsQuery)->fetch_assoc()['total_products'];
$todaysOrders = $conn->query($todaysOrdersQuery)->fetch_assoc()['todays_orders'];
$totalOrders = $conn->query($totalOrdersQuery)->fetch_assoc()['total_orders'];

// Prepare data for bar chart
$orderStats = [];
$query = "SELECT DATE(date_created) AS order_date, COUNT(*) AS total_orders 
          FROM orderstbl 
          GROUP BY DATE(date_created)";
$stmt = $conn->prepare($query);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $orderStats[] = $row;
}
$stmt->close();
$conn->close();

// Convert data for JavaScript
$orderDates = json_encode(array_column($orderStats, 'order_date'));
$orderCounts = json_encode(array_column($orderStats, 'total_orders'));
?>

<div class="dashboard-grid">
    <div class="db-content">
        <div class="prod-container">
            <p class="heading">Admin Dashboard</p>
            <hr>
            <div class="stats-grid">
                <div class="stat-item">
                    <p>Total Admins</p>
                    <h3><?php echo $totalAdmins; ?></h3>
                </div>
                <div class="stat-item">
                    <p>Total Users</p>
                    <h3><?php echo $totalUsers; ?></h3>
                </div>
                <div class="stat-item">
                    <p>Total Products</p>
                    <h3><?php echo $totalProducts; ?></h3>
                </div>
                <div class="stat-item">
                    <p>Today's Orders</p>
                    <h3><?php echo $todaysOrders; ?></h3>
                </div>
                <div class="stat-item">
                    <p>Total Orders</p>
                    <h3><?php echo $totalOrders; ?></h3>
                </div>
            </div>
            <hr>
            <div class="chart-container">
                <canvas id="ordersChart"></canvas>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="chart.js"></script>
<script>
    // Pass PHP data to chart.js
    const orderDates = <?php echo $orderDates; ?>;
    const orderCounts = <?php echo $orderCounts; ?>;
</script>

<?php include 'footer.php'; ?>
