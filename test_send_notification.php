<?php
// Test sending notification to employee
include 'backend/db.php';
include 'backend/notification_helper.php';

// Create connection
$conn = new mysqli('localhost', 'root', '', 'ems_db');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get first employee ID
$employee = $conn->query("SELECT id FROM users WHERE role = 'employee' LIMIT 1");
if ($employee->num_rows > 0) {
    $emp_id = $employee->fetch_assoc()['id'];
    
    echo "📤 Sending test notification to employee ID: $emp_id<br>";
    
    // Send test notification
    $result = createNotification($emp_id, "Test notification from admin - This should appear in your notification panel!", "system");
    
    if ($result) {
        echo "✅ Test notification sent successfully!<br>";
        
        // Check if it was inserted
        $check = $conn->prepare("SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC LIMIT 1");
        $check->bind_param("i", $emp_id);
        $check->execute();
        $notification = $check->get_result()->fetch_assoc();
        
        if ($notification) {
            echo "📋 Notification details:<br>";
            echo "ID: " . $notification['id'] . "<br>";
            echo "Message: " . $notification['message'] . "<br>";
            echo "Status: " . $notification['status'] . "<br>";
            echo "Created: " . $notification['created_at'] . "<br>";
            echo "Type: " . $notification['type'] . "<br>";
        }
    } else {
        echo "❌ Failed to send notification<br>";
    }
    
    // Check unread count
    $unread_count = getUnreadNotificationCount($emp_id);
    echo "🔔 Unread count for employee $emp_id: $unread_count<br>";
    
} else {
    echo "❌ No employees found in database<br>";
}

$conn->close();

echo "<br><a href='frontend/employee/dashboard.php'>👤 Go to Employee Dashboard</a> to test notifications";
?>
