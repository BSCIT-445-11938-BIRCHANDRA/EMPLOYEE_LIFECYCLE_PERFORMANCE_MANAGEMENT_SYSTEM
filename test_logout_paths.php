<?php
// Test Logout Paths - Debug Path Issues
echo "<h1>🔍 LOGOUT PATH TESTER</h1>";
echo "<style>
    body { font-family: Arial, sans-serif; margin: 20px; background: #f8f9fa; }
    .test-card { background: white; padding: 20px; margin: 15px 0; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
    .section-title { color: #2c3e50; border-bottom: 3px solid #667eea; padding-bottom: 10px; margin-bottom: 15px; }
    .path-test { padding: 10px; margin: 10px 0; border-left: 4px solid #667eea; background: #f8f9fa; }
    .success { color: #27ae60; font-weight: bold; }
    .error { color: #e74c3c; font-weight: bold; }
    .warning { color: #f39c12; font-weight: bold; }
    .info { color: #3498db; }
    .code { background: #e9ecef; padding: 2px 5px; border-radius: 3px; font-family: monospace; }
    .btn { background: #667eea; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block; margin: 5px; }
    .btn-danger { background: #e74c3c; }
    .btn-success { background: #27ae60; }
</style>";

echo "<div class='test-card'>";
echo "<h2 class='section-title'>📁 CURRENT DIRECTORY STRUCTURE</h2>";

// Show current directory structure
$current_dir = __DIR__;
echo "<div class='path-test'>";
echo "<strong>Current Directory:</strong> <span class='code'>$current_dir</span><br>";
echo "<strong>Server Root:</strong> <span class='code'>" . $_SERVER['DOCUMENT_ROOT'] . "</span><br>";
echo "<strong>Current URL:</strong> <span class='code'>" . $_SERVER['PHP_SELF'] . "</span><br>";
echo "<strong>Base URL:</strong> <span class='code'>" . dirname($_SERVER['PHP_SELF']) . "</span><br>";
echo "</div>";

// Test logout file paths
echo "<h2 class='section-title'>🔗 LOGOUT PATH TESTS</h2>";

$logout_paths = [
    'From Admin Sidebar' => '../../backend/logout_final.php',
    'From Employee Sidebar' => '../../backend/logout_final.php', 
    'From Header' => 'backend/logout_final.php',
    'Direct Path' => 'backend/logout_final.php',
    'Root Path' => '/ems_project/backend/logout_final.php'
];

foreach ($logout_paths as $name => $path) {
    echo "<div class='path-test'>";
    echo "<strong>$name:</strong><br>";
    echo "<span class='code'>$path</span><br>";
    
    // Check if file exists
    $full_path = $current_dir . '/' . $path;
    if (file_exists($full_path)) {
        echo "<span class='success'>✅ File exists</span><br>";
    } else {
        echo "<span class='error'>❌ File NOT found at: $full_path</span><br>";
    }
    
    // Generate full URL
    $base_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']);
    $full_url = $base_url . '/' . $path;
    echo "<span class='info'>🔗 Full URL: <a href='$full_url' target='_blank'>$full_url</a></span><br>";
    echo "</div>";
}

echo "</div>";

// Test actual logout functionality
echo "<div class='test-card'>";
echo "<h2 class='section-title'>🧪 LOGOUT FUNCTIONALITY TEST</h2>";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['test_logout'])) {
    echo "<div class='path-test info'>";
    echo "<strong>Testing logout...</strong><br>";
    
    // Start session and set test data
    session_start();
    $_SESSION['user_id'] = 1;
    $_SESSION['name'] = 'Test User';
    $_SESSION['role'] = 'admin';
    
    echo "<span class='info'>📝 Session before logout:</span><br>";
    echo "User ID: " . $_SESSION['user_id'] . "<br>";
    echo "Name: " . $_SESSION['name'] . "<br>";
    echo "Role: " . $_SESSION['role'] . "<br>";
    
    // Test logout
    $_SESSION = array();
    session_destroy();
    
    // Clear cookie
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    
    echo "<span class='success'>✅ Session destroyed</span><br>";
    echo "<span class='success'>✅ Cookie cleared</span><br>";
    
    if (empty($_SESSION)) {
        echo "<span class='success'>✅ Logout successful!</span><br>";
    } else {
        echo "<span class='error'>❌ Session still has data</span><br>";
    }
    
    echo "</div>";
} else {
    echo "<div class='path-test'>";
    echo "<strong>Click to test logout functionality:</strong><br>";
    echo "<form method='post'>";
    echo "<button type='submit' name='test_logout' class='btn btn-danger'>🚪 Test Logout</button>";
    echo "</form>";
    echo "</div>";
}

echo "</div>";

// Show all logout files
echo "<div class='test-card'>";
echo "<h2 class='section-title'>📄 ALL LOGOUT FILES</h2>";

$logout_files = [
    'Original Logout' => 'backend/logout.php',
    'Working Logout' => 'backend/logout_working.php',
    'Fixed Logout' => 'backend/logout_fixed.php',
    'Final Logout' => 'backend/logout_final.php'
];

foreach ($logout_files as $name => $file) {
    echo "<div class='path-test'>";
    echo "<strong>$name:</strong> <span class='code'>$file</span><br>";
    
    if (file_exists($file)) {
        echo "<span class='success'>✅ Exists</span><br>";
        $size = filesize($file);
        echo "<span class='info'>Size: " . round($size/1024, 2) . " KB</span><br>";
        
        // Show file content preview
        $content = file_get_contents($file);
        if (strpos($content, 'session_destroy()') !== false) {
            echo "<span class='success'>✅ Has session_destroy()</span><br>";
        }
        if (strpos($content, 'header('Location:')') !== false) {
            echo "<span class='success'>✅ Has redirect</span><br>";
        }
    } else {
        echo "<span class='error'>❌ Missing</span><br>";
    }
    echo "</div>";
}

echo "</div>";

// Quick access links
echo "<div class='test-card'>";
echo "<h2 class='section-title'>🚀 QUICK ACCESS</h2>";
echo "<div style='text-align: center;'>";
echo "<a href='login_admin.php' class='btn'>🔐 Admin Login</a>";
echo "<a href='login_employee.php' class='btn'>👤 Employee Login</a>";
echo "<a href='backend/logout_final.php' class='btn btn-danger' target='_blank'>🚪 Test Logout Direct</a>";
echo "<a href='complete_system_check.php' class='btn btn-success'>🔍 System Check</a>";
echo "</div>";
echo "</div>";

echo "</div>";
?>
