<?php
// Login Debug Script
echo "<h1>🔍 LOGIN SYSTEM DEBUG</h1>";
echo "<style>
    body { font-family: Arial, sans-serif; margin: 20px; background: #f8f9fa; }
    .debug-section { background: white; padding: 20px; margin: 15px 0; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
    .section-title { color: #2c3e50; border-bottom: 3px solid #667eea; padding-bottom: 10px; margin-bottom: 15px; }
    .test-item { padding: 10px; margin: 10px 0; border-left: 4px solid #667eea; background: #f8f9fa; }
    .success { color: #27ae60; }
    .error { color: #e74c3c; }
    .warning { color: #f39c12; }
    .info { color: #3498db; }
    .code { background: #e9ecef; padding: 2px 5px; border-radius: 3px; font-family: monospace; }
    .form-test { background: #fff; padding: 15px; border: 1px solid #ddd; border-radius: 5px; margin: 10px 0; }
    .form-test input { padding: 8px; margin: 5px; border: 1px solid #ddd; border-radius: 3px; }
    .form-test button { padding: 10px 20px; background: #667eea; color: white; border: none; border-radius: 3px; cursor: pointer; }
</style>";

echo "<div class='debug-section'>";
echo "<h2 class='section-title'>📁 File Structure Check</h2>";

// Check login files
$login_files = [
    'Admin Login Page' => 'login_admin.php',
    'Employee Login Page' => 'login_employee.php',
    'Admin Login Process' => 'backend/login_admin_process.php',
    'Employee Login Process' => 'backend/login_employee_process.php',
    'Database Connection' => 'backend/db.php',
    'Auth System' => 'backend/auth.php'
];

foreach ($login_files as $name => $file) {
    $exists = file_exists($file);
    $size = $exists ? filesize($file) : 0;
    echo "<div class='test-item'>";
    echo "<span class='" . ($exists ? 'success' : 'error') . "'>";
    echo ($exists ? '✅' : '❌') . " $name";
    echo "</span><br>";
    echo "<span class='code'>$file</span> ";
    echo "<span class='info'>(" . round($size/1024, 2) . " KB)</span>";
    echo "</div>";
}

echo "</div>";

// Test database connection
echo "<div class='debug-section'>";
echo "<h2 class='section-title'>🗄️ Database Connection Test</h2>";

try {
    include("backend/db.php");
    echo "<div class='test-item success'>✅ Database connection successful</div>";
    echo "<div class='test-item info'>📊 Database: ems_db</div>";
    echo "<div class='test-item info'>🔗 Host: localhost</div>";
    echo "<div class='test-item info'>👤 User: root</div>";
    
    // Test if users table exists and has data
    $result = $conn->query("SELECT COUNT(*) as count FROM users");
    if ($result) {
        $count = $result->fetch_assoc()['count'];
        echo "<div class='test-item success'>✅ Users table exists with $count records</div>";
        
        // Test admin user
        $stmt = $conn->prepare("SELECT id, name, email, password, role FROM users WHERE email = ? AND role = 'admin'");
        $stmt->bind_param("s", "admin@ems.com");
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $admin = $result->fetch_assoc();
            echo "<div class='test-item success'>✅ Admin user found: " . $admin['name'] . "</div>";
            echo "<div class='test-item info'>📧 Email: " . $admin['email'] . "</div>";
            echo "<div class='test-item info'>🔐 Password: " . $admin['password'] . "</div>";
            echo "<div class='test-item info'>👤 Role: " . $admin['role'] . "</div>";
        } else {
            echo "<div class='test-item error'>❌ Admin user not found!</div>";
        }
        $stmt->close();
        
        // Test employee user
        $stmt = $conn->prepare("SELECT id, name, email, password, role FROM users WHERE email = ? AND role = 'employee'");
        $stmt->bind_param("s", "john@ems.com");
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $employee = $result->fetch_assoc();
            echo "<div class='test-item success'>✅ Employee user found: " . $employee['name'] . "</div>";
            echo "<div class='test-item info'>📧 Email: " . $employee['email'] . "</div>";
            echo "<div class='test-item info'>🔐 Password: " . $employee['password'] . "</div>";
            echo "<div class='test-item info'>👤 Role: " . $employee['role'] . "</div>";
        } else {
            echo "<div class='test-item error'>❌ Employee user not found!</div>";
        }
        $stmt->close();
    }
    $conn->close();
    
} catch (Exception $e) {
    echo "<div class='test-item error'>❌ Database connection failed: " . $e->getMessage() . "</div>";
}

echo "</div>";

// Test login process simulation
echo "<div class='debug-section'>";
echo "<h2 class='section-title'>🧪 Login Process Simulation</h2>";

// Simulate admin login
echo "<h3>🔐 Admin Login Test</h3>";
echo "<div class='form-test'>";
echo "<form method='post'>";
echo "<h4>Test Admin Login:</h4>";
echo "<input type='email' name='test_email' placeholder='admin@ems.com' value='admin@ems.com'><br><br>";
echo "<input type='password' name='test_password' placeholder='admin123' value='admin123'><br><br>";
echo "<button type='submit' name='test_admin_login'>Test Admin Login</button>";
echo "</form>";
echo "</div>";

// Simulate employee login
echo "<h3>👤 Employee Login Test</h3>";
echo "<div class='form-test'>";
echo "<form method='post'>";
echo "<h4>Test Employee Login:</h4>";
echo "<input type='email' name='test_email_emp' placeholder='john@ems.com' value='john@ems.com'><br><br>";
echo "<input type='password' name='test_password_emp' placeholder='emp123' value='emp123'><br><br>";
echo "<button type='submit' name='test_employee_login'>Test Employee Login</button>";
echo "</form>";
echo "</div>";

// Process test login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo "<div class='test-item info'>🔄 Processing login test...</div>";
    
    try {
        include("backend/db.php");
        
        if (isset($_POST['test_admin_login'])) {
            $email = $_POST['test_email'];
            $password = $_POST['test_password'];
            
            $stmt = $conn->prepare("SELECT id, name, email, password, role FROM users WHERE email = ? AND role = 'admin'");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows > 0) {
                $user = $result->fetch_assoc();
                if ($password === $user['password']) {
                    echo "<div class='test-item success'>✅ Admin login SUCCESSFUL!</div>";
                    echo "<div class='test-item info'>👤 Welcome: " . $user['name'] . "</div>";
                    
                    // Set session variables
                    session_start();
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['name'] = $user['name'];
                    $_SESSION['email'] = $user['email'];
                    $_SESSION['role'] = $user['role'];
                    
                    echo "<div class='test-item success'>✅ Session variables set</div>";
                    echo "<div class='test-item info'>🔗 <a href='frontend/admin/dashboard.php' target='_blank'>Go to Admin Dashboard</a></div>";
                } else {
                    echo "<div class='test-item error'>❌ Admin login FAILED: Wrong password</div>";
                }
            } else {
                echo "<div class='test-item error'>❌ Admin login FAILED: User not found</div>";
            }
            $stmt->close();
        }
        
        if (isset($_POST['test_employee_login'])) {
            $email = $_POST['test_email_emp'];
            $password = $_POST['test_password_emp'];
            
            $stmt = $conn->prepare("SELECT id, name, email, password, role FROM users WHERE email = ? AND role = 'employee'");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows > 0) {
                $user = $result->fetch_assoc();
                if ($password === $user['password']) {
                    echo "<div class='test-item success'>✅ Employee login SUCCESSFUL!</div>";
                    echo "<div class='test-item info'>👤 Welcome: " . $user['name'] . "</div>";
                    
                    // Set session variables
                    session_start();
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['name'] = $user['name'];
                    $_SESSION['email'] = $user['email'];
                    $_SESSION['role'] = $user['role'];
                    
                    echo "<div class='test-item success'>✅ Session variables set</div>";
                    echo "<div class='test-item info'>🔗 <a href='frontend/employee/dashboard.php' target='_blank'>Go to Employee Dashboard</a></div>";
                } else {
                    echo "<div class='test-item error'>❌ Employee login FAILED: Wrong password</div>";
                }
            } else {
                echo "<div class='test-item error'>❌ Employee login FAILED: User not found</div>";
            }
            $stmt->close();
        }
        
        $conn->close();
        
    } catch (Exception $e) {
        echo "<div class='test-item error'>❌ Login test failed: " . $e->getMessage() . "</div>";
    }
}

echo "</div>";

// Check session status
echo "<div class='debug-section'>";
echo "<h2 class='section-title'>🔄 Session Status</h2>";

if (session_status() === PHP_SESSION_ACTIVE) {
    echo "<div class='test-item success'>✅ Session is active</div>";
    if (isset($_SESSION['user_id'])) {
        echo "<div class='test-item info'>👤 Logged in user ID: " . $_SESSION['user_id'] . "</div>";
        echo "<div class='test-item info'>👤 Logged in user name: " . ($_SESSION['name'] ?? 'N/A') . "</div>";
        echo "<div class='test-item info'>📧 Logged in user email: " . ($_SESSION['email'] ?? 'N/A') . "</div>";
        echo "<div class='test-item info'>🔐 Logged in user role: " . ($_SESSION['role'] ?? 'N/A') . "</div>";
    } else {
        echo "<div class='test-item warning'>⚠️ No active session found</div>";
    }
} else {
    echo "<div class='test-item error'>❌ Session is not active</div>";
}

echo "</div>";

// Quick fixes
echo "<div class='debug-section'>";
echo "<h2 class='section-title'>🔧 Common Issues & Fixes</h2>";

echo "<div class='test-item'>";
echo "<strong>Issue 1:</strong> Database connection failed<br>";
echo "<strong>Fix:</strong> Check XAMPP MySQL service is running<br>";
echo "<strong>Command:</strong> Start XAMPP Control Panel → Start MySQL";
echo "</div>";

echo "<div class='test-item'>";
echo "<strong>Issue 2:</strong> Users table missing<br>";
echo "<strong>Fix:</strong> Run database setup<br>";
echo "<strong>Command:</strong> <a href='database/setup.php' target='_blank'>Run Setup Script</a>";
echo "</div>";

echo "<div class='test-item'>";
echo "<strong>Issue 3:</strong> Wrong file paths<br>";
echo "<strong>Fix:</strong> Check file permissions and paths<br>";
echo "<strong>Command:</strong> Ensure all files are in correct folders";
echo "</div>";

echo "<div class='test-item'>";
echo "<strong>Issue 4:</strong> Session not working<br>";
echo "<strong>Fix:</strong> Check session.save_path in php.ini<br>";
echo "<strong>Command:</strong> Ensure session folder is writable";
echo "</div>";

echo "</div>";

echo "<div class='debug-section'>";
echo "<h2 class='section-title'>🚀 Quick Actions</h2>";
echo "<div class='test-item'>";
echo "<a href='database/setup.php' target='_blank' style='background: #27ae60; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; margin: 5px;'>🗄️ Setup Database</a>";
echo "<a href='login_admin.php' target='_blank' style='background: #667eea; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; margin: 5px;'>🔐 Admin Login</a>";
echo "<a href='login_employee.php' target='_blank' style='background: #3498db; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; margin: 5px;'>👤 Employee Login</a>";
echo "</div>";
echo "</div>";

echo "<div class='debug-section'>";
echo "<h2 class='section-title'>📋 Login Credentials</h2>";
echo "<div class='test-item info'>";
echo "<strong>Admin:</strong> admin@ems.com / admin123<br>";
echo "<strong>Employee:</strong> john@ems.com / emp123<br>";
echo "<strong>Database:</strong> ems_db (localhost)<br>";
echo "<strong>XAMPP:</strong> MySQL service must be running";
echo "</div>";
echo "</div>";
?>
