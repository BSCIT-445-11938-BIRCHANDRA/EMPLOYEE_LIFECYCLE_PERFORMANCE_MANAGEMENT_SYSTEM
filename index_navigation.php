<?php
// Navigation Helper - Shows all available pages
echo "<!DOCTYPE html>";
echo "<html><head>";
echo "<title>EMS Project Navigation</title>";
echo "<style>";
echo "body { font-family: Arial, sans-serif; padding: 20px; }";
echo ".section { margin-bottom: 30px; }";
echo ".section h2 { color: #333; border-bottom: 2px solid #007bff; padding-bottom: 10px; }";
echo ".links { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 10px; margin-top: 10px; }";
echo ".link { padding: 15px; background: #f8f9fa; border-radius: 8px; text-decoration: none; color: #007bff; border: 1px solid #dee2e6; transition: all 0.3s; }";
echo ".link:hover { background: #007bff; color: white; transform: translateY(-2px); }";
echo ".admin { background: #fff3cd; border-color: #ffc107; color: #856404; }";
echo ".admin:hover { background: #ffc107; color: white; }";
echo ".employee { background: #d4edda; border-color: #28a745; color: #155724; }";
echo ".employee:hover { background: #28a745; color: white; }";
echo ".test { background: #f8d7da; border-color: #dc3545; color: #721c24; }";
echo ".test:hover { background: #dc3545; color: white; }";
echo "</style>";
echo "</head><body>";

echo "<h1>EMS Project Navigation</h1>";
echo "<p>Current URL: " . $_SERVER['REQUEST_URI'] . "</p>";
echo "<p>Base Path: " . dirname($_SERVER['PHP_SELF']) . "</p>";

// Admin Section
echo "<div class='section'>";
echo "<h2>Admin Panel</h2>";
echo "<div class='links'>";
echo "<a href='frontend/admin/dashboard.php' class='link admin'>Admin Dashboard</a>";
echo "<a href='frontend/admin/employees.php' class='link admin'>Employees</a>";
echo "<a href='frontend/admin/attendance.php' class='link admin'>Attendance</a>";
echo "<a href='frontend/admin/leave_requests.php' class='link admin'>Leave Requests</a>";
echo "<a href='frontend/admin/tasks.php' class='link admin'>Tasks</a>";
echo "<a href='frontend/admin/reports.php' class='link admin'>Reports</a>";
echo "<a href='frontend/admin/send_notification.php' class='link admin'>Send Notification</a>";
echo "<a href='frontend/admin/settings.php' class='link admin'>Settings</a>";
echo "</div></div>";

// Employee Section
echo "<div class='section'>";
echo "<h2>Employee Panel</h2>";
echo "<div class='links'>";
echo "<a href='frontend/employee/dashboard.php' class='link employee'>Employee Dashboard</a>";
echo "<a href='frontend/employee/attendance.php' class='link employee'>Attendance</a>";
echo "<a href='frontend/employee/leave.php' class='link employee'>Leave Request</a>";
echo "<a href='frontend/employee/tasks.php' class='link employee'>Tasks</a>";
echo "<a href='frontend/employee/profile.php' class='link employee'>Profile</a>";
echo "<a href='frontend/employee/notifications.php' class='link employee'>Notifications</a>";
echo "</div></div>";

// Test Section
echo "<div class='section'>";
echo "<h2>Testing & Debug</h2>";
echo "<div class='links'>";
echo "<a href='url_test.php' class='link test'>URL Test</a>";
echo "<a href='debug_notifications.php' class='link test'>Debug Notifications</a>";
echo "<a href='test_notification_flow.php' class='link test'>Test Notification Flow</a>";
echo "<a href='test_send_notification.php' class='link test'>Test Send Notification</a>";
echo "<a href='setup_notifications_fix.php' class='link test'>Setup Notifications</a>";
echo "</div></div>";

// Login Section
echo "<div class='section'>";
echo "<h2>Login Pages</h2>";
echo "<div class='links'>";
echo "<a href='login_admin.php' class='link'>Admin Login</a>";
echo "<a href='login_employee.php' class='link'>Employee Login</a>";
echo "<a href='index.php' class='link'>Main Index</a>";
echo "</div></div>";

// File System Check
echo "<div class='section'>";
echo "<h2>File System Check</h2>";
echo "<p><strong>Admin Dashboard:</strong> " . (file_exists('frontend/admin/dashboard.php') ? 'Exists' : 'Missing') . "</p>";
echo "<p><strong>Employee Dashboard:</strong> " . (file_exists('frontend/employee/dashboard.php') ? 'Exists' : 'Missing') . "</p>";
echo "<p><strong>Current Directory:</strong> " . __DIR__ . "</p>";
echo "<p><strong>Document Root:</strong> " . $_SERVER['DOCUMENT_ROOT'] . "</p>";
echo "</div>";

echo "</body></html>";
?>
