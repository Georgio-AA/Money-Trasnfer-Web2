<?php include 'includes/header.php'; ?>

<section class="dashboard">
    <aside class="sidebar">
        <h3>Welcome, User</h3>
        <ul>
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="send.php">Send Money</a></li>
            <li><a href="transactions.php" class="active">Transactions</a></li>
            <li><a href="#">Profile</a></li>
            <li><a href="login.php">Logout</a></li>
        </ul>
    </aside>

    <div class="main-content">
        <h2>Transaction History</h2>

        <!-- Filter / Search Bar -->
        <form class="filter-bar" method="GET" action="">
            <input type="text" name="search" placeholder="Search by recipient or country..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
            <select name="status">
                <option value="">All Status</option>
                <option value="Completed" <?php if(isset($_GET['status']) && $_GET['status'] == 'Completed') echo 'selected'; ?>>Completed</option>
                <option value="Pending" <?php if(isset($_GET['status']) && $_GET['status'] == 'Pending') echo 'selected'; ?>>Pending</option>
                <option value="Failed" <?php if(isset($_GET['status']) && $_GET['status'] == 'Failed') echo 'selected'; ?>>Failed</option>
            </select>
            <button type="submit">Filter</button>
        </form>

        <!-- Transaction Table -->
        <div class="recent-transfers">
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
                    <?php
                    // Dummy data array (in real case, fetch from DB)
                    $transactions = [
                        ["date" => "2025-11-08", "recipient" => "Priya Sharma", "country" => "UK", "amount" => 500, "status" => "Completed"],
                        ["date" => "2025-11-04", "recipient" => "Sarah Johnson", "country" => "USA", "amount" => 1200, "status" => "Pending"],
                        ["date" => "2025-10-28", "recipient" => "Michael Chen", "country" => "Singapore", "amount" => 250, "status" => "Completed"],
                        ["date" => "2025-10-20", "recipient" => "Ali Hassan", "country" => "Lebanon", "amount" => 300, "status" => "Failed"],
                        ["date" => "2025-10-10", "recipient" => "Emma Rossi", "country" => "Italy", "amount" => 450, "status" => "Completed"],
                    ];

                    // Filter logic
                    $search = isset($_GET['search']) ? strtolower($_GET['search']) : '';
                    $filter_status = isset($_GET['status']) ? $_GET['status'] : '';

                    foreach ($transactions as $t) {
                        $match = true;
                        if ($search && !str_contains(strtolower($t['recipient']), $search) && !str_contains(strtolower($t['country']), $search)) {
                            $match = false;
                        }
                        if ($filter_status && $t['status'] != $filter_status) {
                            $match = false;
                        }
                        if ($match) {
                            $color = $t['status'] == "Completed" ? "success" : ($t['status'] == "Pending" ? "pending" : "failed");
                            echo "<tr>
                                    <td>{$t['date']}</td>
                                    <td>{$t['recipient']}</td>
                                    <td>{$t['country']}</td>
                                    <td>\${$t['amount']}</td>
                                    <td><span class='status {$color}'>{$t['status']}</span></td>
                                  </tr>";
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
