<?php
// Debug notification system step by step
session_start();

echo "<h2>🔍 Notification System Debug</h2>";

// Step 1: Check session
echo "<h3>Step 1: Session Check</h3>";
echo "Session status: " . session_status() . "<br>";
echo "Session data: <pre>" . print_r($_SESSION, true) . "</pre><br>";

// Step 2: Check database connection
echo "<h3>Step 2: Database Connection</h3>";
$conn = new mysqli('localhost', 'root', '', 'ems_db');
if ($conn->connect_error) {
    echo "❌ Database connection failed: " . $conn->connect_error . "<br>";
} else {
    echo "✅ Database connection successful<br>";
}

// Step 3: Check notifications table
echo "<h3>Step 3: Notifications Table</h3>";
$result = $conn->query("SHOW TABLES LIKE 'notifications'");
if ($result->num_rows > 0) {
    echo "✅ Notifications table exists<br>";
    
    // Check table structure
    $structure = $conn->query("DESCRIBE notifications");
    echo "<h4>Table Structure:</h4>";
    echo "<table border='1'><tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th></tr>";
    while ($row = $structure->fetch_assoc()) {
        echo "<tr><td>" . $row['Field'] . "</td><td>" . $row['Type'] . "</td><td>" . $row['Null'] . "</td><td>" . $row['Key'] . "</td></tr>";
    }
    echo "</table><br>";
    
    // Check total notifications
    $count = $conn->query("SELECT COUNT(*) as count FROM notifications");
    $row = $count->fetch_assoc();
    echo "📊 Total notifications: " . $row['count'] . "<br>";
    
    // Check unread notifications
    $unread = $conn->query("SELECT COUNT(*) as count FROM notifications WHERE status = 'unread'");
    $row = $unread->fetch_assoc();
    echo "🔔 Unread notifications: " . $row['count'] . "<br>";
    
    // Show recent notifications
    $recent = $conn->query("SELECT * FROM notifications ORDER BY created_at DESC LIMIT 5");
    if ($recent->num_rows > 0) {
        echo "<h4>Recent Notifications:</h4>";
        while ($row = $recent->fetch_assoc()) {
            echo "<div style='border: 1px solid #ddd; padding: 10px; margin: 5px 0;'>";
            echo "<strong>ID:</strong> " . $row['id'] . "<br>";
            echo "<strong>User ID:</strong> " . $row['user_id'] . "<br>";
            echo "<strong>Message:</strong> " . htmlspecialchars($row['message']) . "<br>";
            echo "<strong>Type:</strong> " . $row['type'] . "<br>";
            echo "<strong>Status:</strong> " . $row['status'] . "<br>";
            echo "<strong>Created:</strong> " . $row['created_at'] . "<br>";
            echo "</div>";
        }
    } else {
        echo "❌ No notifications found<br>";
    }
} else {
    echo "❌ Notifications table does not exist<br>";
}

// Step 4: Test notification helper functions
echo "<h3>Step 4: Test Helper Functions</h3>";
if (function_exists('getUnreadNotificationCount')) {
    echo "✅ getUnreadNotificationCount function exists<br>";
    
    // Test with user ID 1
    $count = getUnreadNotificationCount(1);
    echo "Test count for user ID 1: $count<br>";
} else {
    echo "❌ getUnreadNotificationCount function does not exist<br>";
}

if (function_exists('getUserNotifications')) {
    echo "✅ getUserNotifications function exists<br>";
    
    // Test with user ID 1
    $notifications = getUserNotifications(1, 'unread', 5);
    echo "Test notifications for user ID 1: <pre>" . print_r($notifications, true) . "</pre><br>";
} else {
    echo "❌ getUserNotifications function does not exist<br>";
}

// Step 5: Test creating a notification
echo "<h3>Step 5: Test Create Notification</h3>";
if (function_exists('createNotification')) {
    echo "✅ createNotification function exists<br>";
    
    // Test creating notification
    $result = createNotification(1, "Debug test notification", "system");
    if ($result) {
        echo "✅ Test notification created successfully<br>";
    } else {
        echo "❌ Failed to create test notification<br>";
    }
} else {
    echo "❌ createNotification function does not exist<br>";
}

$conn->close();

echo "<hr><h3>🎯 Next Steps:</h3>";
echo "1. Run this debug script to identify issues<br>";
echo "2. If all tests pass, check employee dashboard<br>";
echo "3. If still not working, check browser console for JavaScript errors<br>";
echo "<br><a href='frontend/employee/dashboard.php'>👤 Test Employee Dashboard</a>";
?>
