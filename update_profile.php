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

// Handle profile update form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $username = $_POST['username']; // Ensure username stays the same for the logged-in user

    if ($userType === 'admin') {
        $updateQuery = "UPDATE admintbl SET firstname = ?, lastname = ?, email = ?, username = ? WHERE username = ?";
    } elseif ($userType === 'user') {
        $updateQuery = "UPDATE usertbl SET firstname = ?, lastname = ?, email = ?, username = ? WHERE username = ?";
    }

    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param('sssss', $firstname, $lastname, $email, $username, $username);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "<script>alert('Profile updated successfully!'); window.location.href = 'profilepage.php';</script>";
    } else {
        echo "<script>alert('Error updating profile. Please try again.'); window.location.href = 'update_profile.php';</script>";
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Profile</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php include 'header.php'; ?>
    <?php include 'navbar.php'; ?>
    <?php include 'sidebar.php'; ?>

    <div class="profile-container">
        <h2>Update Profile</h2>
        <form action="update_profile.php" method="POST">
            <div class="profile-field">
                <label for="firstname">First Name:</label>
                <input type="text" id="firstname" name="firstname" value="<?php echo htmlspecialchars($user['firstname']); ?>" required>
            </div>
            <div class="profile-field">
                <label for="lastname">Last Name:</label>
                <input type="text" id="lastname" name="lastname" value="<?php echo htmlspecialchars($user['lastname']); ?>" required>
            </div>
            <div class="profile-field">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
            </div>
            <div class="profile-field">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
            </div>
            <div class="profile-actions">
                <button type="submit">Update Profile</button>
            </div>
        </form>
    </div>

    <?php include 'footer.php'; ?>
</body>
</html>
