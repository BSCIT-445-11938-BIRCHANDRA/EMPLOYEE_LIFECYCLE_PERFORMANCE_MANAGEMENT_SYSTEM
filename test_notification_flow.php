<?php
// Complete notification flow test
session_start();

echo "<h2>🔄 Complete Notification Flow Test</h2>";

// Step 1: Check admin session
echo "<h3>Step 1: Check Admin Session</h3>";
if (isset($_SESSION['admin_id'])) {
    echo "✅ Admin session exists: " . $_SESSION['admin_id'] . "<br>";
} else {
    echo "❌ No admin session found<br>";
    echo "<a href='frontend/admin/login.php'>🔑 Admin Login</a><br>";
    exit();
}

// Step 2: Create test notification
echo "<h3>Step 2: Create Test Notification</h3>";
include 'backend/notification_helper.php';

// Get first employee
$conn = new mysqli('localhost', 'root', '', 'ems_db');
$employee = $conn->query("SELECT id, name FROM users WHERE role = 'employee' LIMIT 1");

if ($employee->num_rows > 0) {
    $emp_data = $employee->fetch_assoc();
    $emp_id = $emp_data['id'];
    $emp_name = $emp_data['name'];
    
    echo "📤 Target employee: $emp_name (ID: $emp_id)<br>";
    
    // Create notification
    $message = "Test notification from admin at " . date('Y-m-d H:i:s') . " - This should appear immediately!";
    $result = createNotification($emp_id, $message, "system");
    
    if ($result) {
        echo "✅ Notification created successfully!<br>";
        
        // Verify it was inserted
        $check = $conn->prepare("SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC LIMIT 1");
        $check->bind_param("i", $emp_id);
        $check->execute();
        $notification = $check->get_result()->fetch_assoc();
        
        if ($notification) {
            echo "📋 Notification details:<br>";
            echo "ID: " . $notification['id'] . "<br>";
            echo "User ID: " . $notification['user_id'] . "<br>";
            echo "Message: " . htmlspecialchars($notification['message']) . "<br>";
            echo "Status: " . $notification['status'] . "<br>";
            echo "Type: " . $notification['type'] . "<br>";
            echo "Created: " . $notification['created_at'] . "<br>";
        }
    } else {
        echo "❌ Failed to create notification<br>";
    }
} else {
    echo "❌ No employees found to send notification to<br>";
}

$conn->close();

// Step 3: Test employee notification retrieval
echo "<h3>Step 3: Test Employee Notification Retrieval</h3>";
session_write_close();

// Start new session as employee
session_start();
$_SESSION['employee_id'] = $emp_id;
$_SESSION['user_role'] = 'employee';

echo "👤 Simulating employee session for ID: $emp_id<br>";

// Test notification functions
$unread_count = getUnreadNotificationCount($emp_id);
echo "🔔 Unread count: $unread_count<br>";

$notifications = getUserNotifications($emp_id, 'unread', 5);
echo "📊 Recent notifications: " . count($notifications) . "<br>";

if (!empty($notifications)) {
    foreach ($notifications as $notification) {
        echo "📌 " . htmlspecialchars($notification['message']) . " (" . $notification['status'] . ")<br>";
    }
}

echo "<hr><h3>🎯 Test Results:</h3>";
echo "<strong>1.</strong> <a href='frontend/employee/dashboard.php' target='_blank'>👤 Open Employee Dashboard</a> in new tab<br>";
echo "<strong>2.</strong> Look for notification bell in top-right corner<br>";
echo "<strong>3.</strong> Bell should show badge with count: $unread_count<br>";
echo "<strong>4.</strong> Click bell to see notifications dropdown<br>";
echo "<strong>5.</strong> Check browser console (F12) for JavaScript errors<br>";

echo "<hr><h3>🔧 Manual Test:</h3>";
echo "<a href='frontend/admin/send_notification.php'>📢 Go to Send Notification Page</a><br>";
echo "Send a notification to the same employee and check if it appears immediately<br>";

session_write_close();
?>
