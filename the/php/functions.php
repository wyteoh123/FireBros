<?php
// Common functions for the application

// Sanitize input data
function sanitize($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

// Redirect to a specific page
function redirect($url) {
    header("Location: $url");
    exit;
}

// Get menu items from database
function getMenuItems($pdo, $category = null) {
    try {
        if ($category) {
            $stmt = $pdo->prepare("SELECT * FROM menu_items WHERE category = ? ORDER BY name");
            $stmt->execute([$category]);
        } else {
            $stmt = $pdo->query("SELECT * FROM menu_items ORDER BY name");
        }
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        // In a real app, you would log this error
        return [];
    }
}

// Get orders from database
function getOrders($pdo, $status = null) {
    try {
        if ($status) {
            $stmt = $pdo->prepare("SELECT * FROM orders WHERE status = ? ORDER BY order_date DESC");
            $stmt->execute([$status]);
        } else {
            $stmt = $pdo->query("SELECT * FROM orders ORDER BY order_date DESC");
        }
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        // In a real app, you would log this error
        return [];
    }
}

// Get order details
function getOrderDetails($pdo, $order_id) {
    try {
        $stmt = $pdo->prepare("
            SELECT od.*, mi.name, mi.price 
            FROM order_details od
            JOIN menu_items mi ON od.item_id = mi.id
            WHERE od.order_id = ?
        ");
        $stmt->execute([$order_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        // In a real app, you would log this error
        return [];
    }
}

// Get dashboard statistics
function getDashboardStats($pdo) {
    $stats = [];
    
    try {
        // Total orders
        $stmt = $pdo->query("SELECT COUNT(*) as total_orders FROM orders");
        $stats['total_orders'] = $stmt->fetchColumn();
        
        // Total revenue
        $stmt = $pdo->query("SELECT SUM(total_amount) as total_revenue FROM orders WHERE status = 'completed'");
        $stats['total_revenue'] = $stmt->fetchColumn() ?: 0;
        
        // Pending orders
        $stmt = $pdo->query("SELECT COUNT(*) as pending_orders FROM orders WHERE status = 'pending'");
        $stats['pending_orders'] = $stmt->fetchColumn();
        
        // Menu items
        $stmt = $pdo->query("SELECT COUNT(*) as menu_items FROM menu_items");
        $stats['menu_items'] = $stmt->fetchColumn();
        
    } catch (PDOException $e) {
        // In a real app, you would log this error
        $stats['error'] = $e->getMessage();
    }
    
    return $stats;
}
?>