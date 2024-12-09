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

// Initialize counts
$totalAdmins = $totalUsers = $totalProducts = $todaysOrders = $totalOrders = 0;

// Fetch total Admin users
$query = "SELECT COUNT(*) AS total FROM admintbl WHERE isAdmin = 1";
$stmt = $conn->prepare($query);
$stmt->execute();
$result = $stmt->get_result();
if ($row = $result->fetch_assoc()) {
    $totalAdmins = $row['total'];
}
$stmt->close();

// Fetch total Users
$query = "SELECT COUNT(*) AS total FROM usertbl WHERE isActive = 1";
$stmt = $conn->prepare($query);
$stmt->execute();
$result = $stmt->get_result();
if ($row = $result->fetch_assoc()) {
    $totalUsers = $row['total'];
}
$stmt->close();

// Fetch total Products
$query = "SELECT COUNT(*) AS total FROM productstbl";
$stmt = $conn->prepare($query);
$stmt->execute();
$result = $stmt->get_result();
if ($row = $result->fetch_assoc()) {
    $totalProducts = $row['total'];
}
$stmt->close();

// Fetch today's order count
$todaysDate = date('Y-m-d');
$query = "SELECT COUNT(*) AS total FROM orderstbl WHERE DATE(date_created) = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('s', $todaysDate);
$stmt->execute();
$result = $stmt->get_result();
if ($row = $result->fetch_assoc()) {
    $todaysOrders = $row['total'];
}
$stmt->close();

// Fetch total order count
$query = "SELECT COUNT(*) AS total FROM orderstbl";
$stmt = $conn->prepare($query);
$stmt->execute();
$result = $stmt->get_result();
if ($row = $result->fetch_assoc()) {
    $totalOrders = $row['total'];
}
$stmt->close();

$conn->close();
?>

<div class="dashboard-grid">
    <div class="db-content">
        <div class="prod-container">
            <p class="heading">Dashboard</p>
            <hr>
            <div class="dashboard-metrics">
                <p>Total Admin Users: <strong><?php echo $totalAdmins; ?></strong></p>
                <p>Total Users: <strong><?php echo $totalUsers; ?></strong></p>
                <p>Total Products: <strong><?php echo $totalProducts; ?></strong></p>
                <p>Today's Order Count: <strong><?php echo $todaysOrders; ?></strong></p>
                <p>Total Order Count: <strong><?php echo $totalOrders; ?></strong></p>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
