<?php
// Employee Dashboard Debug
session_start();
include("backend/db.php");

echo "<h2>Employee Dashboard Debug</h2>";

// Simulate employee login (ID: 2 for John Smith)
$employee_id = 2; // John Smith's ID
echo "<p><strong>Testing with Employee ID:</strong> $employee_id</p>";

$today = date('Y-m-d');
$this_month = date('Y-m');
echo "<p><strong>Today:</strong> $today</p>";
echo "<p><strong>This Month:</strong> $this_month</p>";

// Check today's attendance
echo "<h3>Today's Attendance:</h3>";
$stmt = $conn->prepare("SELECT status, check_in FROM attendance WHERE employee_id = ? AND date = ?");
$stmt->bind_param("is", $employee_id, $today);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    $attendance = $result->fetch_assoc();
    echo "<p style='color: green;'>✅ Found: Status = {$attendance['status']}, Check-in = {$attendance['check_in']}</p>";
} else {
    echo "<p style='color: red;'>❌ No attendance found for today</p>";
}
$stmt->close();

// Test present days query (OLD WAY)
echo "<h3>Present Days (OLD Complex Query):</h3>";
$stmt = $conn->prepare("SELECT COUNT(*) as count FROM attendance WHERE employee_id = ? AND status = 'present' AND DATE(date) >= DATE(?) AND DATE(date) <= LAST_DAY(?)");
$stmt->bind_param("iss", $employee_id, $this_month, $this_month);
$stmt->execute();
$result = $stmt->get_result();
$count = $result->fetch_assoc()['count'];
echo "<p>OLD Query Result: $count</p>";
$stmt->close();

// Test present days query (NEW WAY)
echo "<h3>Present Days (NEW Simple Query):</h3>";
$month_pattern = $this_month . '%';
$stmt = $conn->prepare("SELECT COUNT(*) as count FROM attendance WHERE employee_id = ? AND status = 'present' AND date LIKE ?");
$stmt->bind_param("is", $employee_id, $month_pattern);
$stmt->execute();
$result = $stmt->get_result();
$count = $result->fetch_assoc()['count'];
echo "<p>NEW Query Result: $count</p>";
$stmt->close();

// Show all attendance for this employee this month
echo "<h3>All Attendance Records This Month:</h3>";
$stmt = $conn->prepare("SELECT date, status, check_in FROM attendance WHERE employee_id = ? AND date LIKE ? ORDER BY date DESC");
$stmt->bind_param("is", $employee_id, $month_pattern);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
    echo "<tr><th>Date</th><th>Status</th><th>Check In</th></tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>{$row['date']}</td>";
        echo "<td><strong>{$row['status']}</strong></td>";
        echo "<td>{$row['check_in']}</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p>No attendance records found for this month</p>";
}
$stmt->close();

// Test inserting today's attendance if not exists
echo "<h3>Test Insert Today's Attendance:</h3>";
$stmt = $conn->prepare("SELECT id FROM attendance WHERE employee_id = ? AND date = ?");
$stmt->bind_param("is", $employee_id, $today);
$stmt->execute();
if ($stmt->get_result()->num_rows == 0) {
    $check_in = date('H:i');
    $insert = $conn->prepare("INSERT INTO attendance (employee_id, date, status, check_in, created_at) VALUES (?, ?, 'present', ?, NOW())");
    $insert->bind_param("iss", $employee_id, $today, $check_in);
    if ($insert->execute()) {
        echo "<p style='color: green;'>✅ Test attendance inserted for today</p>";
    } else {
        echo "<p style='color: red;'>❌ Failed to insert: " . $insert->error . "</p>";
    }
    $insert->close();
} else {
    echo "<p style='color: orange;'>⚠️ Attendance already exists for today</p>";
}
$stmt->close();

echo "<hr>";
echo "<p><a href='frontend/employee/dashboard.php' style='background: #667eea; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Employee Dashboard</a></p>";
echo "<p><a href='debug_attendance.php' style='background: #e74c3c; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>General Debug</a></p>";

$conn->close();
?>
