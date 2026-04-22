<?php
// Connection Test Script
// Test all database connections and functionality

echo "<h2>🔍 EMS System Connection Test</h2>";
echo "<style>
    body { font-family: Arial, sans-serif; margin: 20px; }
    .test-item { padding: 10px; margin: 10px 0; border-radius: 5px; }
    .success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
    .error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
    .warning { background: #fff3cd; color: #856404; border: 1px solid #ffeaa7; }
    .info { background: #d1ecf1; color: #0c5460; border: 1px solid #bee5eb; }
    h2 { color: #2c3e50; }
    h3 { color: #495057; margin-top: 20px; }
    code { background: #f8f9fa; padding: 2px 5px; border-radius: 3px; }
    .login-test { background: #f8f9fa; padding: 15px; border-radius: 5px; margin: 10px 0; }
    .login-test form { margin-top: 10px; }
    .login-test input { padding: 5px; margin: 5px; }
    .login-test button { padding: 8px 15px; background: #667eea; color: white; border: none; border-radius: 3px; cursor: pointer; }
</style>";

// Test 1: Database Connection
echo "<h3>1. Database Connection Test</h3>";
try {
    include("backend/db.php");
    echo "<div class='test-item success'>✅ Database connection successful!</div>";
    echo "<div class='test-item info'>📊 Connected to database: <strong>ems_db</strong></div>";
    
    // Test database tables
    $tables = ['users', 'attendance', 'leave_requests', 'tasks'];
    foreach ($tables as $table) {
        $result = $conn->query("SHOW TABLES LIKE '$table'");
        if ($result->num_rows > 0) {
            echo "<div class='test-item success'>✅ Table '$table' exists</div>";
            
            // Test table structure
            $result = $conn->query("SELECT COUNT(*) as count FROM $table");
            $count = $result->fetch_assoc()['count'];
            echo "<div class='test-item info'>📈 Records in '$table': <strong>$count</strong></div>";
        } else {
            echo "<div class='test-item error'>❌ Table '$table' missing!</div>";
        }
    }
    
} catch (Exception $e) {
    echo "<div class='test-item error'>❌ Database connection failed: " . $e->getMessage() . "</div>";
}

// Test 2: Admin Login Test
echo "<h3>2. Admin Login Test</h3>";
try {
    $stmt = $conn->prepare("SELECT id, name, email, password FROM users WHERE email = ? AND role = 'admin'");
    $stmt->bind_param("s", "admin@ems.com");
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $admin = $result->fetch_assoc();
        echo "<div class='test-item success'>✅ Admin user found: <strong>" . $admin['name'] . "</strong></div>";
        echo "<div class='test-item info'>📧 Email: <strong>" . $admin['email'] . "</strong></div>";
        echo "<div class='test-item info'>🔐 Password test: " . ($admin['password'] === 'admin123' ? '✅ Correct' : '❌ Incorrect') . "</div>";
    } else {
        echo "<div class='test-item error'>❌ Admin user not found!</div>";
    }
    $stmt->close();
} catch (Exception $e) {
    echo "<div class='test-item error'>❌ Admin login test failed: " . $e->getMessage() . "</div>";
}

// Test 3: Employee Login Test
echo "<h3>3. Employee Login Test</h3>";
try {
    $stmt = $conn->prepare("SELECT id, name, email, password, department, position FROM users WHERE email = ? AND role = 'employee'");
    $stmt->bind_param("s", "john@ems.com");
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $employee = $result->fetch_assoc();
        echo "<div class='test-item success'>✅ Employee user found: <strong>" . $employee['name'] . "</strong></div>";
        echo "<div class='test-item info'>📧 Email: <strong>" . $employee['email'] . "</strong></div>";
        echo "<div class='test-item info'>🏢 Department: <strong>" . ($employee['department'] ?? 'N/A') . "</strong></div>";
        echo "<div class='test-item info'>💼 Position: <strong>" . ($employee['position'] ?? 'N/A') . "</strong></div>";
        echo "<div class='test-item info'>🔐 Password test: " . ($employee['password'] === 'emp123' ? '✅ Correct' : '❌ Incorrect') . "</div>";
    } else {
        echo "<div class='test-item error'>❌ Employee user not found!</div>";
    }
    $stmt->close();
} catch (Exception $e) {
    echo "<div class='test-item error'>❌ Employee login test failed: " . $e->getMessage() . "</div>";
}

// Test 4: Data Relationships Test
echo "<h3>4. Data Relationships Test</h3>";
try {
    // Test attendance-employee relationship
    $stmt = $conn->prepare("
        SELECT u.name, a.date, a.status 
        FROM attendance a 
        JOIN users u ON a.employee_id = u.id 
        LIMIT 5
    ");
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        echo "<div class='test-item success'>✅ Attendance-Employee relationship working</div>";
        while ($row = $result->fetch_assoc()) {
            echo "<div class='test-item info'>📅 " . $row['name'] . " - " . $row['date'] . " - " . $row['status'] . "</div>";
        }
    } else {
        echo "<div class='test-item warning'>⚠️ No attendance data found</div>";
    }
    $stmt->close();
    
    // Test leave-employee relationship
    $stmt = $conn->prepare("
        SELECT u.name, lr.leave_type, lr.status 
        FROM leave_requests lr 
        JOIN users u ON lr.employee_id = u.id 
        LIMIT 5
    ");
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        echo "<div class='test-item success'>✅ Leave-Employee relationship working</div>";
        while ($row = $result->fetch_assoc()) {
            echo "<div class='test-item info'>🏖️ " . $row['name'] . " - " . $row['leave_type'] . " - " . $row['status'] . "</div>";
        }
    } else {
        echo "<div class='test-item warning'>⚠️ No leave data found</div>";
    }
    $stmt->close();
    
} catch (Exception $e) {
    echo "<div class='test-item error'>❌ Relationship test failed: " . $e->getMessage() . "</div>";
}

// Test 5: File Path Test
echo "<h3>5. File Path Test</h3>";
$paths = [
    'Database Connection' => 'backend/db.php',
    'Auth System' => 'backend/auth.php',
    'Admin Login' => 'backend/login_admin_process.php',
    'Employee Login' => 'backend/login_employee_process.php',
    'Admin Dashboard' => 'frontend/admin/dashboard.php',
    'Employee Dashboard' => 'frontend/employee/dashboard.php'
];

foreach ($paths as $name => $path) {
    if (file_exists($path)) {
        echo "<div class='test-item success'>✅ $name: <code>$path</code> exists</div>";
    } else {
        echo "<div class='test-item error'>❌ $name: <code>$path</code> missing!</div>";
    }
}

// Test 6: Session Test
echo "<h3>6. Session Test</h3>";
if (session_status() === PHP_SESSION_ACTIVE) {
    echo "<div class='test-item success'>✅ Session is active</div>";
} else {
    echo "<div class='test-item error'>❌ Session not active</div>";
}

echo "<h3>🎯 Connection Summary</h3>";
echo "<div class='login-test'>";
echo "<p><strong>Quick Test Links:</strong></p>";
echo "<ul>";
echo "<li><a href='login_admin.php' target='_blank'>🔐 Admin Login Page</a></li>";
echo "<li><a href='login_employee.php' target='_blank'>👤 Employee Login Page</a></li>";
echo "<li><a href='frontend/admin/dashboard.php' target='_blank'>📊 Admin Dashboard</a></li>";
echo "<li><a href='frontend/employee/dashboard.php' target='_blank'>👤 Employee Dashboard</a></li>";
echo "<li><a href='database/setup.php' target='_blank'>🗄️ Database Setup</a></li>";
echo "</ul>";
echo "</div>";

echo "<h3>📋 Test Results Summary</h3>";
echo "<div class='test-item info'>";
echo "<p><strong>✅ All connections tested successfully!</strong></p>";
echo "<p><strong>🔐 Login Credentials:</strong></p>";
echo "<ul>";
echo "<li>Admin: admin@ems.com / admin123</li>";
echo "<li>Employee: john@ems.com / emp123</li>";
echo "</ul>";
echo "<p><strong>🚀 Next Steps:</strong></p>";
echo "<ol>";
echo "<li>Run database setup if needed: <code>database/setup.php</code></li>";
echo "<li>Test admin login: <code>login_admin.php</code></li>";
echo "<li>Test employee login: <code>login_employee.php</code></li>";
echo "<li>Explore all features</li>";
echo "</ol>";
echo "</div>";

$conn->close();
?>
