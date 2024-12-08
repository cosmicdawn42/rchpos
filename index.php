<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>RCH POS System</title>
        <link rel="stylesheet" href="styles.css">
    </head>

    <body>
        <div class="container">
            <div class="left-container">
                <img src="logo.png" alt="RCH logo" class="logo-img">
                <p class="logo-subtext">Point of Sales System</p>
            </div>
            <div class="right-container">
                <p class="login-text">Login</p>
                <form action="login_process.php" method="POST">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" required>
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                    <button type="submit">Log in</button>
                </form>
            </div>
        </div>
    </body>
</html>
