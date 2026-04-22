<?php
// Complete System Status Check
echo "<h1>🔍 EMS SYSTEM COMPLETE STATUS REPORT</h1>";
echo "<style>
    body { font-family: Arial, sans-serif; margin: 20px; background: #f8f9fa; }
    .status-card { background: white; padding: 20px; margin: 15px 0; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
    .section-title { color: #2c3e50; border-bottom: 3px solid #667eea; padding-bottom: 10px; margin-bottom: 15px; }
    .item { padding: 8px 0; border-bottom: 1px solid #e9ecef; }
    .item:last-child { border-bottom: none; }
    .success { color: #27ae60; font-weight: bold; }
    .error { color: #e74c3c; font-weight: bold; }
    .warning { color: #f39c12; font-weight: bold; }
    .info { color: #3498db; }
    .checklist { list-style: none; padding: 0; }
    .checklist li { padding: 5px 0; }
    .checklist li:before { content: '✅ '; color: #27ae60; font-weight: bold; }
    .missing:before { content: '❌ '; color: #e74c3c; }
    .file-path { background: #f8f9fa; padding: 2px 5px; border-radius: 3px; font-family: monospace; }
    .summary { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 20px; border-radius: 10px; margin: 20px 0; }
    .grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
    @media (max-width: 768px) { .grid { grid-template-columns: 1fr; } }
</style>";

echo "<div class='summary'>";
echo "<h2>🎯 SYSTEM OVERVIEW</h2>";
echo "<p><strong>Employee Management System (EMS)</strong></p>";
echo "<p>Complete PHP-based employee management solution with admin and employee panels</p>";
echo "</div>";

echo "<div class='grid'>";

// Left Column - Frontend Pages
echo "<div>";
echo "<div class='status-card'>";
echo "<h2 class='section-title'>📄 FRONTEND PAGES STATUS</h2>";

// Public Pages
echo "<h3>🌐 Public Pages</h3>";
$public_pages = [
    'Home Page' => 'index.php',
    'About Page' => 'about.php', 
    'Contact Page' => 'contact.php',
    'Admin Login' => 'login_admin.php',
    'Employee Login' => 'login_employee.php'
];

foreach ($public_pages as $name => $file) {
    $exists = file_exists($file);
    echo "<div class='item'>";
    echo "<span class='" . ($exists ? 'success' : 'error') . "'>";
    echo ($exists ? '✅' : '❌') . " $name";
    echo "</span> - <span class='file-path'>$file</span>";
    echo "</div>";
}

// Admin Pages
echo "<h3>👨‍💼 Admin Panel Pages</h3>";
$admin_pages = [
    'Dashboard' => 'frontend/admin/dashboard.php',
    'Employees Management' => 'frontend/admin/employees.php',
    'Attendance Management' => 'frontend/admin/attendance.php',
    'Leave Requests' => 'frontend/admin/leave_requests.php',
    'Task Management' => 'frontend/admin/tasks.php',
    'Reports' => 'frontend/admin/reports.php',
    'Settings' => 'frontend/admin/settings.php'
];

foreach ($admin_pages as $name => $file) {
    $exists = file_exists($file);
    echo "<div class='item'>";
    echo "<span class='" . ($exists ? 'success' : 'error') . "'>";
    echo ($exists ? '✅' : '❌') . " $name";
    echo "</span> - <span class='file-path'>$file</span>";
    echo "</div>";
}

// Employee Pages
echo "<h3>👤 Employee Panel Pages</h3>";
$employee_pages = [
    'Dashboard' => 'frontend/employee/dashboard.php',
    'Profile' => 'frontend/employee/profile.php',
    'Attendance' => 'frontend/employee/attendance.php',
    'Leave Request' => 'frontend/employee/leave.php',
    'Tasks' => 'frontend/employee/tasks.php'
];

foreach ($employee_pages as $name => $file) {
    $exists = file_exists($file);
    echo "<div class='item'>";
    echo "<span class='" . ($exists ? 'success' : 'error') . "'>";
    echo ($exists ? '✅' : '❌') . " $name";
    echo "</span> - <span class='file-path'>$file</span>";
    echo "</div>";
}

// Components
echo "<h3>🧩 Components</h3>";
$components = [
    'Admin Sidebar' => 'frontend/components/admin_sidebar.php',
    'Employee Sidebar' => 'frontend/components/employee_sidebar.php',
    'Header' => 'components/header.php',
    'Footer' => 'components/footer.php'
];

foreach ($components as $name => $file) {
    $exists = file_exists($file);
    echo "<div class='item'>";
    echo "<span class='" . ($exists ? 'success' : 'error') . "'>";
    echo ($exists ? '✅' : '❌') . " $name";
    echo "</span> - <span class='file-path'>$file</span>";
    echo "</div>";
}

echo "</div>";
echo "</div>";

// Right Column - Backend Files
echo "<div>";
echo "<div class='status-card'>";
echo "<h2 class='section-title'>⚙️ BACKEND FILES STATUS</h2>";

// Core Backend
echo "<h3>🔧 Core Backend</h3>";
$core_backend = [
    'Database Connection' => 'backend/db.php',
    'Authentication System' => 'backend/auth.php',
    'Logout Handler' => 'backend/logout.php',
    'Admin Login Process' => 'backend/login_admin_process.php',
    'Employee Login Process' => 'backend/login_employee_process.php'
];

foreach ($core_backend as $name => $file) {
    $exists = file_exists($file);
    echo "<div class='item'>";
    echo "<span class='" . ($exists ? 'success' : 'error') . "'>";
    echo ($exists ? '✅' : '❌') . " $name";
    echo "</span> - <span class='file-path'>$file</span>";
    echo "</div>";
}

// Admin Backend
echo "<h3>👨‍💼 Admin Backend</h3>";
$admin_backend = [
    'Add Employee' => 'backend/admin/add_employee.php',
    'Update Employee' => 'backend/admin/update_employee.php',
    'Delete Employee' => 'backend/admin/delete_employee.php',
    'Mark Attendance' => 'backend/admin/mark_attendance.php',
    'Handle Leave' => 'backend/admin/handle_leave.php',
    'Assign Task' => 'backend/admin/assign_task.php',
    'Update Password' => 'backend/admin/update_password.php'
];

foreach ($admin_backend as $name => $file) {
    $exists = file_exists($file);
    echo "<div class='item'>";
    echo "<span class='" . ($exists ? 'success' : 'error') . "'>";
    echo ($exists ? '✅' : '❌') . " $name";
    echo "</span> - <span class='file-path'>$file</span>";
    echo "</div>";
}

// Employee Backend
echo "<h3>👤 Employee Backend</h3>";
$employee_backend = [
    'Mark Attendance' => 'backend/employee/mark_attendance.php',
    'Apply Leave' => 'backend/employee/apply_leave.php',
    'Update Task Status' => 'backend/employee/update_task_status.php',
    'Update Password' => 'backend/employee/update_password.php'
];

foreach ($employee_backend as $name => $file) {
    $exists = file_exists($file);
    echo "<div class='item'>";
    echo "<span class='" . ($exists ? 'success' : 'error') . "'>";
    echo ($exists ? '✅' : '❌') . " $name";
    echo "</span> - <span class='file-path'>$file</span>";
    echo "</div>";
}

echo "</div>";
echo "</div>";

echo "</div>";

// Database Status
echo "<div class='status-card'>";
echo "<h2 class='section-title'>🗄️ DATABASE STATUS</h2>";

// Database files
echo "<h3>📊 Database Files</h3>";
$database_files = [
    'SQL Schema' => 'database/ems.sql',
    'Setup Script' => 'database/setup.php',
    'Documentation' => 'database/README.md'
];

foreach ($database_files as $name => $file) {
    $exists = file_exists($file);
    echo "<div class='item'>";
    echo "<span class='" . ($exists ? 'success' : 'error') . "'>";
    echo ($exists ? '✅' : '❌') . " $name";
    echo "</span> - <span class='file-path'>$file</span>";
    echo "</div>";
}

// Test database connection if possible
echo "<h3>🔗 Database Connection Test</h3>";
try {
    include("backend/db.php");
    echo "<div class='item success'>✅ Database connection successful</div>";
    echo "<div class='item info'>📊 Database: ems_db</div>";
    
    // Test tables
    $tables = ['users', 'attendance', 'leave_requests', 'tasks'];
    foreach ($tables as $table) {
        $result = $conn->query("SHOW TABLES LIKE '$table'");
        if ($result->num_rows > 0) {
            $count_result = $conn->query("SELECT COUNT(*) as count FROM $table");
            $count = $count_result->fetch_assoc()['count'];
            echo "<div class='item success'>✅ Table '$table' exists ($count records)</div>";
        } else {
            echo "<div class='item error'>❌ Table '$table' missing</div>";
        }
    }
    $conn->close();
} catch (Exception $e) {
    echo "<div class='item error'>❌ Database connection failed: " . $e->getMessage() . "</div>";
}

echo "</div>";

// Final Summary
echo "<div class='status-card'>";
echo "<h2 class='section-title'>🎯 FINAL SYSTEM STATUS</h2>";

$total_files = 0;
$existing_files = 0;

// Count all files
$all_files = array_merge(
    $public_pages, $admin_pages, $employee_pages, $components,
    $core_backend, $admin_backend, $employee_backend, $database_files
);

foreach ($all_files as $file) {
    $total_files++;
    if (file_exists($file)) {
        $existing_files++;
    }
}

$completion_rate = round(($existing_files / $total_files) * 100, 1);

echo "<div class='item info'>📊 Total Files: <strong>$total_files</strong></div>";
echo "<div class='item success'>✅ Created Files: <strong>$existing_files</strong></div>";
echo "<div class='item info'>📈 Completion Rate: <strong>$completion_rate%</strong></div>";

if ($completion_rate >= 95) {
    echo "<div class='item success'>🎉 SYSTEM IS COMPLETE AND READY!</div>";
} else {
    echo "<div class='item warning'>⚠️ System needs " . (100 - $completion_rate) . "% more files</div>";
}

echo "<h3>🚀 Quick Access Links</h3>";
echo "<ul class='checklist'>";
echo "<li><a href='index.php' target='_blank'>🏠 Home Page</a></li>";
echo "<li><a href='login_admin.php' target='_blank'>🔐 Admin Login</a></li>";
echo "<li><a href='login_employee.php' target='_blank'>👤 Employee Login</a></li>";
echo "<li><a href='database/setup.php' target='_blank'>🗄️ Database Setup</a></li>";
echo "<li><a href='test_connection.php' target='_blank'>🔍 Connection Test</a></li>";
echo "</ul>";

echo "<h3>🔐 Login Credentials</h3>";
echo "<ul class='checklist'>";
echo "<li>Admin: admin@ems.com / admin123</li>";
echo "<li>Employee: john@ems.com / emp123</li>";
echo "</ul>";

echo "</div>";
?>
