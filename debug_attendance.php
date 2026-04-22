<?php
// Debug Dashboard Attendance Issue
include("backend/db.php");

echo "<h2>Dashboard Attendance Debug</h2>";

$today = date('Y-m-d');
echo "<p><strong>Today's Date:</strong> $today</p>";

// Check all attendance records for today
echo "<h3>All Attendance Records for Today:</h3>";
$result = $conn->query("SELECT a.id, a.employee_id, a.status, a.check_in, u.name, u.email FROM attendance a JOIN users u ON a.employee_id = u.id WHERE a.date = '$today' ORDER BY a.created_at DESC");

if ($result && $result->num_rows > 0) {
    echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
    echo "<tr><th>ID</th><th>Employee</th><th>Email</th><th>Status</th><th>Check In</th><th>Created At</th></tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>{$row['id']}</td>";
        echo "<td>{$row['name']}</td>";
        echo "<td>{$row['email']}</td>";
        echo "<td><strong>{$row['status']}</strong></td>";
        echo "<td>{$row['check_in']}</td>";
        echo "<td>{$row['created_at']}</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p style='color: red;'>No attendance records found for today!</p>";
}

// Count by status
echo "<h3>Attendance Counts by Status:</h3>";
$statuses = ['present', 'absent', 'late', 'leave'];
foreach ($statuses as $status) {
    $stmt = $conn->prepare("SELECT COUNT(*) as count FROM attendance WHERE date = ? AND status = ?");
    $stmt->bind_param("ss", $today, $status);
    $stmt->execute();
    $count = $stmt->get_result()->fetch_assoc()['count'];
    echo "<p><strong>$status:</strong> $count</p>";
    $stmt->close();
}

// Test the exact query used in admin dashboard
echo "<h3>Admin Dashboard Query Test:</h3>";
$stmt = $conn->prepare("SELECT COUNT(*) as count FROM attendance WHERE date = ? AND status = 'present'");
$stmt->bind_param("s", $today);
$stmt->execute();
$result = $stmt->get_result();
$count = $result->fetch_assoc()['count'];
echo "<p><strong>Present Today (Admin Dashboard Query):</strong> $count</p>";
$stmt->close();

// Check if there are any duplicate entries
echo "<h3>Duplicate Check:</h3>";
$result = $conn->query("SELECT employee_id, COUNT(*) as count FROM attendance WHERE date = '$today' GROUP BY employee_id HAVING count > 1");
if ($result && $result->num_rows > 0) {
    echo "<p style='color: orange;'>Found duplicate attendance records:</p>";
    while ($row = $result->fetch_assoc()) {
        echo "<p>Employee ID {$row['employee_id']}: {$row['count']} records</p>";
    }
} else {
    echo "<p style='color: green;'>No duplicate records found</p>";
}

// Check recent activity (last 5 minutes)
echo "<h3>Recent Activity (Last 5 Minutes):</h3>";
$five_min_ago = date('Y-m-d H:i:s', strtotime('-5 minutes'));
$result = $conn->query("SELECT a.*, u.name FROM attendance a JOIN users u ON a.employee_id = u.id WHERE a.created_at >= '$five_min_ago' ORDER BY a.created_at DESC");
if ($result && $result->num_rows > 0) {
    echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
    echo "<tr><th>Employee</th><th>Status</th><th>Check In</th><th>Created At</th></tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>{$row['name']}</td>";
        echo "<td>{$row['status']}</td>";
        echo "<td>{$row['check_in']}</td>";
        echo "<td>{$row['created_at']}</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p>No recent activity in the last 5 minutes</p>";
}

echo "<hr>";
echo "<p><a href='complete_fix.php' style='background: #667eea; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Run Complete Fix</a></p>";
echo "<p><a href='frontend/admin/dashboard.php' style='background: #e74c3c; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Admin Dashboard</a></p>";

$conn->close();
?>
