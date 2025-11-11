<?php include 'includes/header.php'; ?>

<section class="dashboard">
    <aside class="sidebar">
        <h3>Welcome, User</h3>
        <ul>
            <li><a href="dashboard.php" class="active">Dashboard</a></li>
            <li><a href="send.php">Send Money</a></li>
            <li><a href="#">Transactions</a></li>
            <li><a href="#">Profile</a></li>
            <li><a href="login.php">Logout</a></li>
        </ul>
    </aside>

    <div class="main-content">
        <h2>Dashboard Overview</h2>

        <div class="stats">
            <div class="card">
                <h4>Current Balance</h4>
                <p>$3,250.00</p>
            </div>
            <div class="card">
                <h4>Transfers This Month</h4>
                <p>8 Transactions</p>
            </div>
            <div class="card">
                <h4>Total Sent</h4>
                <p>$12,450.00</p>
            </div>
        </div>

        <div class="quick-send">
            <h3>Quick Send</h3>
            <form method="POST" action="">
                <input type="text" name="recipient" placeholder="Recipient Name" required>
                <input type="text" name="country" placeholder="Country" required>
                <input type="number" name="amount" placeholder="Amount (USD)" required>
                <button type="submit" name="quickSend">Send</button>
            </form>

            <?php
            if (isset($_POST['quickSend'])) {
                $recipient = htmlspecialchars($_POST['recipient']);
                $amount = (float)$_POST['amount'];
                echo "<p class='success'>Quick transfer to $recipient of $$amount initiated successfully!</p>";
            }
            ?>
        </div>

        <div class="recent-transfers">
            <h3>Recent Transfers</h3>
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Recipient</th>
                        <th>Country</th>
                        <th>Amount</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr><td>Nov 5 2025</td><td>Priya Sharma</td><td>UK</td><td>$500</td><td><span class="status success">Completed</span></td></tr>
                    <tr><td>Oct 28 2025</td><td>Michael Chen</td><td>Singapore</td><td>$250</td><td><span class="status pending">Pending</span></td></tr>
                    <tr><td>Oct 10 2025</td><td>Sarah Johnson</td><td>USA</td><td>$1,200</td><td><span class="status success">Completed</span></td></tr>
                </tbody>
            </table>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
