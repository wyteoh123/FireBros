<?php
require_once '../php/config.php';
require_once '../php/auth.php';

// Check if admin is logged in
checkAdminLogin();

// Get dashboard statistics
$stats = getDashboardStats($pdo);

// Get recent orders
$recent_orders = getOrders($pdo);
$recent_orders = array_slice($recent_orders, 0, 5); // Get only 5 most recent
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | FireBros Admin</title>
    <link rel="stylesheet" href="../css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
</head>
<body>
    <div class="admin-container">
        <aside class="admin-sidebar">
            <div class="admin-sidebar-header">
                <h2>FireBros</h2>
                <p>Admin Panel</p>
            </div>
            <nav class="admin-nav">
                <ul>
                    <li><a href="dashboard.php" class="active"><i class="fas fa-tachometer-alt"></i> <span>Dashboard</span></a></li>
                    <li><a href="orders.php"><i class="fas fa-shopping-cart"></i> <span>Orders</span></a></li>
                    <li><a href="menu_manage.php"><i class="fas fa-utensils"></i> <span>Menu Management</span></a></li>
                    <li><a href="?logout"><i class="fas fa-sign-out-alt"></i> <span>Logout</span></a></li>
                </ul>
            </nav>
        </aside>
        
        <main class="admin-main">
            <div class="admin-header">
                <h1>Dashboard</h1>
                <div class="admin-user">
                    <img src="https://via.placeholder.com/40" alt="User">
                    <div class="admin-user-info">
                        <h4><?php echo $_SESSION['admin_name']; ?></h4>
                        <p>Administrator</p>
                    </div>
                </div>
            </div>
            
            <div class="admin-cards">
                <div class="admin-card">
                    <div class="admin-card-header">
                        <h3>Total Orders</h3>
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <div class="admin-card-body">
                        <h2><?php echo $stats['total_orders']; ?></h2>
                        <p>All time orders</p>
                    </div>
                </div>
                
                <div class="admin-card">
                    <div class="admin-card-header">
                        <h3>Total Revenue</h3>
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                    <div class="admin-card-body">
                        <h2>$<?php echo number_format($stats['total_revenue'], 2); ?></h2>
                        <p>Completed orders</p>
                    </div>
                </div>
                
                <div class="admin-card">
                    <div class="admin-card-header">
                        <h3>Pending Orders</h3>
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="admin-card-body">
                        <h2><?php echo $stats['pending_orders']; ?></h2>
                        <p>Awaiting processing</p>
                    </div>
                </div>
                
                <div class="admin-card">
                    <div class="admin-card-header">
                        <h3>Menu Items</h3>
                        <i class="fas fa-utensils"></i>
                    </div>
                    <div class="admin-card-body">
                        <h2><?php echo $stats['menu_items']; ?></h2>
                        <p>Available dishes</p>
                    </div>
                </div>
            </div>
            
            <div class="admin-row">
                <div class="admin-col">
                    <div class="admin-card">
                        <div class="admin-card-header">
                            <h3>Sales Overview</h3>
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <div class="admin-card-body">
                            <canvas id="salesChart" height="250"></canvas>
                        </div>
                    </div>
                </div>
                
                <div class="admin-col">
                    <div class="admin-card">
                        <div class="admin-card-header">
                            <h3>Recent Orders</h3>
                            <i class="fas fa-list"></i>
                        </div>
                        <div class="admin-card-body">
                            <div class="admin-table">
                                <table>
                                    <thead>
                                        <tr>
                                            <th>Order ID</th>
                                            <th>Customer</th>
                                            <th>Amount</th>
                                            <th>Status</th>
                                            <th>Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($recent_orders as $order): ?>
                                        <tr>
                                            <td>#<?php echo $order['id']; ?></td>
                                            <td><?php echo sanitize($order['customer_name']); ?></td>
                                            <td>$<?php echo number_format($order['total_amount'], 2); ?></td>
                                            <td>
                                                <span class="status status-<?php echo $order['status']; ?>">
                                                    <?php echo ucfirst($order['status']); ?>
                                                </span>
                                            </td>
                                            <td><?php echo date('M j, Y', strtotime($order['order_date'])); ?></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="../js/admin.js"></script>
</body>
</html>