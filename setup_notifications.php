<?php
/**
 * Setup Notifications Table
 * This script creates the notifications table for the notification system
 */

// Include database connection
include("backend/db.php");

echo "<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Setup Notifications Table - EMS</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
        .container { max-width: 800px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { color: #333; text-align: center; margin-bottom: 30px; }
        .success { color: #28a745; font-weight: bold; }
        .error { color: #dc3545; font-weight: bold; }
        .info { color: #17a2b8; }
        .sql-box { background: #f8f9fa; padding: 15px; border-radius: 5px; margin: 15px 0; border-left: 4px solid #007bff; }
        pre { background: #f8f9fa; padding: 10px; border-radius: 5px; overflow-x: auto; }
        .btn { background: #007bff; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; text-decoration: none; display: inline-block; margin: 10px 5px; }
        .btn:hover { background: #0056b3; }
        .btn-success { background: #28a745; }
        .btn-success:hover { background: #1e7e34; }
    </style>
</head>
<body>
    <div class='container'>
        <h1>🔔 Setup Notifications Table</h1>
        <p class='info'>This script will create the notifications table for the Employee Management System notification feature.</p>";

try {
    // Check if notifications table already exists
    $table_check = $conn->query("SHOW TABLES LIKE 'notifications'");
    if ($table_check->num_rows > 0) {
        echo "<div class='info'>ℹ️ Notifications table already exists!</div>";
        
        // Show table structure
        $structure = $conn->query("DESCRIBE notifications");
        echo "<h3>Current Table Structure:</h3>";
        echo "<table border='1' style='border-collapse: collapse; width: 100%; margin: 15px 0;'>";
        echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
        while ($row = $structure->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['Field'] . "</td>";
            echo "<td>" . $row['Type'] . "</td>";
            echo "<td>" . $row['Null'] . "</td>";
            echo "<td>" . $row['Key'] . "</td>";
            echo "<td>" . $row['Default'] . "</td>";
            echo "<td>" . $row['Extra'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
        
        // Show sample data
        $sample_data = $conn->query("SELECT * FROM notifications ORDER BY created_at DESC LIMIT 5");
        if ($sample_data->num_rows > 0) {
            echo "<h3>Recent Notifications:</h3>";
            echo "<table border='1' style='border-collapse: collapse; width: 100%; margin: 15px 0;'>";
            echo "<tr><th>ID</th><th>User ID</th><th>Message</th><th>Status</th><th>Created At</th></tr>";
            while ($row = $sample_data->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row['id'] . "</td>";
                echo "<td>" . $row['user_id'] . "</td>";
                echo "<td>" . htmlspecialchars($row['message']) . "</td>";
                echo "<td>" . $row['status'] . "</td>";
                echo "<td>" . $row['created_at'] . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        }
        
        echo "<div style='text-align: center; margin-top: 30px;'>";
        echo "<a href='frontend/admin/dashboard.php' class='btn btn-success'>Go to Admin Dashboard</a>";
        echo "<a href='test_notifications.php' class='btn'>Test Notifications</a>";
        echo "</div>";
        
    } else {
        // Create notifications table
        $create_table_sql = "
        CREATE TABLE notifications (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            message TEXT NOT NULL,
            type VARCHAR(50) DEFAULT 'general',
            status VARCHAR(20) DEFAULT 'unread',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
            INDEX idx_user_status (user_id, status),
            INDEX idx_created_at (created_at)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ";
        
        if ($conn->query($create_table_sql)) {
            echo "<div class='success'>✅ Notifications table created successfully!</div>";
            
            echo "<div class='sql-box'>
                <h4>📋 Table Structure Created:</h4>
                <pre>" . htmlspecialchars($create_table_sql) . "</pre>
            </div>";
            
            // Insert some sample notifications for testing
            $sample_notifications = [
                [1, 'Welcome to the Employee Management System!', 'system'],
                [1, 'New task assigned: Complete project documentation', 'task'],
                [1, 'Your leave request has been approved', 'leave'],
            ];
            
            foreach ($sample_notifications as $notification) {
                $insert_sql = "INSERT INTO notifications (user_id, message, type) VALUES (?, ?, ?)";
                $stmt = $conn->prepare($insert_sql);
                $stmt->bind_param("iss", $notification[0], $notification[1], $notification[2]);
                $stmt->execute();
            }
            
            echo "<div class='success'>✅ Sample notifications inserted for testing!</div>";
            
            // Show notification statistics
            $stats = $conn->query("
                SELECT 
                    COUNT(*) as total,
                    SUM(CASE WHEN status = 'unread' THEN 1 ELSE 0 END) as unread,
                    SUM(CASE WHEN status = 'read' THEN 1 ELSE 0 END) as read_count
                FROM notifications
            ");
            $stats_data = $stats->fetch_assoc();
            
            echo "<h3>📊 Notification Statistics:</h3>";
            echo "<ul>";
            echo "<li><strong>Total Notifications:</strong> " . $stats_data['total'] . "</li>";
            echo "<li><strong>Unread:</strong> " . $stats_data['unread'] . "</li>";
            echo "<li><strong>Read:</strong> " . $stats_data['read_count'] . "</li>";
            echo "</ul>";
            
            echo "<div style='text-align: center; margin-top: 30px;'>";
            echo "<a href='frontend/admin/dashboard.php' class='btn btn-success'>Go to Admin Dashboard</a>";
            echo "<a href='test_notifications.php' class='btn'>Test Notifications</a>";
            echo "</div>";
            
        } else {
            echo "<div class='error'>❌ Error creating notifications table: " . $conn->error . "</div>";
        }
    }
    
} catch (Exception $e) {
    echo "<div class='error'>❌ Database error: " . $e->getMessage() . "</div>";
}

echo "
        <div class='sql-box'>
            <h4>🔧 What This Table Does:</h4>
            <ul>
                <li><strong>id:</strong> Unique notification identifier</li>
                <li><strong>user_id:</strong> Which user should see this notification</li>
                <li><strong>message:</strong> The notification text content</li>
                <li><strong>type:</strong> Category (task, leave, system, etc.)</li>
                <li><strong>status:</strong> unread/read tracking</li>
                <li><strong>created_at:</strong> When notification was created</li>
                <li><strong>updated_at:</strong> Last update timestamp</li>
            </ul>
        </div>
        
        <div class='sql-box'>
            <h4>🚀 Next Steps:</h4>
            <ol>
                <li>Notification system is now ready to use</li>
                <li>Notifications will be created when tasks are assigned</li>
                <li>Notifications will appear for leave requests</li>
                <li>Users will see bell icon with unread count</li>
                <li>Click bell to view and manage notifications</li>
            </ol>
        </div>
    </div>
</body>
</html>";

$conn->close();
?>
