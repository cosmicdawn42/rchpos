<?php
// Start session
session_start();

// Database connection settings
/*
$servername = "localhost";
$username = "root";        
$password = "";            
$dbname = "rch_db";
*/
// Remote DB
$servername = "sql.freedb.tech";
$username = "freedb_etheria2024";        
$password = 'EXH$fvdNh78zv*J';            
$dbname = "rch_db";

// Connect to MySQL
//$conn = new mysqli($servername, $username, $password, $dbname);
$conn = new mysqli('sql.freedb.tech', 'freedb_etheria2024', 'EXH$fvdNh78zv*J', 'freedb_rch_db');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $inputUsername = $_POST['username'];
    $inputPassword = $_POST['password'];

    // Check the admintbl for admin login
    $sqlAdmin = "SELECT * FROM admintbl WHERE username = ?";
    $stmtAdmin = $conn->prepare($sqlAdmin);
    $stmtAdmin->bind_param("s", $inputUsername);
    $stmtAdmin->execute();
    $resultAdmin = $stmtAdmin->get_result();

    // If found in admintbl, check the password
    if ($resultAdmin->num_rows > 0) {
        $userAdmin = $resultAdmin->fetch_assoc();
        if ($inputPassword === $userAdmin['password']) {
            // Set session username and user type for admin
            $_SESSION['username'] = $userAdmin['username'];
            $_SESSION['user_type'] = 'admin';  // Add this line to set user type
            // Redirect to admin dashboard
            header("Location: admin_dashboard.php");
            exit();
        } else {
            // Display error and stay on index.php
            echo "<script>alert('Invalid username or password.'); window.location.href = 'index.php';</script>";
            exit();
        }
    }

    // If not found in admintbl, check the usertbl for regular users
    $sqlUser = "SELECT * FROM usertbl WHERE username = ?";
    $stmtUser = $conn->prepare($sqlUser);
    $stmtUser->bind_param("s", $inputUsername);
    $stmtUser->execute();
    $resultUser = $stmtUser->get_result();

    // If found in usertbl, check the password
    if ($resultUser->num_rows > 0) {
        $user = $resultUser->fetch_assoc();
        if ($inputPassword === $user['password']) {
            // Set session username and user type for regular user
            $_SESSION['username'] = $user['username'];
            $_SESSION['user_type'] = 'user';  // Add this line to set user type
            // Redirect to main POS
            header("Location: mainpos.php");
            exit();
        } else {
            // Display error and stay on index.php
            echo "<script>alert('Invalid username or password.'); window.location.href = 'index.php';</script>";
            exit();
        }
    } else {
        // Display error and stay on index.php
        echo "<script>alert('Invalid username or password.'); window.location.href = 'index.php';</script>";
        exit();
    }

    $stmtAdmin->close();
    $stmtUser->close();
}

$conn->close();
?>
