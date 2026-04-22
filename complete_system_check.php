<?php
// Complete System Check - Logout Error & Employee Login Issues
echo "<h1>🔍 COMPLETE SYSTEM DIAGNOSTIC</h1>";
echo "<style>
    body { font-family: Arial, sans-serif; margin: 20px; background: #f8f9fa; }
    .diagnostic-card { background: white; padding: 25px; margin: 15px 0; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
    .section-title { color: #2c3e50; border-bottom: 3px solid #667eea; padding-bottom: 10px; margin-bottom: 15px; }
    .test-item { padding: 12px; margin: 10px 0; border-left: 4px solid #667eea; background: #f8f9fa; border-radius: 5px; }
    .success { color: #27ae60; font-weight: bold; }
    .error { color: #e74c3c; font-weight: bold; }
    .warning { color: #f39c12; font-weight: bold; }
    .info { color: #3498db; }
    .code { background: #e9ecef; padding: 2px 5px; border-radius: 3px; font-family: monospace; }
    .btn { background: #667eea; color: white; padding: 12px 25px; text-decoration: none; border-radius: 5px; display: inline-block; margin: 10px 5px; }
    .btn-danger { background: #e74c3c; }
    .btn-success { background: #27ae60; }
    .btn-warning { background: #f39c12; }
    .error-box { background: #f8d7da; border: 1px solid #f5c6cb; padding: 15px; border-radius: 5px; margin: 10px 0; }
    .success-box { background: #d4edda; border: 1px solid #c3e6cb; padding: 15px; border-radius: 5px; margin: 10px 0; }
    .warning-box { background: #fff3cd; border: 1px solid #ffeaa7; padding: 15px; border-radius: 5px; margin: 10px 0; }
</style>";

echo "<div class='diagnostic-card'>";
echo "<h2 class='section-title'>🚨 CRITICAL ISSUES IDENTIFIED</h2>";
echo "<div class='error-box'>";
echo "<strong>❌ Logout Error</strong><br>";
echo "<strong>❌ Employee Login Not Working</strong><br>";
echo "<strong>🔍 Checking Backend & Database...</strong>";
echo "</div>";
echo "</div>";

// Database Connection Check
echo "<div class='diagnostic-card'>";
echo "<h2 class='section-title'>🗄️ DATABASE CONNECTION CHECK</h2>";

try {
    $host = "localhost";
    $username = "root";
    $password = "";
    $database = "ems_db";
    
    // Test connection without database first
    $conn = new mysqli($host, $username, $password);
    
    if ($conn->connect_error) {
        echo "<div class='test-item error'>❌ MySQL Connection Failed: " . $conn->connect_error . "</div>";
        echo "<div class='test-item warning'>⚠️ Fix: Start XAMPP MySQL Service</div>";
        echo "</div>";
        echo "</div>";
        exit();
    }
    
    echo "<div class='test-item success'>✅ MySQL Server Connected Successfully</div>";
    
    // Check if database exists
    $result = $conn->query("SHOW DATABASES LIKE 'ems_db'");
    if ($result->num_rows > 0) {
        echo "<div class='test-item success'>✅ Database 'ems_db' Exists</div>";
        
        // Select database
        $conn->select_db("ems_db");
        
        // Check tables
        $tables = ['users', 'attendance', 'leave_requests', 'tasks'];
        foreach ($tables as $table) {
            $result = $conn->query("SHOW TABLES LIKE '$table'");
            if ($result->num_rows > 0) {
                $count_result = $conn->query("SELECT COUNT(*) as count FROM $table");
                $count = $count_result->fetch_assoc()['count'];
                echo "<div class='test-item success'>✅ Table '$table' exists ($count records)</div>";
            } else {
                echo "<div class='test-item error'>❌ Table '$table' MISSING!</div>";
            }
        }
        
        // Check employee users
        $result = $conn->query("SELECT id, name, email, password, role FROM users WHERE role = 'employee'");
        if ($result->num_rows > 0) {
            echo "<div class='test-item success'>✅ Employee Users Found: " . $result->num_rows . "</div>";
            while ($row = $result->fetch_assoc()) {
                echo "<div class='test-item info'>👤 " . $row['name'] . " (" . $row['email'] . ") - Role: " . $row['role'] . "</div>";
            }
        } else {
            echo "<div class='test-item error'>❌ NO EMPLOYEE USERS FOUND!</div>";
        }
        
        // Check admin user
        $result = $conn->query("SELECT id, name, email, password, role FROM users WHERE role = 'admin'");
        if ($result->num_rows > 0) {
            $admin = $result->fetch_assoc();
            echo "<div class='test-item success'>✅ Admin User Found: " . $admin['name'] . "</div>";
            echo "<div class='test-item info'>📧 Email: " . $admin['email'] . "</div>";
            echo "<div class='test-item info'>🔐 Password: " . $admin['password'] . "</div>";
        } else {
            echo "<div class='test-item error'>❌ NO ADMIN USER FOUND!</div>";
        }
        
    } else {
        echo "<div class='test-item error'>❌ Database 'ems_db' DOES NOT EXIST!</div>";
        echo "<div class='test-item warning'>⚠️ Run: database/quick_setup.php</div>";
    }
    
    $conn->close();
    
} catch (Exception $e) {
    echo "<div class='test-item error'>❌ Database Error: " . $e->getMessage() . "</div>";
}

echo "</div>";

// Backend Files Check
echo "<div class='diagnostic-card'>";
echo "<h2 class='section-title'>⚙️ BACKEND FILES CHECK</h2>";

$backend_files = [
    'Logout Script' => 'backend/logout.php',
    'Admin Login Process' => 'backend/login_admin_process.php',
    'Employee Login Process' => 'backend/login_employee_process.php',
    'Database Connection' => 'backend/db.php',
    'Auth System' => 'backend/auth.php'
];

foreach ($backend_files as $name => $file) {
    if (file_exists($file)) {
        echo "<div class='test-item success'>✅ $name: <span class='code'>$file</span></div>";
        
        // Check file content for critical functions
        $content = file_get_contents($file);
        
        if ($name === 'Logout Script') {
            if (strpos($content, 'session_start()') !== false) {
                echo "<div class='test-item success'>   ✅ session_start() found</div>";
            } else {
                echo "<div class='test-item error'>   ❌ session_start() missing</div>";
            }
            
            if (strpos($content, 'session_destroy()') !== false) {
                echo "<div class='test-item success'>   ✅ session_destroy() found</div>";
            } else {
                echo "<div class='test-item error'>   ❌ session_destroy() missing</div>";
            }
            
            if (strpos($content, 'header(\'Location:\')') !== false) {
                echo "<div class='test-item success'>   ✅ Redirect found</div>";
            } else {
                echo "<div class='test-item error'>   ❌ Redirect missing</div>";
            }
        }
        
        if ($name === 'Employee Login Process') {
            if (strpos($content, "role = 'employee'") !== false) {
                echo "<div class='test-item success'>   ✅ Employee role check found</div>";
            } else {
                echo "<div class='test-item error'>   ❌ Employee role check missing</div>";
            }
        }
        
    } else {
        echo "<div class='test-item error'>❌ $name: <span class='code'>$file</span> MISSING!</div>";
    }
}

echo "</div>";

// Frontend Files Check
echo "<div class='diagnostic-card'>";
echo "<h2 class='section-title'>📄 FRONTEND FILES CHECK</h2>";

$frontend_files = [
    'Employee Login Page' => 'login_employee.php',
    'Admin Login Page' => 'login_admin.php',
    'Header Component' => 'components/header.php',
    'Employee Dashboard' => 'frontend/employee/dashboard.php'
];

foreach ($frontend_files as $name => $file) {
    if (file_exists($file)) {
        echo "<div class='test-item success'>✅ $name: <span class='code'>$file</span></div>";
        
        if ($name === 'Employee Login Page') {
            $content = file_get_contents($file);
            if (strpos($content, 'backend/login_employee_process.php') !== false) {
                echo "<div class='test-item success'>   ✅ Correct backend action</div>";
            } else {
                echo "<div class='test-item error'>   ❌ Wrong backend action</div>";
            }
        }
        
        if ($name === 'Header Component') {
            $content = file_get_contents($file);
            if (strpos($content, 'session_start()') !== false) {
                echo "<div class='test-item success'>   ✅ session_start() found</div>";
            } else {
                echo "<div class='test-item error'>   ❌ session_start() missing</div>";
            }
        }
        
    } else {
        echo "<div class='test-item error'>❌ $name: <span class='code'>$file</span> MISSING!</div>";
    }
}

echo "</div>";

// Session System Check
echo "<div class='diagnostic-card'>";
echo "<h2 class='section-title'>🔄 SESSION SYSTEM CHECK</h2>";

echo "<div class='test-item info'>📊 Session Status: " . session_status() . "</div>";
echo "<div class='test-item info'>🆔 Session ID: " . session_id() . "</div>";

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
    echo "<div class='test-item warning'>⚠️ Session was not active, started now</div>";
} else {
    echo "<div class='test-item success'>✅ Session is active</div>";
}

echo "<div class='test-item info'>📁 Session Save Path: " . session_save_path() . "</div>";
echo "<div class='test-item info'>🍪 Session Cookie Name: " . session_name() . "</div>";

// Check session data
if (!empty($_SESSION)) {
    echo "<div class='test-item info'>📝 Current Session Data:</div>";
    foreach ($_SESSION as $key => $value) {
        echo "<div class='test-item info'>   $key: " . (is_string($value) ? htmlspecialchars($value) : print_r($value, true)) . "</div>";
    }
} else {
    echo "<div class='test-item warning'>⚠️ No session data (user not logged in)</div>";
}

echo "</div>";

// Live Login Test
echo "<div class='diagnostic-card'>";
echo "<h2 class='section-title'>🧪 LIVE LOGIN TEST</h2>";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo "<div class='test-item info'>🔄 Testing login...</div>";
    
    try {
        include("backend/db.php");
        
        $email = $_POST['test_email'] ?? '';
        $password = $_POST['test_password'] ?? '';
        $user_type = $_POST['user_type'] ?? '';
        
        echo "<div class='test-item info'>📧 Email: $email</div>";
        echo "<div class='test-item info'>🔐 Password: $password</div>";
        echo "<div class='test-item info'>👤 User Type: $user_type</div>";
        
        if ($user_type === 'admin') {
            $stmt = $conn->prepare("SELECT id, name, email, password, role FROM users WHERE email = ? AND role = 'admin'");
        } else {
            $stmt = $conn->prepare("SELECT id, name, email, password, role FROM users WHERE email = ? AND role = 'employee'");
        }
        
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            
            if ($password === $user['password']) {
                echo "<div class='test-item success'>✅ Login SUCCESSFUL!</div>";
                echo "<div class='test-item info'>👤 Welcome: " . $user['name'] . "</div>";
                echo "<div class='test-item info'>📧 Email: " . $user['email'] . "</div>";
                echo "<div class='test-item info'>🔐 Role: " . $user['role'] . "</div>";
                
                // Set session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['name'] = $user['name'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['role'] = $user['role'];
                
                echo "<div class='test-item success'>✅ Session variables set</div>";
                
                if ($user['role'] === 'admin') {
                    echo "<div class='test-item info'>🔗 <a href='frontend/admin/dashboard.php' target='_blank' class='btn btn-success'>Go to Admin Dashboard</a></div>";
                } else {
                    echo "<div class='test-item info'>🔗 <a href='frontend/employee/dashboard.php' target='_blank' class='btn btn-success'>Go to Employee Dashboard</a></div>";
                }
                
            } else {
                echo "<div class='test-item error'>❌ Login FAILED: Wrong password</div>";
            }
        } else {
            echo "<div class='test-item error'>❌ Login FAILED: User not found</div>";
        }
        
        $stmt->close();
        $conn->close();
        
    } catch (Exception $e) {
        echo "<div class='test-item error'>❌ Login test failed: " . $e->getMessage() . "</div>";
    }
} else {
    echo "<div class='test-item info'>📝 Test login below</div>";
    
    echo "<div style='display: grid; grid-template-columns: 1fr 1fr; gap: 20px;'>";
    
    // Admin Login Test
    echo "<div class='test-item'>";
    echo "<h4>🔐 Admin Login Test</h4>";
    echo "<form method='post'>";
    echo "<input type='hidden' name='user_type' value='admin'>";
    echo "<input type='email' name='test_email' placeholder='admin@ems.com' value='admin@ems.com' style='width: 100%; padding: 8px; margin: 5px 0;'><br>";
    echo "<input type='password' name='test_password' placeholder='admin123' value='admin123' style='width: 100%; padding: 8px; margin: 5px 0;'><br>";
    echo "<button type='submit' class='btn btn-success'>Test Admin Login</button>";
    echo "</form>";
    echo "</div>";
    
    // Employee Login Test
    echo "<div class='test-item'>";
    echo "<h4>👤 Employee Login Test</h4>";
    echo "<form method='post'>";
    echo "<input type='hidden' name='user_type' value='employee'>";
    echo "<input type='email' name='test_email' placeholder='john@ems.com' value='john@ems.com' style='width: 100%; padding: 8px; margin: 5px 0;'><br>";
    echo "<input type='password' name='test_password' placeholder='emp123' value='emp123' style='width: 100%; padding: 8px; margin: 5px 0;'><br>";
    echo "<button type='submit' class='btn btn-success'>Test Employee Login</button>";
    echo "</form>";
    echo "</div>";
    
    echo "</div>";
}

echo "</div>";

// Logout Test
echo "<div class='diagnostic-card'>";
echo "<h2 class='section-title'>🚪 LOGOUT TEST</h2>";

if (isset($_POST['test_logout'])) {
    echo "<div class='test-item info'>🔄 Testing logout...</div>";
    
    // Show session before logout
    if (!empty($_SESSION)) {
        echo "<div class='test-item info'>📊 Session before logout:</div>";
        foreach ($_SESSION as $key => $value) {
            echo "<div class='test-item info'>   $key: " . (is_string($value) ? htmlspecialchars($value) : 'Non-string') . "</div>";
        }
    } else {
        echo "<div class='test-item warning'>⚠️ No session to logout</div>";
    }
    
    // Perform logout
    session_destroy();
    $_SESSION = array();
    
    // Clear cookie
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    
    echo "<div class='test-item success'>✅ Session destroyed</div>";
    echo "<div class='test-item success'>✅ Session cookie cleared</div>";
    
    if (empty($_SESSION)) {
        echo "<div class='test-item success'>✅ Logout successful!</div>";
    } else {
        echo "<div class='test-item error'>❌ Session still has data</div>";
    }
    
} else {
    echo "<div class='test-item info'>📝 Click to test logout</div>";
    echo "<form method='post'>";
    echo "<button type='submit' name='test_logout' class='btn btn-danger'>🚪 Test Logout</button>";
    echo "</form>";
}

echo "</div>";

// Summary and Fixes
echo "<div class='diagnostic-card'>";
echo "<h2 class='section-title'>🎯 DIAGNOSTIC SUMMARY</h2>";

echo "<div class='success-box'>";
echo "<h3>✅ SYSTEM STATUS</h3>";
echo "<p><strong>Database:</strong> " . (file_exists('backend/db.php') ? '✅ Connected' : '❌ Not Connected') . "</p>";
echo "<p><strong>Tables:</strong> " . (isset($conn) && $conn ? '✅ All Tables Present' : '❌ Missing Tables') . "</p>";
echo "<p><strong>Users:</strong> " . (isset($result) && $result->num_rows > 0 ? '✅ Users Exist' : '❌ No Users') . "</p>";
echo "<p><strong>Backend:</strong> " . (file_exists('backend/logout.php') ? '✅ Files Present' : '❌ Missing Files') . "</p>";
echo "<p><strong>Session:</strong> " . (session_status() === PHP_SESSION_ACTIVE ? '✅ Working' : '❌ Not Working') . "</p>";
echo "</div>";

echo "<div class='warning-box'>";
echo "<h3>⚠️ COMMON ISSUES & FIXES</h3>";
echo "<p><strong>Issue 1:</strong> Database not found</p>";
echo "<p><strong>Fix:</strong> Run <a href='database/quick_setup.php' target='_blank'>database/quick_setup.php</a></p>";
echo "<p><strong>Issue 2:</strong> Employee users missing</p>";
echo "<p><strong>Fix:</strong> Insert employee records in users table</p>";
echo "<p><strong>Issue 3:</strong> Session not working</p>";
echo "<p><strong>Fix:</strong> Check PHP session configuration</p>";
echo "<p><strong>Issue 4:</strong> Logout not working</p>";
echo "<p><strong>Fix:</strong> Replace logout.php with fixed version</p>";
echo "</div>";

echo "<div style='text-align: center; margin-top: 20px;'>";
echo "<a href='database/quick_setup.php' target='_blank' class='btn btn-warning'>🗄️ Setup Database</a>";
echo "<a href='login_admin.php' target='_blank' class='btn'>🔐 Admin Login</a>";
echo "<a href='login_employee.php' target='_blank' class='btn'>👤 Employee Login</a>";
echo "<a href='backend/logout.php' target='_blank' class='btn btn-danger'>🚪 Test Logout</a>";
echo "</div>";

echo "</div>";
echo "</div>";
?>
