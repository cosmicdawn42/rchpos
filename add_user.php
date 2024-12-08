<?php include'header.php' ?>
<?php include'navbar.php' ?>

<?php include'sidebar.php' ?>

    <div class="dashboard-grid" id="content-grid">
        <div class="db-content">
            <div class="prod-container">
                <p class="heading">Add New User</p>
                <hr>
                <form action="" class="prod-form">
                    <label for="prod-id" >ID</label>
                    <input type="number" require>
                    <label for="prod-name">Name</label>
                    <input type="text" require>
                    <label for="prod-price">Email</label>
                    <input type="email" require>
                    <label for="prod-qty">Phone</label>
                    <input type="number" require>
                    <label for="username">Username</label>
                    <input type="text" require>
                    <label for="password">Password</label>
                    <input type="text" require>
                    </form>
            </div>
        </div>
        <button type="Submit" class="savebtn">Save</button>
    </div>
</div>

<?php include'footer.php' ?>