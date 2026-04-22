<?php
// URL Test and Troubleshooting
echo "<h2>🔍 URL Troubleshooting</h2>";

// Get current URL info
$current_url = $_SERVER['REQUEST_URI'];
$script_name = $_SERVER['SCRIPT_NAME'];
$host = $_SERVER['HTTP_HOST'];
$method = $_SERVER['REQUEST_METHOD'];

echo "<h3>Current Request Info:</h3>";
echo "Host: $host<br>";
echo "URL: $current_url<br>";
echo "Script: $script_name<br>";
echo "Method: $method<br>";

// Check if file exists
$file_path = __DIR__ . '/' . basename($script_name);
echo "File Path: $file_path<br>";
echo "File Exists: " . (file_exists($file_path) ? '✅ Yes' : '❌ No') . "<br>";

// List available admin files
echo "<h3>Available Admin Files:</h3>";
$admin_dir = __DIR__ . '/admin';
if (is_dir($admin_dir)) {
    $files = scandir($admin_dir);
    foreach ($files as $file) {
        if ($file !== '.' && $file !== '..') {
            echo "📄 $file<br>";
        }
    }
}

// Test database connection
echo "<h3>Database Test:</h3>";
try {
    $conn = new mysqli('localhost', 'root', '', 'ems_db');
    if ($conn->connect_error) {
        echo "❌ Database connection failed: " . $conn->connect_error . "<br>";
    } else {
        echo "✅ Database connection successful<br>";
        $conn->close();
    }
} catch (Exception $e) {
    echo "❌ Database error: " . $e->getMessage() . "<br>";
}

echo "<hr>";
echo "<h3>🎯 Common Solutions:</h3>";
echo "<strong>1.</strong> Clear browser cache (Ctrl+F5)<br>";
echo "<strong>2.</strong> Check URL spelling<br>";
echo "<strong>3.</strong> Use direct file path: <a href='frontend/admin/dashboard.php'>frontend/admin/dashboard.php</a><br>";
echo "<strong>4.</strong> Check Apache error logs<br>";
echo "<strong>5.</strong> Restart Apache/XAMPP if needed<br>";

echo "<hr>";
echo "<h3>📋 Test Links:</h3>";
echo "<a href='frontend/admin/dashboard.php'>🏠 Admin Dashboard</a><br>";
echo "<a href='frontend/employee/dashboard.php'>👤 Employee Dashboard</a><br>";
echo "<a href='test_notifications_flow.php'>🔔 Test Notifications</a><br>";
echo "<a href='debug_notifications.php'>🔍 Debug Notifications</a><br>";
?>
