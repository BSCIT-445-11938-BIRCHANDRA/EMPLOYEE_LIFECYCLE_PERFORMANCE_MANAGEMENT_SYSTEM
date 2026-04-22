<?php
// Test notification system
include 'backend/db.php';

// Create connection
$conn = new mysqli('localhost', 'root', '', 'ems_db');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if notifications table exists
$result = $conn->query("SHOW TABLES LIKE 'notifications'");
if ($result->num_rows > 0) {
    echo "✅ Notifications table exists<br>";
    
    // Check count
    $count = $conn->query("SELECT COUNT(*) as count FROM notifications");
    $row = $count->fetch_assoc();
    echo "📊 Total notifications: " . $row['count'] . "<br>";
    
    // Check unread count
    $unread = $conn->query("SELECT COUNT(*) as count FROM notifications WHERE status = 'unread'");
    $row = $unread->fetch_assoc();
    echo "🔔 Unread notifications: " . $row['count'] . "<br>";
    
    // Show sample notifications
    $sample = $conn->query("SELECT * FROM notifications ORDER BY created_at DESC LIMIT 5");
    if ($sample->num_rows > 0) {
        echo "<h3>Recent Notifications:</h3>";
        while ($row = $sample->fetch_assoc()) {
            echo "📌 User ID: " . $row['user_id'] . " - " . $row['message'] . " (" . $row['status'] . ")<br>";
        }
    } else {
        echo "❌ No notifications found<br>";
    }
} else {
    echo "❌ Notifications table does not exist<br>";
    
    // Create table
    $create = "CREATE TABLE notifications (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        message TEXT NOT NULL,
        type VARCHAR(50) DEFAULT 'general',
        status ENUM('unread', 'read') DEFAULT 'unread',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        INDEX idx_user_status (user_id, status),
        INDEX idx_created_at (created_at)
    )";
    
    if ($conn->query($create)) {
        echo "✅ Created notifications table<br>";
        
        // Insert sample notification
        $insert = "INSERT INTO notifications (user_id, message, type) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($insert);
        $stmt->bind_param("iss", 1, "Welcome to the Employee Management System!", "system");
        if ($stmt->execute()) {
            echo "✅ Inserted sample notification for user ID 1<br>";
        }
    } else {
        echo "❌ Failed to create table: " . $conn->error . "<br>";
    }
}

$conn->close();
?>
