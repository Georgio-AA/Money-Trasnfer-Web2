<?php include 'includes/header.php'; ?>

<section class="dashboard">
    <aside class="sidebar">
        <h3>Welcome, User</h3>
        <ul>
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="send.php">Send Money</a></li>
            <li><a href="transactions.php">Transactions</a></li>
            <li><a href="profile.php" class="active">Profile</a></li>
            <li><a href="login.php">Logout</a></li>
        </ul>
    </aside>

    <div class="main-content">
        <h2>Profile Settings</h2>

        <!-- Account Info -->
        <div class="profile-card">
            <h3>Account Information</h3>
            <form method="POST" action="">
                <label>Full Name:</label>
                <input type="text" name="fullname" value="Michael Chen" required>

                <label>Email Address:</label>
                <input type="email" name="email" value="michael@swiftpay.com" required>

                <label>Phone Number:</label>
                <input type="text" name="phone" value="+44 7123 456789" required>

                <button type="submit" name="updateProfile">Update Profile</button>
            </form>

            <?php
            if (isset($_POST['updateProfile'])) {
                $name = htmlspecialchars($_POST['fullname']);
                $email = htmlspecialchars($_POST['email']);
                $phone = htmlspecialchars($_POST['phone']);
                echo "<p class='success'>Profile updated successfully for $name!</p>";
            }
            ?>
        </div>

        <!-- Change Password -->
        <div class="profile-card">
            <h3>Change Password</h3>
            <form method="POST" action="">
                <label>Current Password:</label>
                <input type="password" name="current" required>

                <label>New Password:</label>
                <input type="password" name="new" required>

                <label>Confirm New Password:</label>
                <input type="password" name="confirm" required>

                <button type="submit" name="changePass">Update Password</button>
            </form>

            <?php
            if (isset($_POST['changePass'])) {
                $new = $_POST['new'];
                $confirm = $_POST['confirm'];

                if ($new !== $confirm) {
                    echo "<p class='error'>New passwords do not match!</p>";
                } else {
                    echo "<p class='success'>Password updated successfully!</p>";
                }
            }
            ?>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
