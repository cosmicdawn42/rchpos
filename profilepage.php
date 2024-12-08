<?php
session_start();
//$conn = new mysqli('localhost', 'root', '', 'rch_db');
$conn = new mysqli('sql.freedb.tech', 'freedb_etheria2024', 'EXH$fvdNh78zv*J', 'freedb_rch_db');

// Check for valid session
if (!isset($_SESSION['username']) || !isset($_SESSION['user_type'])) {
    header('Location: index.php');
    exit;
}

$username = $_SESSION['username'];
$userType = $_SESSION['user_type']; // Either 'admin' or 'user'

// Query based on user type (admin or regular user)
if ($userType === 'admin') {
    $query = $conn->prepare("SELECT * FROM admintbl WHERE username = ?");
} elseif ($userType === 'user') {
    $query = $conn->prepare("SELECT * FROM usertbl WHERE username = ?");
} else {
    echo "Error: Invalid user type.";
    exit;
}

$query->bind_param('s', $username);
$query->execute();
$result = $query->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc(); // Fetch user data
} else {
    echo "Error: User not found.";
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Page</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to your CSS file -->
</head>
<body>
    <?php include 'header.php'; ?>
    <?php include 'navbar.php'; ?>
    <?php include 'sidebar.php'; ?>

    <div class="profile-container">
    <h2>My Profile</h2>
    <div class="profile-field">
        <label>First Name:</label>
        <p><?php echo htmlspecialchars($user['firstname']); ?></p>
    </div>
    <div class="profile-field">
        <label>Last Name:</label>
        <p><?php echo htmlspecialchars($user['lastname']); ?></p>
    </div>
    <div class="profile-field">
        <label>Email:</label>
        <p><?php echo htmlspecialchars($user['email']); ?></p>
    </div>
    <div class="profile-field">
        <label>Username:</label>
        <p><?php echo htmlspecialchars($user['username']); ?></p>
    </div>

    <div class="profile-actions">
        <a href="update_profile.php" class="btn">Update Profile</a>
    </div>
</div>


    <?php include 'footer.php'; ?>
</body>
</html>

<?php
$conn->close();
?>
