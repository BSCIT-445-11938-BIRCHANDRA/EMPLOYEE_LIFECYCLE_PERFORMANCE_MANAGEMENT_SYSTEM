<?php
include 'backend/db.php';

echo "<h3>Debug Attendance Data for Employee ID 8 (nyx)</h3>";

// Get all attendance records for nyx
$stmt = $conn->prepare("SELECT * FROM attendance WHERE employee_id = ? ORDER BY date DESC");
$stmt->bind_param("i", 8);
$stmt->execute();
$result = $stmt->get_result();

echo "<h4>All Attendance Records:</h4>";
echo "<table border='1' style='border-collapse: collapse; margin: 20px 0;'>";
echo "<tr><th>Date</th><th>Status</th><th>Check In</th><th>Check Out</th></tr>";

$total_present = 0;
while ($row = $result->fetch_assoc()) {
    echo "<tr>";
    echo "<td>" . $row['date'] . "</td>";
    echo "<td>" . $row['status'] . "</td>";
    echo "<td>" . ($row['check_in'] ?? 'N/A') . "</td>";
    echo "<td>" . ($row['check_out'] ?? 'N/A') . "</td>";
    echo "</tr>";
    
    if ($row['status'] === 'present') {
        $total_present++;
    }
}
echo "</table>";

echo "<h4>Current Month (April 2026) Attendance:</h4>";
$this_month = date('Y-m');
$stmt = $conn->prepare("SELECT * FROM attendance WHERE employee_id = ? AND date LIKE ? ORDER BY date");
$month_pattern = $this_month . '%';
$stmt->bind_param("is", 8, $month_pattern);
$stmt->execute();
$result = $stmt->get_result();

$present_this_month = 0;
echo "<table border='1' style='border-collapse: collapse; margin: 20px 0;'>";
echo "<tr><th>Date</th><th>Status</th><th>Check In</th><th>Check Out</th></tr>";

while ($row = $result->fetch_assoc()) {
    echo "<tr>";
    echo "<td>" . $row['date'] . "</td>";
    echo "<td>" . $row['status'] . "</td>";
    echo "<td>" . ($row['check_in'] ?? 'N/A') . "</td>";
    echo "<td>" . ($row['check_out'] ?? 'N/A') . "</td>";
    echo "</tr>";
    
    if ($row['status'] === 'present') {
        $present_this_month++;
    }
}
echo "</table>";

echo "<h3>Summary:</h3>";
echo "<p><strong>Total Present Days (All Time):</strong> $total_present</p>";
echo "<p><strong>Present This Month ($this_month):</strong> $present_this_month</p>";

// Test the exact query from dashboard
echo "<h3>Testing Dashboard Query:</h3>";
$stmt = $conn->prepare("SELECT COUNT(*) as count FROM attendance WHERE employee_id = ? AND status = 'present' AND date LIKE ?");
$stmt->bind_param("is", 8, $month_pattern);
$stmt->execute();
$result = $stmt->get_result();
$count = $result->fetch_assoc()['count'];

echo "<p><strong>Dashboard Query Result:</strong> $count present days</p>";

$conn->close();
?>
