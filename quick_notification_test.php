<?php
// Quick test to create and verify notifications
session_start();

echo "<h2>Quick Notification Test</h2>";

// Create test notification
include 'backend/db.php';
$conn = new mysqli('localhost', 'root', '', 'ems_db');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get employee ID
$employee = $conn->query("SELECT id, name FROM users WHERE role = 'employee' LIMIT 1");
if ($employee->num_rows > 0) {
    $emp = $employee->fetch_assoc();
    $emp_id = $emp['id'];
    $emp_name = $emp['name'];
    
    echo "Creating test notification for: $emp_name (ID: $emp_id)<br>";
    
    // Insert test notification directly
    $message = "Test notification from admin at " . date('Y-m-d H:i:s');
    $sql = "INSERT INTO notifications (user_id, message, type, status) VALUES (?, ?, 'system', 'unread')";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $emp_id, $message);
    
    if ($stmt->execute()) {
        echo "Test notification created successfully!<br>";
        
        // Verify it exists
        $check = $conn->prepare("SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC LIMIT 1");
        $check->bind_param("i", $emp_id);
        $check->execute();
        $result = $check->get_result();
        
        if ($result->num_rows > 0) {
            $notification = $result->fetch_assoc();
            echo "<h3>Notification Details:</h3>";
            echo "ID: " . $notification['id'] . "<br>";
            echo "User ID: " . $notification['user_id'] . "<br>";
            echo "Message: " . htmlspecialchars($notification['message']) . "<br>";
            echo "Status: " . $notification['status'] . "<br>";
            echo "Type: " . $notification['type'] . "<br>";
            echo "Created: " . $notification['created_at'] . "<br>";
            
            // Test notification helper function
            echo "<h3>Testing Helper Functions:</h3>";
            
            // Test unread count
            include 'backend/notification_helper.php';
            $unread_count = getUnreadNotificationCount($emp_id);
            echo "Unread count: $unread_count<br>";
            
            // Test get notifications
            $notifications = getUserNotifications($emp_id, 'unread', 5);
            echo "Notifications found: " . count($notifications) . "<br>";
            
            if (!empty($notifications)) {
                foreach ($notifications as $notif) {
                    echo "Message: " . htmlspecialchars($notif['message']) . "<br>";
                }
            } else {
                echo "No notifications returned by helper function<br>";
            }
        } else {
            echo "Error: Notification not found after insert<br>";
        }
    } else {
        echo "Error creating notification: " . $conn->error . "<br>";
    }
} else {
    echo "No employees found<br>";
}

$conn->close();

echo "<hr>";
echo "<a href='frontend/employee/dashboard.php'>Test Employee Dashboard</a>";
echo "<br><a href='frontend/admin/send_notification.php'>Send Real Notification</a>";
?>
