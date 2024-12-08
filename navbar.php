<?php
// Start session to access session variables
session_start();
?>

<div class="navbar">
    <ul>
        <li><img src="logo_text.png" alt="RCH Logo"></li>
        <li>POS System</li>
    </ul>
    <div class="navbar-date-time">
        <span id="currentDate"><?php echo date('F j, Y'); ?></span>
        <span id="currentTime"><?php echo date('h:i:s A'); ?></span>
    </div>
    <div class="pfp-dropdown">
        <a href="" class="dropdown-toggle">
            <i class="fi fi-ss-user"></i>
            <?php
            // Check if session is started and user is logged in
            if (isset($_SESSION['username'])) {
                echo $_SESSION['username'];  // Display the logged-in username
            } else {
                echo "Guest";  // Default if no user is logged in
            }
            ?>
            <i class="fi fi-ss-angle-small-down"></i>
        </a>
        <ul class="pfp-menu">
            <li><a href="profilepage.php">Profile</a></li>
            <li><a href="logout.php">Logout</a></li> <!-- Changed to logout.php for better practice -->
        </ul>
    </div>
</div>
