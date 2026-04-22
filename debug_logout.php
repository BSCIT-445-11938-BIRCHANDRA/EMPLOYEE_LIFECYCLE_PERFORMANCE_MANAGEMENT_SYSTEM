<?php
// Logout System Debug
echo "<h1>🔍 LOGOUT SYSTEM DEBUG</h1>";
echo "<style>
    body { font-family: Arial, sans-serif; margin: 20px; background: #f8f9fa; }
    .debug-section { background: white; padding: 20px; margin: 15px 0; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
    .section-title { color: #2c3e50; border-bottom: 3px solid #667eea; padding-bottom: 10px; margin-bottom: 15px; }
    .test-item { padding: 10px; margin: 10px 0; border-left: 4px solid #667eea; background: #f8f9fa; }
    .success { color: #27ae60; font-weight: bold; }
    .error { color: #e74c3c; font-weight: bold; }
    .warning { color: #f39c12; font-weight: bold; }
    .info { color: #3498db; }
    .code { background: #e9ecef; padding: 2px 5px; border-radius: 3px; font-family: monospace; }
    .btn { background: #667eea; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block; margin: 5px; }
    .btn-danger { background: #e74c3c; }
    .btn-success { background: #27ae60; }
    .session-info { background: #fff; padding: 15px; border: 1px solid #ddd; border-radius: 5px; margin: 10px 0; }
</style>";

echo "<div class='debug-section'>";
echo "<h2 class='section-title'>📁 Logout File Check</h2>";

// Check logout file
$logout_file = 'backend/logout.php';
if (file_exists($logout_file)) {
    echo "<div class='test-item success'>✅ Logout file exists: <span class='code'>$logout_file</span></div>";
    
    // Check file content
    $content = file_get_contents($logout_file);
    if (strpos($content, 'session_start()') !== false) {
        echo "<div class='test-item success'>✅ session_start() found in logout.php</div>";
    } else {
        echo "<div class='test-item error'>❌ session_start() missing in logout.php</div>";
    }
    
    if (strpos($content, 'session_destroy()') !== false) {
        echo "<div class='test-item success'>✅ session_destroy() found in logout.php</div>";
    } else {
        echo "<div class='test-item error'>❌ session_destroy() missing in logout.php</div>";
    }
    
    if (strpos($content, 'header(\'Location:\')') !== false) {
        echo "<div class='test-item success'>✅ Redirect found in logout.php</div>";
    } else {
        echo "<div class='test-item error'>❌ Redirect missing in logout.php</div>";
    }
} else {
    echo "<div class='test-item error'>❌ Logout file missing: <span class='code'>$logout_file</span></div>";
}

echo "</div>";

// Check header file
echo "<div class='debug-section'>";
echo "<h2 class='section-title'>📄 Header File Check</h2>";

$header_file = 'components/header.php';
if (file_exists($header_file)) {
    echo "<div class='test-item success'>✅ Header file exists: <span class='code'>$header_file</span></div>";
    
    $content = file_get_contents($header_file);
    if (strpos($content, 'session_start()') !== false) {
        echo "<div class='test-item success'>✅ session_start() found in header.php</div>";
    } else {
        echo "<div class='test-item error'>❌ session_start() missing in header.php</div>";
    }
    
    if (strpos($content, 'backend/logout.php') !== false) {
        echo "<div class='test-item success'>✅ Logout link found in header.php</div>";
    } else {
        echo "<div class='test-item error'>❌ Logout link missing in header.php</div>";
    }
    
    if (strpos($content, '$_SESSION[\'user_id\']') !== false) {
        echo "<div class='test-item success'>✅ Session check found in header.php</div>";
    } else {
        echo "<div class='test-item error'>❌ Session check missing in header.php</div>";
    }
} else {
    echo "<div class='test-item error'>❌ Header file missing: <span class='code'>$header_file</span></div>";
}

echo "</div>";

// Session status check
echo "<div class='debug-section'>";
echo "<h2 class='section-title'>🔄 Session Status Check</h2>";

echo "<div class='session-info'>";
echo "<strong>Session Status:</strong> " . session_status() . "<br>";
echo "<strong>Session ID:</strong> " . session_id() . "<br>";

// Start session if not started
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
    echo "<div class='test-item warning'>⚠️ Session was not active, started now</div>";
} else {
    echo "<div class='test-item success'>✅ Session is already active</div>";
}

echo "<strong>Current Session Data:</strong><br>";
if (empty($_SESSION)) {
    echo "<span class='warning'>No session data (user not logged in)</span><br>";
} else {
    foreach ($_SESSION as $key => $value) {
        echo "<span class='info'>$key: " . (is_string($value) ? htmlspecialchars($value) : print_r($value, true)) . "</span><br>";
    }
}
echo "</div>";

echo "</div>";

// Test logout functionality
echo "<div class='debug-section'>";
echo "<h2 class='section-title'>🧪 Logout Functionality Test</h2>";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['test_logout'])) {
    echo "<div class='test-item info'>🔄 Testing logout process...</div>";
    
    // Show session before logout
    echo "<div class='test-item info'>📊 Session before logout:</div>";
    if (empty($_SESSION)) {
        echo "<div class='test-item warning'>⚠️ No session data to clear</div>";
    } else {
        foreach ($_SESSION as $key => $value) {
            echo "<div class='test-item info'>📝 $key: " . (is_string($value) ? htmlspecialchars($value) : 'Non-string value') . "</div>";
        }
    }
    
    // Perform logout
    session_destroy();
    
    // Clear session cookie
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    
    echo "<div class='test-item success'>✅ Session destroyed successfully</div>";
    echo "<div class='test-item success'>✅ Session cookie cleared</div>";
    
    // Check session after logout
    echo "<div class='test-item info'>📊 Session after logout:</div>";
    if (empty($_SESSION)) {
        echo "<div class='test-item success'>✅ Session data cleared successfully</div>";
    } else {
        echo "<div class='test-item error'>❌ Session data still exists</div>";
    }
    
    echo "<div class='test-item success'>✅ Logout test completed successfully!</div>";
} else {
    echo "<div class='test-item info'>📝 Click below to test logout functionality</div>";
    echo "<form method='post'>";
    echo "<button type='submit' name='test_logout' class='btn btn-danger'>🚪 Test Logout</button>";
    echo "</form>";
}

echo "</div>";

// Create test session
echo "<div class='debug-section'>";
echo "<h2 class='section-title'>🧪 Create Test Session</h2>";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_session'])) {
    session_start();
    $_SESSION['user_id'] = 1;
    $_SESSION['name'] = 'Test User';
    $_SESSION['email'] = 'test@example.com';
    $_SESSION['role'] = 'admin';
    
    echo "<div class='test-item success'>✅ Test session created successfully</div>";
    echo "<div class='test-item info'>👤 User: Test User</div>";
    echo "<div class='test-item info'>📧 Email: test@example.com</div>";
    echo "<div class='test-item info'>🔐 Role: admin</div>";
    echo "<div class='test-item info'>🔄 Session ID: " . session_id() . "</div>";
} else {
    echo "<div class='test-item info'>📝 Create a test session to test logout</div>";
    echo "<form method='post'>";
    echo "<button type='submit' name='create_session' class='btn btn-success'>👤 Create Test Session</button>";
    echo "</form>";
}

echo "</div>";

// Check logout links in admin and employee panels
echo "<div class='debug-section'>";
echo "<h2 class='section-title'>🔗 Logout Links Check</h2>";

$files_to_check = [
    'Admin Sidebar' => 'frontend/components/admin_sidebar.php',
    'Employee Sidebar' => 'frontend/components/employee_sidebar.php'
];

foreach ($files_to_check as $name => $file) {
    echo "<div class='test-item'>";
    echo "<strong>$name:</strong><br>";
    
    if (file_exists($file)) {
        echo "<span class='success'>✅ File exists</span><br>";
        
        $content = file_get_contents($file);
        if (strpos($content, 'logout.php') !== false) {
            echo "<span class='success'>✅ Logout link found</span><br>";
        } else {
            echo "<span class='error'>❌ Logout link missing</span><br>";
        }
    } else {
        echo "<span class='error'>❌ File missing</span><br>";
    }
    echo "</div>";
}

echo "</div>";

// Common logout issues
echo "<div class='debug-section'>";
echo "<h2 class='section-title'>🔧 Common Logout Issues & Fixes</h2>";

echo "<div class='test-item'>";
echo "<strong>Issue 1:</strong> Session not destroyed properly<br>";
echo "<strong>Symptoms:</strong> User stays logged in after logout<br>";
echo "<strong>Fix:</strong> Ensure session_destroy() is called<br>";
echo "<strong>Code:</strong> <span class='code'>session_destroy();</span>";
echo "</div>";

echo "<div class='test-item'>";
echo "<strong>Issue 2:</strong> Session cookie not cleared<br>";
echo "<strong>Symptoms:</strong> Browser remembers session after logout<br>";
echo "<strong>Fix:</strong> Clear session cookie<br>";
echo "<strong>Code:</strong> <span class='code'>setcookie(session_name(), '', time() - 42000, ...);</span>";
echo "</div>";

echo "<div class='test-item'>";
echo "<strong>Issue 3:</strong> Redirect not working<br>";
echo "<strong>Symptoms:</strong> Page stays on logout after logout<br>";
echo "<strong>Fix:</strong> Use proper header redirect<br>";
echo "<strong>Code:</strong> <span class='code'>header('Location: index.php');</span>";
echo "</div>";

echo "<div class='test-item'>";
echo "<strong>Issue 4:</strong> Session not started<br>";
echo "<strong>Symptoms:</strong> Session functions don't work<br>";
echo "<strong>Fix:</strong> Call session_start() at top<br>";
echo "<strong>Code:</strong> <span class='code'>session_start();</span>";
echo "</div>";

echo "</div>";

// Quick access links
echo "<div class='debug-section'>";
echo "<h2 class='section-title'>🚀 Quick Access</h2>";
echo "<div style='text-align: center;'>";
echo "<a href='index.php' class='btn'>🏠 Home Page</a>";
echo "<a href='login_admin.php' class='btn'>🔐 Admin Login</a>";
echo "<a href='login_employee.php' class='btn'>👤 Employee Login</a>";
echo "<a href='backend/logout.php' class='btn btn-danger'>🚪 Direct Logout</a>";
echo "</div>";
echo "</div>";

echo "<div class='debug-section'>";
echo "<h2 class='section-title'>📋 Summary</h2>";
echo "<div class='test-item info'>";
echo "<strong>Current Status:</strong><br>";
echo "- Logout file: " . (file_exists('backend/logout.php') ? '✅ Exists' : '❌ Missing') . "<br>";
echo "- Header file: " . (file_exists('components/header.php') ? '✅ Exists' : '❌ Missing') . "<br>";
echo "- Session status: " . (session_status() === PHP_SESSION_ACTIVE ? '✅ Active' : '❌ Inactive') . "<br>";
echo "- Session data: " . (empty($_SESSION) ? '❌ Empty' : '✅ Has data') . "<br>";
echo "</div>";
echo "</div>";
?>
