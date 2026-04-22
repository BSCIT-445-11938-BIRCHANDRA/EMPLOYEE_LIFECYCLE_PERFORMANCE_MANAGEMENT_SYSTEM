<?php
/**
 * Test Notification System
 * This script tests the complete notification functionality
 */

// Include notification helper
require_once 'backend/notification_helper.php';

// Include database connection
require_once 'backend/db.php';

echo "<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Notification System Test - EMS</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
        .container { max-width: 1000px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { color: #333; text-align: center; margin-bottom: 30px; }
        .section { margin-bottom: 30px; padding: 20px; border: 1px solid #ddd; border-radius: 8px; }
        .section h2 { color: #555; margin-bottom: 15px; }
        .success { color: #28a745; font-weight: bold; }
        .error { color: #dc3545; font-weight: bold; }
        .info { color: #17a2b8; }
        .warning { color: #ffc107; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: bold; }
        .form-group input, .form-group select { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; }
        .btn { background: #007bff; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; }
        .btn:hover { background: #0056b3; }
        .btn-success { background: #28a745; }
        .btn-success:hover { background: #1e7e34; }
        .btn-warning { background: #ffc107; color: #212529; }
        .btn-warning:hover { background: #e0a800; }
        .result { margin-top: 15px; padding: 10px; border-radius: 5px; }
        .result.success { background: #d4edda; border: 1px solid #c3e6cb; }
        .result.error { background: #f8d7da; border: 1px solid #f5c6cb; }
        table { width: 100%; border-collapse: collapse; margin: 15px 0; }
        th, td { padding: 10px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #f8f9fa; }
        .notification-stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin: 20px 0; }
        .stat-card { background: #f8f9fa; padding: 20px; border-radius: 8px; text-align: center; }
        .stat-number { font-size: 32px; font-weight: bold; color: #007bff; }
        .stat-label { color: #666; margin-top: 5px; }
    </style>
</head>
<body>
    <div class='container'>
        <h1>🔔 Notification System Test</h1>
        <p class='info'>Test the complete notification functionality of the Employee Management System.</p>";

// Handle test form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $test_type = $_POST['test_type'] ?? '';
    $user_id = $_POST['user_id'] ?? '';
    $message = $_POST['message'] ?? '';
    
    if (empty($user_id) || !is_numeric($user_id)) {
        echo "<div class='result error'>Please provide a valid user ID.</div>";
    } else {
        $user_id = (int)$user_id;
        
        switch ($test_type) {
            case 'create_notification':
                if (empty($message)) {
                    echo "<div class='result error'>Please provide a notification message.</div>";
                } else {
                    $result = createNotification($user_id, $message, 'test');
                    if ($result) {
                        echo "<div class='result success'>✅ Notification created successfully for user: $user_id</div>";
                    } else {
                        echo "<div class='result error'>❌ Failed to create notification</div>";
                    }
                }
                break;
                
            case 'task_assignment':
                $task_title = $_POST['task_title'] ?? 'Test Task';
                $result = createTaskAssignmentNotification($user_id, $task_title);
                if ($result) {
                    echo "<div class='result success'>✅ Task assignment notification sent to user: $user_id</div>";
                } else {
                    echo "<div class='result error'>❌ Failed to create task assignment notification</div>";
                }
                break;
                
            case 'leave_status':
                $status = $_POST['status'] ?? 'approved';
                $leave_type = $_POST['leave_type'] ?? 'Sick Leave';
                $result = createLeaveStatusNotification($user_id, $status, $leave_type);
                if ($result) {
                    echo "<div class='result success'>✅ Leave status notification sent to user: $user_id</div>";
                } else {
                    echo "<div class='result error'>❌ Failed to create leave status notification</div>";
                }
                break;
                
            case 'mark_read':
                $notification_id = $_POST['notification_id'] ?? '';
                if (!empty($notification_id) && is_numeric($notification_id)) {
                    $result = markNotificationAsRead((int)$notification_id, $user_id);
                    if ($result) {
                        echo "<div class='result success'>✅ Notification marked as read</div>";
                    } else {
                        echo "<div class='result error'>❌ Failed to mark notification as read</div>";
                    }
                } else {
                    echo "<div class='result error'>Please provide a valid notification ID.</div>";
                }
                break;
                
            case 'mark_all_read':
                $result = markAllNotificationsAsRead($user_id);
                if ($result) {
                    echo "<div class='result success'>✅ All notifications marked as read for user: $user_id</div>";
                } else {
                    echo "<div class='result error'>❌ Failed to mark all notifications as read</div>";
                }
                break;
        }
    }
}

// Display current notification statistics
echo "
        <div class='section'>
            <h2>📊 Current Notification Statistics</h2>";

try {
    // Get all users to test with
    $users_result = $conn->query("SELECT id, name, email, role FROM users ORDER BY role, name");
    $users = [];
    while ($user = $users_result->fetch_assoc()) {
        $users[] = $user;
    }
    
    if (!empty($users)) {
        echo "<h3>Available Users for Testing:</h3>";
        echo "<table>";
        echo "<tr><th>ID</th><th>Name</th><th>Email</th><th>Role</th><th>Unread Count</th></tr>";
        
        foreach ($users as $user) {
            $unread_count = getUnreadNotificationCount($user['id']);
            $stats = getNotificationStatistics($user['id']);
            
            echo "<tr>";
            echo "<td>" . $user['id'] . "</td>";
            echo "<td>" . htmlspecialchars($user['name']) . "</td>";
            echo "<td>" . htmlspecialchars($user['email']) . "</td>";
            echo "<td>" . htmlspecialchars($user['role']) . "</td>";
            echo "<td><strong>" . $unread_count . "</strong></td>";
            echo "</tr>";
        }
        echo "</table>";
        
        // Show overall statistics
        echo "<div class='notification-stats'>";
        $total_notifications = 0;
        $total_unread = 0;
        
        foreach ($users as $user) {
            $stats = getNotificationStatistics($user['id']);
            $total_notifications += $stats['total'];
            $total_unread += $stats['unread'];
        }
        
        echo "<div class='stat-card'>
            <div class='stat-number'>$total_notifications</div>
            <div class='stat-label'>Total Notifications</div>
        </div>";
        echo "<div class='stat-card'>
            <div class='stat-number'>$total_unread</div>
            <div class='stat-label'>Total Unread</div>
        </div>";
        echo "<div class='stat-card'>
            <div class='stat-number'>" . count($users) . "</div>
            <div class='stat-label'>Total Users</div>
        </div>";
        echo "<div class='stat-card'>
            <div class='stat-number'>" . ($total_notifications > 0 ? round(($total_unread / $total_notifications) * 100, 1) : 0) . "%</div>
            <div class='stat-label'>Unread Percentage</div>
        </div>";
        echo "</div>";
    }
    
} catch (Exception $e) {
    echo "<div class='error'>❌ Error getting statistics: " . $e->getMessage() . "</div>";
}

echo "</div>";

// Test forms
echo "
        <div class='section'>
            <h2>🧪 Test Notification Functions</h2>
            
            <form method='POST' action=''>
                <div class='form-group'>
                    <label for='user_id'>User ID:</label>
                    <input type='number' id='user_id' name='user_id' placeholder='Enter user ID' required>
                </div>
                
                <div class='form-group'>
                    <label>Test Function:</label>
                    <button type='submit' name='test_type' value='create_notification' class='btn'>Create Custom Notification</button>
                    <button type='submit' name='test_type' value='task_assignment' class='btn btn-success'>Test Task Assignment</button>
                    <button type='submit' name='test_type' value='leave_status' class='btn btn-warning'>Test Leave Status</button>
                    <button type='submit' name='test_type' value='mark_all_read' class='btn btn-success'>Mark All Read</button>
                </div>
                
                <div class='form-group'>
                    <label for='message'>Custom Message:</label>
                    <input type='text' id='message' name='message' placeholder='Enter notification message'>
                </div>
                
                <div class='form-group'>
                    <label for='task_title'>Task Title:</label>
                    <input type='text' id='task_title' name='task_title' placeholder='Enter task title' value='Test Task Assignment'>
                </div>
                
                <div class='form-group'>
                    <label for='status'>Leave Status:</label>
                    <select id='status' name='status'>
                        <option value='approved'>Approved</option>
                        <option value='rejected'>Rejected</option>
                    </select>
                </div>
                
                <div class='form-group'>
                    <label for='leave_type'>Leave Type:</label>
                    <select id='leave_type' name='leave_type'>
                        <option value='Sick Leave'>Sick Leave</option>
                        <option value='Casual Leave'>Casual Leave</option>
                        <option value='Annual Leave'>Annual Leave</option>
                    </select>
                </div>
            </form>
        </div>";

// Show recent notifications
echo "
        <div class='section'>
            <h2>📋 Recent Notifications</h2>";

try {
    // Get recent notifications from all users
    $recent_sql = "
        SELECT n.*, u.name, u.email, u.role 
        FROM notifications n 
        JOIN users u ON n.user_id = u.id 
        ORDER BY n.created_at DESC 
        LIMIT 10
    ";
    $recent_result = $conn->query($recent_sql);
    
    if ($recent_result->num_rows > 0) {
        echo "<table>";
        echo "<tr><th>ID</th><th>User</th><th>Message</th><th>Type</th><th>Status</th><th>Created</th></tr>";
        
        while ($notification = $recent_result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $notification['id'] . "</td>";
            echo "<td>" . htmlspecialchars($notification['name']) . " (" . htmlspecialchars($notification['role']) . ")</td>";
            echo "<td>" . htmlspecialchars($notification['message']) . "</td>";
            echo "<td>" . htmlspecialchars($notification['type']) . "</td>";
            echo "<td><span style='color: " . ($notification['status'] === 'unread' ? '#dc3545' : '#28a745') . "'>" . htmlspecialchars($notification['status']) . "</span></td>";
            echo "<td>" . $notification['created_at'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p class='info'>No notifications found.</p>";
    }
    
} catch (Exception $e) {
    echo "<div class='error'>❌ Error getting recent notifications: " . $e->getMessage() . "</div>";
}

echo "</div>";

// System information
echo "
        <div class='section'>
            <h2>🔧 System Information</h2>
            <div class='info-box'>
                <h4>📋 Notification Features Implemented:</h4>
                <ul>
                    <li class='success'>✅ Database table with proper structure</li>
                    <li class='success'>✅ Helper functions for all operations</li>
                    <li class='success'>✅ Task assignment notifications</li>
                    <li class='success'>✅ Leave status notifications</li>
                    <li class='success'>✅ Leave request notifications for admins</li>
                    <li class='success'>✅ Bell icon with unread count</li>
                    <li class='success'>✅ Dropdown notification list</li>
                    <li class='success'>✅ Mark as read/delete functionality</li>
                    <li class='success'>✅ Real-time updates (AJAX)</li>
                    <li class='success'>✅ Full notification pages</li>
                </ul>
            </div>
            
            <div class='info-box'>
                <h4>🚀 Integration Points:</h4>
                <ul>
                    <li><strong>Task Assignment:</strong> backend/admin/assign_task.php</li>
                    <li><strong>Leave Status:</strong> backend/admin/handle_leave.php</li>
                    <li><strong>Leave Requests:</strong> backend/employee/apply_leave.php</li>
                    <li><strong>Admin Sidebar:</strong> frontend/components/admin_sidebar.php</li>
                    <li><strong>Employee Sidebar:</strong> frontend/components/employee_sidebar.php</li>
                </ul>
            </div>
            
            <div class='info-box'>
                <h4>📱 UI Components:</h4>
                <ul>
                    <li><strong>Bell Icon:</strong> Shows unread count with pulse animation</li>
                    <li><strong>Dropdown:</strong> Recent notifications with actions</li>
                    <li><strong>Full Page:</strong> Complete notification management</li>
                    <li><strong>Real-time:</strong> Auto-refresh every 30 seconds</li>
                </ul>
            </div>
        </div>";

echo "
        <div style='text-align: center; margin-top: 30px;'>
            <a href='setup_notifications.php' class='btn btn-success'>Setup Notifications Table</a>
            <a href='frontend/admin/dashboard.php' class='btn'>Admin Dashboard</a>
            <a href='frontend/employee/dashboard.php' class='btn'>Employee Dashboard</a>
        </div>
    </div>
</body>
</html>";

$conn->close();
?>
