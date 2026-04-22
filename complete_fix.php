<?php
// Comprehensive System Fix
session_start();

echo "<h1>EMS System - Complete Fix</h1>";

// Include database
include("backend/db.php");

// Fix 1: Ensure database and tables exist
echo "<h2>🔧 Database Setup</h2>";

// Create database if not exists
$conn->query("CREATE DATABASE IF NOT EXISTS ems_db");
$conn->select_db("ems_db");

// Create tables if not exist
$tables = [
    "CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        email VARCHAR(255) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        role ENUM('admin', 'employee') NOT NULL DEFAULT 'employee',
        department VARCHAR(100),
        position VARCHAR(100),
        status ENUM('active', 'inactive') NOT NULL DEFAULT 'active',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )",
    
    "CREATE TABLE IF NOT EXISTS attendance (
        id INT AUTO_INCREMENT PRIMARY KEY,
        employee_id INT NOT NULL,
        date DATE NOT NULL,
        status ENUM('present', 'absent', 'late', 'leave') NOT NULL,
        check_in TIME,
        check_out TIME,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (employee_id) REFERENCES users(id) ON DELETE CASCADE,
        UNIQUE KEY unique_employee_date (employee_id, date)
    )",
    
    "CREATE TABLE IF NOT EXISTS leave_requests (
        id INT AUTO_INCREMENT PRIMARY KEY,
        employee_id INT NOT NULL,
        leave_type ENUM('Sick Leave', 'Casual Leave', 'Annual Leave', 'Maternity Leave', 'Paternity Leave', 'Emergency Leave') NOT NULL,
        start_date DATE NOT NULL,
        end_date DATE NOT NULL,
        reason TEXT NOT NULL,
        status ENUM('pending', 'approved', 'rejected') NOT NULL DEFAULT 'pending',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        processed_by INT,
        FOREIGN KEY (employee_id) REFERENCES users(id) ON DELETE CASCADE,
        FOREIGN KEY (processed_by) REFERENCES users(id)
    )",
    
    "CREATE TABLE IF NOT EXISTS tasks (
        id INT AUTO_INCREMENT PRIMARY KEY,
        employee_id INT,
        title VARCHAR(255) NOT NULL,
        description TEXT NOT NULL,
        start_date DATE NOT NULL,
        deadline DATE NOT NULL,
        priority ENUM('low', 'medium', 'high') NOT NULL DEFAULT 'medium',
        status ENUM('pending', 'in-progress', 'completed') NOT NULL DEFAULT 'pending',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (employee_id) REFERENCES users(id) ON DELETE CASCADE
    )"
];

foreach ($tables as $sql) {
    if ($conn->query($sql)) {
        echo "<p style='color: green;'>✅ Table created/verified</p>";
    } else {
        echo "<p style='color: red;'>❌ Table error: " . $conn->error . "</p>";
    }
}

// Fix 2: Create admin user if not exists
echo "<h2>👤 User Accounts Setup</h2>";
$admin_check = $conn->query("SELECT id FROM users WHERE role = 'admin' LIMIT 1");
if ($admin_check->num_rows == 0) {
    $conn->query("INSERT INTO users (name, email, password, role, department, position, status) VALUES 
    ('Admin User', 'admin@ems.com', 'admin123', 'admin', 'IT', 'System Administrator', 'active')");
    echo "<p style='color: green;'>✅ Admin user created</p>";
} else {
    echo "<p style='color: orange;'>⚠️ Admin user already exists</p>";
}

// Create sample employees if none exist
$emp_check = $conn->query("SELECT id FROM users WHERE role = 'employee' LIMIT 1");
if ($emp_check->num_rows == 0) {
    $conn->query("INSERT INTO users (name, email, password, role, department, position, status) VALUES 
    ('John Smith', 'john@ems.com', 'emp123', 'employee', 'IT', 'Software Developer', 'active'),
    ('Jane Doe', 'jane@ems.com', 'emp123', 'employee', 'HR', 'HR Manager', 'active'),
    ('Mike Johnson', 'mike@ems.com', 'emp123', 'employee', 'Finance', 'Accountant', 'active')");
    echo "<p style='color: green;'>✅ Sample employees created</p>";
} else {
    echo "<p style='color: orange;'>⚠️ Employees already exist</p>";
}

// Fix 3: Test attendance functionality
echo "<h2>📅 Attendance System Test</h2>";
$today = date('Y-m-d');

// Get first employee for testing
$emp_result = $conn->query("SELECT id, name FROM users WHERE role = 'employee' LIMIT 1");
if ($emp_result->num_rows > 0) {
    $employee = $emp_result->fetch_assoc();
    $emp_id = $employee['id'];
    $emp_name = $employee['name'];
    
    echo "<p>Testing with employee: $emp_name (ID: $emp_id)</p>";
    
    // Check if attendance exists for today
    $att_check = $conn->prepare("SELECT id FROM attendance WHERE employee_id = ? AND date = ?");
    $att_check->bind_param("is", $emp_id, $today);
    $att_check->execute();
    
    if ($att_check->get_result()->num_rows == 0) {
        // Insert test attendance
        $check_in = date('H:i');
        $insert = $conn->prepare("INSERT INTO attendance (employee_id, date, status, check_in, created_at) VALUES (?, ?, 'present', ?, NOW())");
        $insert->bind_param("iss", $emp_id, $today, $check_in);
        
        if ($insert->execute()) {
            echo "<p style='color: green;'>✅ Test attendance created successfully</p>";
        } else {
            echo "<p style='color: red;'>❌ Failed to create test attendance: " . $insert->error . "</p>";
        }
        $insert->close();
    } else {
        echo "<p style='color: orange;'>⚠️ Attendance already exists for today</p>";
    }
    $att_check->close();
    
    // Verify attendance count
    $count_result = $conn->query("SELECT COUNT(*) as count FROM attendance WHERE date = '$today' AND status = 'present'");
    $count = $count_result->fetch_assoc()['count'];
    echo "<p>Present today count: $count</p>";
    
} else {
    echo "<p style='color: red;'>❌ No employees found to test attendance</p>";
}

// Fix 4: Clear any existing sessions
echo "<h2>🔄 Session Reset</h2>";
session_destroy();
echo "<p style='color: green;'>✅ Sessions cleared</p>";

// Fix 5: Display login credentials
echo "<h2>🔑 Login Credentials</h2>";
echo "<div style='background: #f0f0f0; padding: 15px; border-radius: 5px; margin: 10px 0;'>";
echo "<h3>Admin Login:</h3>";
echo "<p><strong>Email:</strong> admin@ems.com</p>";
echo "<p><strong>Password:</strong> admin123</p>";
echo "<hr>";
echo "<h3>Employee Login:</h3>";
echo "<p><strong>Email:</strong> john@ems.com</p>";
echo "<p><strong>Password:</strong> emp123</p>";
echo "</div>";

// Fix 6: Test links
echo "<h2>🔗 Quick Links</h2>";
echo "<p><a href='index.php' style='background: #667eea; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; margin: 5px;'>🏠 Home Page</a></p>";
echo "<p><a href='login_admin.php' style='background: #e74c3c; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; margin: 5px;'>👨‍💼 Admin Login</a></p>";
echo "<p><a href='login_employee.php' style='background: #3498db; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; margin: 5px;'>👤 Employee Login</a></p>";

echo "<h2 style='color: green;'>🎉 System Fixed Successfully!</h2>";
echo "<p><strong>Next Steps:</strong></p>";
echo "<ol>";
echo "<li>Go to home page and login as admin or employee</li>";
echo "<li>Test attendance marking from employee panel</li>";
echo "<li>Check admin dashboard for attendance counts</li>";
echo "<li>Test all other features (tasks, leave requests, etc.)</li>";
echo "</ol>";

$conn->close();
?>
