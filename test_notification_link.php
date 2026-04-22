<?php
// Test script to verify notification link paths
echo "<h2>Notification Link Path Test</h2>";

// Get current page
$current_page = $_SERVER['PHP_SELF'];
echo "Current page: " . $current_page . "<br>";

// Test different relative paths
echo "<h3>Testing Paths from Current Location:</h3>";

// Test 1: notifications.php (same directory)
$path1 = "notifications.php";
echo "Path 1: '$path1' -> ";
if (file_exists($path1)) {
    echo "✅ EXISTS";
} else {
    echo "❌ NOT FOUND";
}
echo "<br>";

// Test 2: ./notifications.php (explicit same directory)
$path2 = "./notifications.php";
echo "Path 2: '$path2' -> ";
if (file_exists($path2)) {
    echo "✅ EXISTS";
} else {
    echo "❌ NOT FOUND";
}
echo "<br>";

// Test 3: ../employee/notifications.php (up one level, into employee)
$path3 = "../employee/notifications.php";
echo "Path 3: '$path3' -> ";
if (file_exists($path3)) {
    echo "✅ EXISTS";
} else {
    echo "❌ NOT FOUND";
}
echo "<br>";

// Test 4: ../../frontend/employee/notifications.php (up two levels, into frontend/employee)
$path4 = "../../frontend/employee/notifications.php";
echo "Path 4: '$path4' -> ";
if (file_exists($path4)) {
    echo "✅ EXISTS";
} else {
    echo "❌ NOT FOUND";
}
echo "<br>";

// Test absolute path
$path5 = "C:/xampp/htdocs/ems_project/ems_project/frontend/employee/notifications.php";
echo "Path 5 (Absolute): '$path5' -> ";
if (file_exists($path5)) {
    echo "✅ EXISTS";
} else {
    echo "❌ NOT FOUND";
}
echo "<br>";

echo "<h3>Current Working Directory:</h3>";
echo getcwd() . "<br>";

echo "<h3>Directory Contents:</h3>";
$files = scandir('.');
foreach ($files as $file) {
    if ($file !== '.' && $file !== '..') {
        echo "- $file<br>";
    }
}

echo "<hr>";
echo "<h3>Test Links:</h3>";
echo "<a href='notifications.php'>Test Link 1: notifications.php</a><br>";
echo "<a href='./notifications.php'>Test Link 2: ./notifications.php</a><br>";
echo "<a href='../employee/notifications.php'>Test Link 3: ../employee/notifications.php</a><br>";
echo "<a href='../../frontend/employee/notifications.php'>Test Link 4: ../../frontend/employee/notifications.php</a><br>";

echo "<hr>";
echo "<h3>Include Test:</h3>";
echo "Including notification bell component...<br>";
include 'frontend/components/notification_bell_simple.php';
?>
